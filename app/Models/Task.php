<?php

/**
 * タスクモデル
 * 
 * @since 1.0.0
 */

require_once __DIR__ . '/Model.php';

class Task extends Model
{
    protected const TABLE = 'tasks';

    // ステータスコード
    public const STATUS = [
        // 未対応
        'NOT_STARTED' => 0,
        // 対応中
        'PROCESSING' => 1,
        // 完了
        'COMPLETED' => 2,
    ];

    // 戦闘ステータスコード
    public const BATTLE_STATUS = [
        // 対戦中
        'BATTLE' => 1,
        // 敗北
        'DEFEAT' => 2,
        // 勝利
        'VICTORY' => 3,
    ];

    // ステータスコード表示名
    public const STATUS_DISP = [
        0 => '未対応',
        1 => '対応中',
        2 => '完了',
    ];

    // 戦闘ステータスコード表示名
    public const BATTLE_STATUS_DISP = [
        1 => '対戦中',
        2 => '敗北',
        3 => '勝利',
    ];

    // 戦闘ステータスメッセージ
    public const BATTLE_STATUS_MSG = [
        1 => 'がとびだしてきた！',
        2 => 'ににげられた…',
        3 => 'をつかまえた！',
    ];

    public ?int $id = null;
    public ?int $project_id = null;
    public ?int $code = null;
    public ?string $title = null;
    public ?string $description = null;
    public int $status_code = 0;
    public ?int $assign_user_id = null;
    public ?string $start_date = null;
    public ?string $expired_date = null;
    public ?string $complete_date = null;
    public ?float $expectation_time = null;
    public ?float $actual_time = null;
    public ?int $pokemon_id = null;
    public int $battle_status_code = 1;
    public ?string $created_at = null;
    public ?string $updated_at = null;

    protected array $fillable = [
        'project_id',
        'code',
        'title',
        'description',
        'status_code',
        'assign_user_id',
        'start_date',
        'expired_date',
        'complete_date',
        'expectation_time',
        'actual_time',
        'pokemon_id',
        'battle_status_code',
    ];

    /**
     * 条件に合致するレコードをモデルオブジェクトの配列で取得
     * 
     * 担当者名（users.name）も含めて取得
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
        $task_tb = static::TABLE;
        $user_tb = User::getTable();
        $sql =
            'SELECT ' .
            "    {$task_tb}.*, " .
            "    {$user_tb}.name AS assign_user_name " .
            "FROM {$task_tb} " .
            "    LEFT JOIN {$user_tb} ON {$user_tb}.id = {$task_tb}.assign_user_id ";
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

    /**
     * codeの登録値を取得（プロジェクト内タスク数＋1）
     *
     * @param int $project_id
     * @return int
     */
    public static function getNewCode(int $project_id): int
    {
        $sql  = 'SELECT COUNT(*) FROM ' . static::TABLE .
            ' WHERE project_id = ?';

        try {
            $stmt = DB::execute($sql, [$project_id]);
            $count = $stmt->fetchColumn(0);
        } catch (PDOException $e) {
            DB::error_exit($e, __METHOD__);
        }

        return $count + 1;
    }
}
