<?php

/**
 * プロジェクトモデル
 * 
 * @since 1.0.0
 */

require_once __DIR__ . '/Model.php';
require_once __DIR__ . '/ProjectAttendee.php';
require_once __DIR__ . '/User.php';

class Project extends Model
{
    protected const TABLE = 'projects';

    public ?int $id = null;
    public ?string $name = null;
    public ?string $created_at = null;
    public ?string $updated_at = null;

    protected array $fillable = [
        'name',
    ];

    /**
     * 指定したユーザーが参加中のプロジェクト一覧を取得
     *
     * @param int $user_id
     * @param string|null $kw
     * @return array
     */
    public static function getJoinAttendees(int $user_id, ?string $kw = null): array
    {
        $project_tb = self::TABLE;
        $attendee_tb = ProjectAttendee::getTable();
        $user_tb = User::getTable();
        $role_admin = ProjectAttendee::ROLE['ADMIN'];

        // 指定したユーザーが参加中のプロジェクト一覧を取得するSQL
        $sql = <<<EOQ
            SELECT
                *
            FROM (
                SELECT
                    {$project_tb}.*,
                    {$attendee_tb}.user_id AS owner_id,
                    {$attendee_tb}.role_code,
                    CASE WHEN {$attendee_tb}.role_code = {$role_admin} 
                        THEN {$user_tb}.name 
                        ELSE NULL 
                    END AS owner_name,
                    attendees_count.count AS attendees_count
                FROM
                    {$project_tb}
                    LEFT JOIN {$attendee_tb} ON {$attendee_tb}.project_id = {$project_tb}.id
                    INNER JOIN {$user_tb} ON {$attendee_tb}.user_id = {$user_tb}.id
                    INNER JOIN (
                        SELECT project_id, COUNT(*) AS count FROM {$attendee_tb} GROUP BY project_id
                    ) AS attendees_count ON attendees_count.project_id = {$project_tb}.id
                WHERE
                    {$project_tb}.id IN (
                        SELECT project_id FROM {$attendee_tb} WHERE {$attendee_tb}.user_id = :user_id
                    )
            ) AS {$project_tb}
            WHERE owner_name IS NOT NULL
        EOQ;

        if (!empty($kw)) {
            $sql .= " AND name LIKE :kw";
            $params['kw'] = "%{$kw}%";
        }

        $sql .= ' ORDER BY updated_at DESC';

        $params['user_id'] = $user_id;

        try {
            $stmt = DB::getConnection()->prepare($sql);
            $stmt->execute($params);
            $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Project::class);
            $list = $stmt->fetchAll();
        } catch (PDOException $e) {
            DB::error_exit($e, __METHOD__);
        }

        return $list;
    }

    /**
     * ログインユーザーがオーナーかどうか
     *
     * @return bool
     */
    public function isOwner(): bool
    {
        return Auth::getUserId() === ($this->owner_id ?? null);
    }
}
