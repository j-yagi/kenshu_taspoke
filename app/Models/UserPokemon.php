<?php

/**
 * ユーザーポケモンモデル
 * 
 * @since 1.0.0
 */

require_once __DIR__ . '/Model.php';
require_once __DIR__ . '/Pokemon.php';

class UserPokemon extends Model
{
    protected const TABLE = 'user_pokemons';

    public ?int $id = null;
    public ?int $user_id = null;
    public ?int $pokemon_id = null;
    public ?string $created_at = null;
    public ?string $updated_at = null;

    protected array $fillable = [
        'user_id',
        'pokemon_id',
    ];

    /**
     * 条件に合致するレコードをモデルオブジェクトの配列で取得
     * 
     * ポケモン情報を含めて取得
     * ORDER BY指定がない場合pokemon_id昇順
     * 
     * @param string|null $where
     * @param array|null $params
     * @param string|null $order_by
     * @param int|null $limit
     * @param int|null $offset
     * @return array
     */
    public static function getJoinPokemon(
        ?string $where = null,
        ?array $params = [],
        ?string $order_by = null,
        ?int $limit = null,
        ?int $offset = null
    ): array {
        $pokemon_tb = Pokemon::getTable();
        $user_pokemon_tb = static::TABLE;

        $sql = <<<EOQ
            SELECT
                {$pokemon_tb}.*,
                {$user_pokemon_tb}.user_id
            FROM
                {$user_pokemon_tb}
                INNER JOIN {$pokemon_tb} ON {$pokemon_tb}.id = {$user_pokemon_tb}.pokemon_id
        EOQ;
        if ($where) {
            $sql .= ' WHERE ' . $where;
        }
        if ($order_by) {
            $sql .= ' ORDER BY ' . $order_by;
        } else {
            $sql .= ' ORDER BY pokemon_id ';
        }
        if ($limit) {
            $sql .= ' LIMIT ' . $limit;
        }
        if ($offset) {
            $sql .= ' OFFSET ' . $offset;
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
     * ユーザー獲得済みのポケモン数を取得
     *
     * @param int $user_id
     * @return int
     */
    public static function getCount(int $user_id): int
    {
        $sql = 'SELECT COUNT(*) FROM ' . static::TABLE . ' WHERE user_id = ?';
        $stmt = DB::execute($sql, [$user_id]);

        return $stmt->fetchColumn(0);
    }
}
