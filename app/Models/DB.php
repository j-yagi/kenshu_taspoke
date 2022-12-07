<?php

/**
 * データベース接続、操作クラス
 * 
 * @since 1.0.0
 */

/**
 * @template PDO
 */
class DB
{
    /** @var PDO|null PDOインスタンス */
    private static ?PDO $pdo = null;

    /**
     * コンストラクタ
     * 
     */
    public function __construct()
    {
        if (is_null(self::$pdo)) {
            self::connect();
        }
    }

    /**
     * DB接続されたPDOインスタンスを取得
     *
     * @return PDO
     */
    public static function getConnection(): PDO
    {
        if (is_null(self::$pdo)) {
            self::connect();
        }

        return self::$pdo;
    }

    /**
     * 接続
     *
     * @return bool
     */
    private static function connect(): bool
    {
        // 接続情報
        // WARNING: 現状MySQLのみ対応
        $connections = Config::get('db.connections.' . Config::get('db.driver', 'mysql'));
        $dsn =
            "mysql:dbname={$connections['database']};" .
            "host={$connections['host']}:{$connections['port']};" .
            "charset={$connections['charset']}";
        $username = $connections['user'];
        $password = $connections['password'];

        // 設定
        // エラー時例外を投げる
        $options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
        // フェッチのデフォルト返却の型をオブジェクトに設定
        $options[PDO::ATTR_DEFAULT_FETCH_MODE] = PDO::FETCH_OBJ;
        // 安全性＋型を正しく取得するためプリペアドステートメントのエミュレートをOFFに設定
        // NOTE: エミュレートを使用していると型宣言していないカラムが全て文字列で返る
        // 例）モデルクラスで「?int $id = null」を宣言していない→ $id = "1"で返る
        $options[PDO::ATTR_EMULATE_PREPARES] = false;

        try {
            self::$pdo = new PDO($dsn, $username, $password, $options);
        } catch (PDOException $e) {
            self::error_exit($e, __METHOD__);
        }

        return true;
    }

    /**
     * SQLを実行
     *
     * @param string $sql
     * @param array|null $params
     * @return PDOStatement
     */
    public static function execute(string $sql, ?array $params = []): PDOStatement
    {
        if (is_null(self::$pdo)) {
            self::connect();
        }

        try {
            $stmt = self::$pdo->prepare($sql);
            $stmt->execute($params);
        } catch (Exception $e) {
            self::error_exit($e, __METHOD__);
        }

        return $stmt;
    }

    /**
     * 最後に挿入された行のIDを返す
     *
     * @return int
     */
    public static function lastInsertId(): int
    {
        if (is_null(self::$pdo)) {
            self::connect();
        }

        try {
            $id = self::$pdo->lastInsertId();
        } catch (Exception $e) {
            self::error_exit($e, __METHOD__);
        }

        return (int)$id;
    }

    /**
     * トランザクション中かどうか
     *
     * @return bool
     */
    public static function inTransaction(): bool
    {
        if (is_null(self::$pdo)) {
            self::connect();
        }

        return self::$pdo->inTransaction();
    }

    /**
     * トランザクションを開始する
     *
     * @return bool
     */
    public static function begin(): bool
    {
        if (self::inTransaction()) {
            return false;
        }

        try {
            $result = self::$pdo->beginTransaction();
        } catch (Exception $e) {
            self::error_exit($e, __METHOD__);
        }

        return $result;
    }

    /**
     * トランザクションをコミットする
     *
     * @return bool
     */
    public static function commit(): bool
    {
        if (!self::inTransaction()) {
            return false;
        }

        try {
            $result = self::$pdo->commit();
        } catch (Exception $e) {
            self::error_exit($e, __METHOD__);
        }

        return $result;
    }

    /**
     * トランザクションをロールバックする
     *
     * @return bool
     */
    public static function rollback(): bool
    {
        if (!self::inTransaction()) {
            return false;
        }

        try {
            $result = self::$pdo->rollback();
        } catch (Exception $e) {
            self::error_exit($e, __METHOD__);
        }

        return $result;
    }

    /**
     * エラー終了
     *
     * @param Exception $e
     * @param string|null $disp_message
     * @param string|null $method
     * @return never
     */
    public static function error_exit(Exception $e, ?string $method = null, ?string $disp_message = null): never
    {
        if (self::inTransaction()) {
            self::rollback();
        }

        if (isProduction()) {
            // 本番環境の場合、エラーページにリダイレクトして終了
            redirect_error($disp_message, null, null, 500);
            Log::error($e->getMessage(), $method);
        } else {
            // 開発環境の場合、エラーメッセージを出力して終了
            echo $e->getMessage();
        }

        exit;
    }
}
