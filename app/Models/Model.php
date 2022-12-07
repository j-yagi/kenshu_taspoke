<?php

/**
 * モデルの共通クラス
 * 
 * 特定のテーブルに対する操作、1レコードに対する操作の処理をまとめたクラス。
 * テーブルごとに継承してモデルクラスを作成する。
 * 
 * @since 1.0.0
 */

require_once __DIR__ . '/DB.php';

class Model
{
    /**
     * テーブル名
     * 
     * 継承先で定義する。
     * 空文字不可。
     * 
     * @var string
     */
    protected const TABLE = '';

    /**
     * プライマリキーカラム名
     * 
     * 空文字不可。
     * 
     * @var string
     */
    public const PKEY = 'id';

    /**
     * 作成日時カラム名
     * 
     * 作成日時カラムを持たないテーブルの場合は継承先で空文字にする。
     * 
     * @var string
     */
    public const CREATED_AT = 'created_at';

    /**
     * 更新日時カラム名
     * 
     * 更新日時カラムを持たないテーブルの場合は継承先で空文字にする。
     * 
     * @var string
     */
    public const UPDATED_AT = 'updated_at';

    /**
     * 登録、更新するカラム名リスト
     * 
     * 継承先で定義する。
     * 
     * @see Model::fill
     * @see Model::insert
     * @see Model::update
     * @var array
     */
    protected array $fillable = [];



    /**
     * コンストラクタ
     * 
     * @param array $attributes { string(カラム名): mixed(値), ... }
     */
    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);
    }

    /**
     * テーブル名を取得
     *
     * @return string
     */
    public static function getTable(): string
    {
        return static::TABLE;
    }

    /**
     * 連想配列の$fillableキーをクラスプロパティにセット
     * 
     * @param array $attributes
     * @return self
     */
    public function fill(array $attributes): self
    {
        foreach ($this->fillable as $name) {
            if (array_key_exists($name, $attributes)) {
                $this->$name = $attributes[$name] === '' ? null : $attributes[$name];
            }
        }

        return $this;
    }

    /**
     * モデルオブジェクトが保持しているpublicプロパティを連想配列で取得
     *
     * @return array
     */
    public function toArray(): array
    {
        $attributes = [];
        foreach ((new ReflectionObject($this))->getProperties(ReflectionProperty::IS_PUBLIC) as $prop) {
            $name = $prop->getName();
            $attributes[$name] = $this->$name;
        }

        return $attributes;
    }

    /**
     * 作成日時カラムを追加
     *
     * @param array $columns
     * @return array
     */
    public function addCreatedAtColumn(array $columns): array
    {
        if (static::CREATED_AT) {
            if (array_search(static::CREATED_AT, $columns) === false) {
                $columns[] = static::CREATED_AT;
            }
            $this->{static::CREATED_AT} = date('Y-m-d H:i:s');
        }

        return $columns;
    }

    /**
     * 更新日時カラムを追加
     *
     * @param array $columns
     * @return array
     */
    public function addUpdatedAtColumn(array $columns): array
    {
        if (static::UPDATED_AT) {
            if (array_search(static::UPDATED_AT, $columns) === false) {
                $columns[] = static::UPDATED_AT;
            }
            $this->{static::UPDATED_AT} = date('Y-m-d H:i:s');
        }

        return $columns;
    }

    /**
     * 保持しているプロパティ値でレコードを登録
     *
     * @return int 登録件数
     */
    public function insert(): int
    {
        $str_names = '';
        $str_prepareds = '';
        $params = [];
        $columns = $this->addCreatedAtColumn($this->addUpdatedAtColumn($this->fillable));
        foreach ($columns as $idx => $name) {
            if ($idx !== 0) {
                $str_names .= ', ';
                $str_prepareds .= ', ';
            }
            $str_names .= $name;
            $str_prepareds .= ':' . $name;
            $params[$name] = $this->$name;
        }

        $sql =  'INSERT INTO ' . static::TABLE . " ($str_names) VALUES ($str_prepareds) ";
        $stmt = DB::execute($sql, $params);

        if ($id = DB::lastInsertId()) {
            $this->{static::PKEY} = $id;
        }

        return $stmt->rowCount();
    }

    /**
     * 保持しているプロパティ値でレコードを更新
     *
     * @return int|false 更新件数、プライマリキーのレコードがない場合false
     */
    public function update()
    {
        if ($this->find($this->id) === false) {
            return false;
        }

        $str_sets = '';
        $params = [];
        foreach ($this->addUpdatedAtColumn($this->fillable) as $idx => $name) {
            if ($idx !== 0) {
                $str_sets .= ', ';
            }
            $str_sets .= "$name = :$name";
            $params[$name] = $this->$name;
        }
        $params[static::PKEY] = $this->{static::PKEY};

        $sql =  'UPDATE ' . static::TABLE . " SET $str_sets WHERE " . static::PKEY . ' = :' . static::PKEY;
        $stmt = DB::execute($sql, $params);


        return $stmt->rowCount();
    }

    /**
     * 保持しているプロパティ値でレコードを更新または登録
     *
     * プライマリーキーのレコードがあれば更新、ない場合登録する。
     * 
     * @return int 登録、更新件数
     */
    public function upsert(): int
    {
        if (!is_null($this->id) && $this->find($this->id) !== false) {
            // IDが格納されていても念のため再検索
            // プライマリキーがある場合は更新
            $result = $this->update();
        } else {
            // プライマリキーがない場合は登録
            $result = $this->insert();
        }

        return $result;
    }

    /**
     * レコードを削除
     *
     * @return int 削除件数
     */
    public function delete(): int
    {
        $sql = 'DELETE FROM ' . static::TABLE . ' WHERE ' . static::PKEY . ' = ? ';
        $stmt = DB::execute($sql, [$this->{static::PKEY}]);

        return $stmt->rowCount();
    }

    /**
     * プライマリキーを指定して1件取得
     * 
     * 見つからない場合はfalseを返す。
     * $instanceを渡した場合、取得結果を$instanceオブジェクトに反映する。
     * $instanceを渡さない場合、Modelインスタンスを生成して返す。
     *
     * @param int $id
     * @param Model $instance
     * @return self|false
     */
    public static function find(int $id, ?Model $instance = null)
    {
        $sql = 'SELECT * FROM ' . static::TABLE . ' WHERE ' . static::PKEY . ' = ? LIMIT 1';
        $stmt = DB::execute($sql, [$id]);

        if (is_null($instance)) {
            // 取得結果を格納するインスタンスが渡されなかった場合、新しく生成したインスタンスに格納
            $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, static::class);
        } else {
            // インスタンスが渡された場合、そのプロパティ値を更新
            $stmt->setFetchMode(PDO::FETCH_INTO, $instance);
        }

        return $stmt->fetch();
    }

    /**
     * 1レコードを取得または生成
     * 
     * プライマリキーで1件取得する。
     * 見つからない場合新しいインスタンスを生成して返す。
     * 
     * @param int|null $id
     * @return self
     */
    public static function findOrNew(?int $id): self
    {
        $model = $id ? static::find($id) : false;

        return $model ?: new static();
    }

    /**
     * 条件に合致するレコードをモデルオブジェクトの配列で取得
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
        $sql = 'SELECT * FROM ' . static::TABLE;
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
     * 条件に合致するレコードをすべて削除
     *
     * @param string $where
     * @param array|null $params
     * @return int
     */
    public static function deleteWhere(string $where, ?array $params = null): int
    {
        $sql = 'DELETE FROM ' . static::TABLE . ' WHERE ' . $where;
        $stmt = DB::execute($sql, $params);

        return $stmt->rowCount();
    }

    /**
     * 複数レコードを登録
     *
     * @param array<int, Model> $models
     * @return int 登録件数
     */
    public static function insertArray(array $models)
    {
        if ($models) {
            $params = [];
            $values = [];
            $columns = $models[0]->fillable;
            foreach ($models as $model) {
                $values[] = '(' . implode(',', array_fill(0, count($columns), '?')) . ')';
                foreach ($columns as $col_nm) {
                    $params[] = $model->$col_nm;
                }
            }

            $sql = 'INSERT INTO ' . static::TABLE . ' (' . implode(',', $columns) . ') VALUES ' . implode(',', $values);
            $stmt = DB::execute($sql, $params);

            return $stmt->rowCount();
        }

        return 0;
    }

    /**
     * ゲッターマジックメソッドのオーバーライド
     * 
     * 定義していないないプロパティを参照しようとしたときに自動的に呼ばれる
     *
     * @param string $name
     * @return null|DateTimeImmutable
     */
    public function __get(string $name)
    {
        preg_match('/^(.+)(_dt)$/', $name, $matches);
        if (
            isset($matches[1]) &&
            isset($matches[2]) &&
            property_exists($this, $matches[1]) &&
            is_string($this->{$matches[1]})
        ) {
            // 呼び出したプロパティ名が「_dt」で終わり、
            // 「_dt」の前の文字列のプロパティを持っている場合（例：created_at_dt）、
            // DateTimeImmutableに変換したプロパティを追加し返す。
            return $this->$name = new DateTimeImmutable($this->{$matches[1]});
        }
    }
}
