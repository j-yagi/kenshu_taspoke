<?php

/**
 * ユーザーモデル
 * 
 * @since 1.0.0
 */

require_once __DIR__ . '/Model.php';

class User extends Model
{
    protected const TABLE = 'users';

    public ?int $id = null;
    public ?string $name = null;
    public ?string $email = null;
    public ?string $password = null;
    public ?string $created_at = null;
    public ?string $updated_at = null;

    protected array $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * パスワードをハッシュ化
     *
     * @param string $password
     * @return string
     */
    public static function passwordHash(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * DBから取得したパスワードと合致しているか
     *
     * @param string $password
     * @return bool
     */
    public function passwordCheck(string $password): bool
    {
        return password_verify($password, $this->password);
    }

    /**
     * メールアドレスでユーザー情報を取得
     *
     * @param string $email
     * @return self|false
     */
    public static function findByEmail(string $email)
    {
        $sql = 'SELECT * FROM ' . self::TABLE . ' WHERE email = ? LIMIT 1';
        $stmt = DB::execute($sql, [$email]);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, self::class);

        return $stmt->fetch();
    }
}
