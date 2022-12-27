<?php

/**
 * ユーザー管理画面コントローラー
 * 
 * ユーザーに関する画面の表示に必要な処理を管理するクラス。
 * 
 * @since 1.0.0
 */

require_once ROOT_DIR . '/app/Controllers/Controller.php';
require_once ROOT_DIR . '/app/Models/User.php';
require_once ROOT_DIR . '/app/Utill/Validation.php';
require_once ROOT_DIR . '/app/Utill/Request.php';
require_once ROOT_DIR . '/app/Utill/Auth.php';

class UserController extends Controller
{
    /**
     * コンストラクタ
     * 
     */
    public function __construct()
    {
        // NOTE: アカウント登録、登録確認、登録完了、ログイン画面はログイン前のみ、
        // アカウント更新画面はログイン後のみアクセス可能なため、
        // Auth::guest()またはAuth::guard()は各メソッドで実行。
    }

    /**
     * ログイン画面表示前処理
     * 
     * @return array
     */
    public function login(): array
    {
        // ログイン済みの場合リダイレクト
        Auth::guest();

        $errors = [];
        $old = [];

        // ログインボタン押下後のアクセスの場合
        if (Request::isPost()) {
            // CSRFトークンチェック
            if (!Request::checkCsrfToken('user.login', Request::getPost('_token'))) {
                redirect_error('不正なアクセスです。', '/user/login.php');
            }

            // バリデーションチェック
            $data = Request::getPost();
            $validation = $this->loginDataValidation($data);
            if ($validation->hasError()) {
                $errors = $validation->getErrors();
                $old = $data;
            } else {
                // ユーザー情報取得
                $user = User::findByEmail($data['email']);
                if ($user === false) {
                    $errors['email']['exists'] = 'アカウントが見つかりません。';
                } elseif ($user->passwordCheck($data['password']) === false) {
                    $errors['email']['exists'] = 'アカウントが見つかりません。';
                } else {
                    // ログインしてダッシュボード画面へ
                    Auth::setUserId($user->id);
                    $this->redirect('/');
                }
            }
        }

        return compact('errors', 'old');
    }

    /**
     * ログアウト処理画面
     * 
     * @return never
     */
    public function logout(): never
    {
        // 未ログインの場合リダイレクト
        Auth::guard();

        // リクエストメソッド、CSRFトークンチェック
        if (
            !Request::isPost() ||
            !Request::checkCsrfToken('user.logout', Request::getPost('_token'))
        ) {
            redirect_error('不正なアクセスです。', '/');
        }

        // ログイン、セッション情報を削除してログイン画面へリダイレクト
        Auth::setUserId(null);
        $this->redirect('/user/login.php');
    }

    /**
     * アカウント登録画面表示前処理
     *
     * @return array
     */
    public function register(): array
    {
        // ログイン済みの場合リダイレクト
        Auth::guest();

        $errors = Session::pull('form_errors', []);
        $old = Session::pull('form_data.user.register', []);

        return compact('errors', 'old');
    }

    /**
     * アカウント登録確認画面表示前処理
     * 
     * @return array
     */
    public function confirm(): array
    {
        // ログイン済みの場合リダイレクト
        Auth::guest();

        // リクエストメソッド、CSRFトークンチェック
        if (
            !Request::isPost() ||
            !Request::checkCsrfToken('user.register', Request::getPost('_token'))
        ) {
            redirect_error('不正なアクセスです。', '/user/register.php');
        }

        // バリデーションチェック
        $data = Request::getPost();
        Session::set('form_data.user.register', $data);
        $validation = $this->registerDataValidation($data);
        if ($validation->hasError()) {
            // エラーがあった場合、エラー内容を保存して登録画面にリダイレクト
            Session::set('form_errors', $validation->getErrors());
            $this->redirect('/user/register.php');
        }

        return $data;
    }

    /**
     * アカウント登録完了画面表示前処理
     *
     * @return void
     */
    public function complete(): void
    {
        // ログイン済みの場合リダイレクト
        Auth::guest();

        // セッションデータチェック
        $data = Session::pull('form_data.user.register', false);
        if (!$data) {
            redirect_error('不正なアクセスです。', '/user/register.php');
        }

        // 念のため再度バリデーションチェック
        $validation = $this->registerDataValidation($data);
        if ($validation->hasError()) {
            // エラーがあった場合、エラー内容を保存して登録画面にリダイレクト
            Session::set('form_data.user.register', $data);
            Session::set('form_errors', $validation->getErrors());
            $this->redirect('/user/register.php');
        }

        // DB登録
        $user = new User($data);
        $user->password = User::passwordHash($data['password']);
        $user->insert();
    }

    /**
     * アカウント登録情報のバリデーションチェック
     *
     * @param array $data
     * @return Validation
     */
    private function registerDataValidation(array $data): Validation
    {
        $validation = new Validation();

        // 名前
        $validation
            ->required('name', $data['name'])
            ->length('name', $data['name'], 20);
        // メールアドレス
        $validation
            ->required('email', $data['email'])
            ->email('email', $data['email'])
            ->length('email', $data['email'], 255)
            ->unique('email', $data['email'], 'users', 'email');
        // パスワード
        $validation
            ->required('password', $data['password'])
            ->length('password', $data['password'], null, 8)
            ->alphanumeric('password', $data['password']);

        return $validation;
    }

    /**
     * ログイン情報のバリデーションチェック
     * 
     * @param array $data
     * @return Validation
     */
    private function loginDataValidation(array $data): Validation
    {
        $validation = new Validation();

        // メールアドレス
        $validation
            ->required('email', $data['email'])
            ->email('email', $data['email'])
            ->length('email', $data['email'], 255);
        // パスワード
        $validation
            ->required('password', $data['password'])
            ->length('password', $data['password'], null, 8)
            ->alphanumeric('password', $data['password']);

        return $validation;
    }

    /**
     * アカウント更新画面処理
     *
     * @return array
     */
    public function edit(): array
    {
        Auth::guard();

        $id = Auth::getUserId();
        $user = User::find($id);

        $errors = Session::get('errors', []);
        $old = Session::get('old', []);

            // バリデーションチェック
            $data = Request::getPost();
            $validation = $this->registerDataValidation($data);
            if ($validation->hasError()) {
                // バリデーションエラーがあった場合
                Session::set('errors', $validation->getErrors());
                Session::set('old', $data);

                // 前画面にリダイレクト
                $this->redirect('/user/updata.php');
            } else {
                // エラーがない場合、DB登録
                DB::begin();

                $user = new User($data);
                var_dump($data);
                $data->fill($data);
                $data->upsert();

                $params = [
                    'id' => Auth::getUserId(),
                    'name' => $user->name,
                    'email' => $user->email,
                    'password' => $user->password
                ];

                DB::commit();

                // 前画面にリダイレクト
                $this->redirect('/account');
            }

            return compact('user','errors','old');
        }

    }
