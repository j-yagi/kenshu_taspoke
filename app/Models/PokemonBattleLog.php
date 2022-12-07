<?php

/**
 * ポケモンバトルログモデル
 * 
 * @since 1.0.0
 */

require_once __DIR__ . '/Model.php';
require_once __DIR__ . '/Pokemon.php';
require_once __DIR__ . '/User.php';
require_once __DIR__ . '/Task.php';

class PokemonBattleLog extends Model
{
    protected const TABLE = 'pokemon_battle_logs';

    public ?int $id = null;
    public ?int $task_id = null;
    public ?int $user_id = null;
    public ?int $pokemon_id = null;
    public ?int $action_code = null;
    public ?string $message = null;
    public ?string $created_at = null;
    public ?string $updated_at = null;

    protected array $fillable = [
        'task_id',
        'user_id',
        'pokemon_id',
        'action_code',
        'message',
    ];

    /**
     * 条件に合致するレコードをモデルオブジェクトの配列で取得
     * 
     * ユーザー名、ポケモン名を含めて取得
     * ORDER BYの指定がない場合updated_atの降順
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
        $log_tb = static::TABLE;
        $user_tb = User::getTable();
        $pokemon_tb = Pokemon::getTable();
        $task_tb = Task::getTable();

        $sql = <<<EOQ
            SELECT
                {$log_tb}.*,
                {$user_tb}.name AS user_name,
                {$pokemon_tb}.name_ja AS pokemon_name_ja,
                {$pokemon_tb}.front_img_url AS pokemon_front_img_url,
                {$pokemon_tb}.back_img_url AS pokemon_back_img_url,
                {$task_tb}.code AS task_code
            FROM
                {$log_tb}
                LEFT JOIN {$user_tb} ON {$user_tb}.id = {$log_tb}.user_id
                INNER JOIN {$pokemon_tb} ON {$pokemon_tb}.id = {$log_tb}.pokemon_id
                LEFT JOIN {$task_tb} ON {$task_tb}.id = {$log_tb}.task_id
        EOQ;
        if ($where) {
            $sql .= ' WHERE ' . $where;
        }
        if ($order_by) {
            $sql .= ' ORDER BY ' . $order_by;
        } else {
            $sql .= ' ORDER BY ' . $log_tb . '.updated_at DESC ';
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
