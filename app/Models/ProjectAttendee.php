<?php

/**
 * プロジェクト参加者モデル
 * 
 * @since 1.0.0
 */

require_once __DIR__ . '/Model.php';

class ProjectAttendee extends Model
{
    protected const TABLE = 'project_attendees';

    // 権限コード
    public const ROLE = [
        // 一般
        'USER' => 0,
        // 管理者
        'ADMIN' => 1,
    ];

    public ?int $id = null;
    public ?int $project_id = null;
    public ?int $user_id = null;
    public ?string $email = null;
    public int $role_code = 0;
    public ?string $created_at = null;
    public ?string $updated_at = null;

    protected array $fillable = [
        'project_id',
        'user_id',
        'email',
        'role_code',
    ];

    /**
     * getのオーバーライド
     * 
     * 参加者ユーザー名を追加
     * 
     * @param string|null $where
     * @param array|null $params
     * @param string|null $order_by
     * @param int|null $limit
     * @return array
     */
    public static function get(
        ?string $where = null,
        ?array $params = [],
        ?string $order_by = null,
        ?int $limit = null
    ): array {
        $attendee_tb = static::TABLE;
        $user_tb = User::getTable();
        $sql =
            'SELECT ' .
            "    {$attendee_tb}.*, " .
            "    {$user_tb}.name AS user_name " .
            "FROM {$attendee_tb} " .
            "    LEFT JOIN {$user_tb} ON {$user_tb}.id = {$attendee_tb}.user_id ";
        if ($where) {
            $sql .= ' WHERE ' . $where;
        }
        if ($order_by) {
            $sql .= ' ORDER BY ' . $order_by;
        }
        if ($limit) {
            $sql .= ' LIMIT ' . $limit;
        }

        try {
            $stmt = DB::execute($sql, $params);
            $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, static::class);
            $result = $stmt->fetchAll();
        } catch (PDOException $e) {
            DB::error_exit($e, __METHOD__);
        }

        return $result;
    }
}
