<?php

/**
 * タスク画面管理コントローラー
 * 
 * タスクに関する画面の表示に必要な処理を管理するクラス。
 * 
 * @since 1.0.0
 */
require_once ROOT_DIR . '/app/Controllers/Controller.php';
require_once ROOT_DIR . '/app/Models/Project.php';
require_once ROOT_DIR . '/app/Models/ProjectAttendee.php';
require_once ROOT_DIR . '/app/Models/Task.php';
require_once ROOT_DIR . '/app/Models/Pokemon.php';
require_once ROOT_DIR . '/app/Models/PokemonBattleLog.php';
require_once ROOT_DIR . '/app/Models/UserPokemon.php';
require_once ROOT_DIR . '/app/Utill/Auth.php';
require_once ROOT_DIR . '/app/Utill/Validation.php';

class TaskController extends Controller
{
    private ?Project $project = null;

    public function __construct()
    {
        // タスクに関する画面はすべてログイン必須（未ログインの場合リダイレクト）
        Auth::guard();

        // 関連プロジェクトID必須
        $project = Project::find(Request::getParam('project_id'));
        if ($project === false) {
            redirect_error('不正なアクセスです。');
        }
        $this->project = $project;

        // 自身が参加しているプロジェクトか
        $attendees = ProjectAttendee::get('project_id = ? AND user_id = ?', [$project->id, Auth::getUserId()]);
        if (count($attendees) === 0) {
            redirect_error('不正なアクセスです。');
        }
    }

    /**
     * タスク一覧画面表示前処理
     *
     * @return array
     */
    public function index(): array
    {
        $tasks = Task::get('project_id = ?', [$this->project->id], 'updated_at DESC');

        return [
            'project' => $this->project,
            'tasks' => $tasks,
        ];
    }

    /**
     * タスク登録、更新画面表示前処理
     * 
     * @return array
     */
    public function edit(): array
    {
        $errors = Session::pull('errors', []);
        $old = Session::pull('old', []);

        $task = Task::findOrNew(Request::getParam('id'));
        $task->project_id = $this->project->id;
        if (!$task->code) {
            $task->code = $old['code'] ?? Task::getNewCode($this->project->id);
        }
        if (!$task->pokemon_id) {
            $task->pokemon_id = $old['pokemon_id'] ?? Pokemon::getRandomId();
        }

        $attendees = ProjectAttendee::get('project_id = ? AND user_id IS NOT NULL', [$this->project->id], 'role_code DESC');
        $pokemon = Pokemon::findOrInsert($task->pokemon_id);

        // 登録ボタン押下後のアクセスの場合
        if (Request::isPost()) {
            // CSRFトークンチェック
            if (!Request::checkCsrfToken('task.edit', Request::getPost('_token'))) {
                redirect_error('不正なアクセスです。', '/task/?project_id=' . Request::getParam('project_id'));
            }

            // バリデーションチェック
            $data = Request::getPost();
            $validation = (new Validation())
                ->required('title', $data['title'])
                ->length('title', $data['title'], 100)
                ->length('description', $data['description'], 2000)
                ->required('status_code', $data['status_code'])
                ->inArray('status_code', $data['status_code'], Task::STATUS)
                ->inArray('assign_user_id', $data['assign_user_id'], array_column($attendees, 'user_id'))
                ->date('start_date', $data['start_date'])
                ->date('expired_date', $data['expired_date'])
                ->date('complete_date', $data['complete_date'])
                ->numeric('expectation_time', $data['expectation_time'])
                ->numeric('actual_time', $data['actual_time']);
            if ($data['start_date'] && $data['expired_date']) {
                $validation->beforeOrEqual('start_date', $data['start_date'], $data['expired_date'], '期限日以前の日付を指定してください。');
            }
            if ($data['expired_date']) {
                $validation->afterOrEqual('expired_date', $data['expired_date'], date('Y-m-d'), '現在年月日以降の日付を指定してください。');
            }
            if ($data['start_date'] && $data['complete_date']) {
                $validation->afterOrEqual('complete_date', $data['complete_date'], $data['start_date'], '開始日以降の日付を指定してください。');
            }

            if ($validation->hasError()) {
                // バリデーションエラーがあった場合
                Session::set('errors', $validation->getErrors());
                Session::set('old', $data);

                // 再送信が発生しないよう自ページにリダイレクトする
                $this->redirect(Request::getCurrentUri());
            } else {
                // エラーがない場合、DB登録
                DB::begin();

                // 入力値をモデルオブジェクトにセット
                $task->fill($data);

                // 対戦履歴ログを登録するかどうか（タスク新規登録時ログ登録）
                $insert_log = $task->id ? false : true;

                // 対戦状態コードチェック
                if (
                    $task->battle_status_code === Task::BATTLE_STATUS['BATTLE'] &&
                    $task->status_code === Task::STATUS['COMPLETED'] &&
                    $task->complete_date &&
                    $task->expired_date
                ) {
                    $insert_log = true;

                    // 期限内に完了した場合勝利、期限を超えた場合敗北
                    if ($task->complete_date <= $task->expired_date) {
                        // 期限内に完了した場合勝利
                        $task->battle_status_code = Task::BATTLE_STATUS['VICTORY'];

                        // プロジェクト参加者全てにポケモン登録
                        $user_pokemons = [];
                        foreach ($attendees as $attendee) {
                            $user_pokemons[] = new UserPokemon([
                                'user_id' => $attendee->user_id,
                                'pokemon_id' => $task->pokemon_id,
                            ]);
                        }
                        UserPokemon::insertArray($user_pokemons);
                    } else {
                        // 期限を超えた場合敗北
                        $task->battle_status_code = Task::BATTLE_STATUS['DEFEAT'];
                    }
                }

                // タスク登録更新
                $task->upsert();

                // 対戦履歴ログ登録
                if ($insert_log) {
                    $log = new PokemonBattleLog([
                        'task_id' => $task->id,
                        'user_id' => $task->assign_user_id,
                        'pokemon_id' => $task->pokemon_id,
                        'action_code' => $task->battle_status_code,
                        'message' => $pokemon->name_ja . Task::BATTLE_STATUS_MSG[$task->battle_status_code],
                    ]);
                    $log->insert();
                }

                DB::commit();

                // 前画面にリダイレクト
                $this->redirect(Request::getParam('ref') ?: '/task/?project_id=' . Request::getParam('project_id'));
            }
        }

        return compact('task', 'attendees', 'pokemon', 'errors', 'old');
    }

    /**
     * タスク削除画面表示前処理
     *
     * @return void
     */
    public function delete()
    {
        $task = Task::find(Request::getParam('id'));
        if ($task === false) {
            redirect_error('プロジェクト情報が見つかりませんでした。');
        }

        // 削除ボタン押下後のアクセスの場合
        if (Request::isPost()) {
            // CSRFトークンチェック
            if (!Request::checkCsrfToken('task.delete', Request::getPost('_token'))) {
                redirect_error('不正なアクセスです。', '/task?project_id=' . $this->project->id);
            }

            // タスク削除
            $task->delete();

            // 一覧画面にリダイレクト
            $this->redirect('/task?project_id=' . $this->project->id);
        }
    }
}
