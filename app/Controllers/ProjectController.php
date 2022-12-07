<?php

/**
 * プロジェクト管理コントローラー
 * 
 * プロジェクトに関する画面の表示に必要な処理を管理するクラス。
 * 
 * @since 1.0.0
 */

require_once ROOT_DIR . '/app/Controllers/Controller.php';
require_once ROOT_DIR . '/app/Models/Project.php';
require_once ROOT_DIR . '/app/Models/PokemonBattleLog.php';
require_once ROOT_DIR . '/app/Utill/Auth.php';
require_once ROOT_DIR . '/app/Utill/Validation.php';

class ProjectController extends Controller
{
    /**
     * コンストラクタ
     */
    public function __construct()
    {
        // プロジェクトに関する画面はすべてログイン必須（未ログインの場合リダイレクト）
        Auth::guard();
    }

    /**
     * プロジェクト一覧画面表示前処理
     *
     * @param string|null $keyword
     * @return array
     */
    public function index(): array
    {
        // バリデーションチェック
        $errors = [];
        $validation = (new Validation())
            ->length('keyword', Request::getParam('keyword'), 255);
        if ($validation->hasError()) {
            $errors = $validation->getErrors();
        }

        // ログインユーザーが参加中のプロジェクト一覧取得
        $list = Project::getJoinAttendees(Auth::getUserId(), Request::getParam('keyword'));

        return compact('list', 'errors');
    }

    /**
     * プロジェクト照会画面表示前処理
     *
     * @param int $id プロジェクトID
     * @return array
     */
    public function show(): array
    {
        $project = Project::find(Request::getParam('id'));
        if ($project === false) {
            redirect_error('プロジェクト情報が見つかりませんでした。');
        }

        return compact('project');
    }

    /**
     * プロジェクト登録、更新画面表示前処理
     *
     * @return array
     */
    public function edit(): array
    {
        $project = Project::findOrNew(Request::getParam('id'));
        $errors = Session::pull('errors', []);
        $old = Session::pull('old', []);

        // 登録ボタン押下後のアクセスの場合
        if (Request::isPost()) {
            // CSRFトークンチェック
            if (!Request::checkCsrfToken('project.edit', Request::getPost('_token'))) {
                redirect_error('不正なアクセスです。', '/project');
            }

            // バリデーションチェック
            $data = Request::getPost();
            $validation = (new Validation())
                ->required('name', $data['name'])
                ->length('name', $data['name'], 100);

            if ($validation->hasError()) {
                // バリデーションエラーがあった場合
                Session::set('errors', $validation->getErrors());
                Session::set('old', $data);

                // 再送信が発生しないよう自ページにリダイレクトする
                $this->redirect(Request::getCurrentUri());
            } else {
                // エラーがない場合、DB登録
                DB::begin();

                // プロジェクト情報登録
                // $project = new Project($data);
                $project->fill($data);
                $project->upsert();

                // 参加者情報登録
                // NOTE: 現状参加者はログインユーザーのみ
                $params = [
                    'project_id' => $project->id,
                    'user_id' => Auth::getUserId(),
                ];
                $attendees = ProjectAttendee::get('project_id = :project_id AND user_id = :user_id', $params);
                if (count($attendees) === 0) {
                    $attendee = new ProjectAttendee($params);
                    $attendee->role_code = ProjectAttendee::ROLE['ADMIN'];
                    $attendee->insert();
                }

                DB::commit();

                // 前画面にリダイレクト
                $this->redirect(Request::getParam('ref') ?: '/project');
            }
        }

        return compact('project', 'errors', 'old');
    }

    /**
     * プロジェクト削除画面表示前処理
     *
     * @return void
     */
    public function delete()
    {
        $project = Project::find(Request::getParam('id'));
        if ($project === false) {
            redirect_error('プロジェクト情報が見つかりませんでした。');
        }

        // 削除ボタン押下後のアクセスの場合
        if (Request::isPost()) {
            // CSRFトークンチェック
            if (!Request::checkCsrfToken('project.delete', Request::getPost('_token'))) {
                redirect_error('不正なアクセスです。', '/project');
            }

            // DB削除
            DB::begin();

            // TODO: ノート関連タスク、プロジェクトにNULLをセット

            // プロジェクト参加者削除
            ProjectAttendee::deleteWhere('project_id = ?', [$project->id]);

            // 関連タスク削除
            Task::deleteWhere('project_id = ?', [$project->id]);

            // プロジェクト削除
            $project->delete();

            DB::commit();

            // 一覧画面にリダイレクト
            $this->redirect('/project');
        }
    }

    /**
     * ポケモン対戦履歴画面表示前処理
     *
     * @return array
     */
    public function battle_log(): array
    {
        // 関連プロジェクトID必須
        $project = Project::find(Request::getParam('project_id'));
        if ($project === false) {
            redirect_error('不正なアクセスです。');
        }

        // 自身が参加しているプロジェクトか
        $attendees = ProjectAttendee::get('project_id = ? AND user_id = ?', [$project->id, Auth::getUserId()]);
        if (count($attendees) === 0) {
            redirect_error('不正なアクセスです。');
        }

        // ポケモン対戦履歴ログ取得
        $logs = PokemonBattleLog::get('project_id = ?', [$project->id]);

        return compact('project', 'logs');
    }
}
