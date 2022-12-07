<?php

/**
 * バリデーションクラス
 * 
 * @since 1.0.0
 */

class Validation
{
    /**
     * エラーメッセージ格納用
     *
     * @var array
     */
    protected array $errors = [];

    /**
     * 空白をチェック 
     * 
     * @since 11.12 setString(string $str) 実装した。
     * @param string $name フォーム項目名
     * @param mixed $val チェックする文字列
     * @return self
     */
    public function required(string $name, $val): self
    {
        if (is_null($val) || trim($val) === '') {
            $this->errors[$name][__FUNCTION__] = '必須項目です。';
        }

        return $this;
    }

    /**
     * 文字の長さをチェック 
     * 
     * @since 11.12 setString(string $str, string $max. string $min='') 実装した。
     * @param string $name フォーム項目名
     * @param mixed $val チェックする文字列
     * @param int|null $max 文字数上限
     * @param int|null $min 文字数下限
     * @return self
     */
    public function length(string $name, $val, ?int $max = null, ?int $min = null): self
    {
        $data_len = mb_strlen($val);
        if ($max && $min) {
            if ($data_len > $max || $data_len < $min) {
                $this->errors[$name][__FUNCTION__] = "{$min}文字以上{$max}文字以内で入力してください。";
            }
        } elseif ($max && $data_len > $max) {
            $this->errors[$name][__FUNCTION__] = "{$max}文字以内で入力してください。";
        } elseif ($min && $data_len < $min) {
            $this->errors[$name][__FUNCTION__] = "{$min}文字以上で入力してください。";
        }

        return $this;
    }

    /**
     * メールアドレス形式をチェック 
     * 
     * @since 11.12 setString(string $str) 実装した。
     * 
     * @param string $name フォーム項目名
     * @param mixed $val チェックするメールアドレス
     * @return self
     */
    public function email(string $name, $val): self
    {
        if (!empty($val) && !preg_match('/^[a-z0-9._+^~-]+@[a-z0-9.-]+$/i', $val)) {
            $this->errors[$name][__FUNCTION__] = 'メールアドレスの形式で入力してください。';
        }

        return $this;
    }

    /**
     * DB登録値の重複をチェック 
     * 
     * @since 11.12 setString(string $str) 実装した。
     * 
     * @param string $name フォーム項目名
     * @param mixed $value チェックする値
     * @param string $table 重複チェックするテーブル名
     * @param string $unique_col 重複チェックするカラム名
     * @param int|null $exclusion_id 重複チェックから除外したいプライマリキー値（自身を除く場合等に使用）
     * @return self
     */
    public function unique(
        string $name,
        string $value,
        string $table,
        string $unique_col,
        ?int $exclusion_id = null
    ): self {
        $model_nm = ucfirst(camelize(rtrim($table, 's')));
        $model_path = ROOT_DIR . "/app/Models/$model_nm.php";
        if (!is_file($model_path)) {
            // 開発段階で発生しうるエラー
            // キャッチせず例外を発生させる
            throw new Exception("該当するモデルクラスがありません。（{$model_path}）");
        }

        require_once $model_path;

        $sql = "SELECT COUNT(*) as cnt FROM $table WHERE $unique_col = :unique_col";
        $params['unique_col'] = $value;
        if ($exclusion_id) {
            $sql .= ' AND ' . $model_nm::PKEY . ' <> :exclusion_id';
            $params['exclusion_id'] = $exclusion_id;
        }

        $stmt = DB::execute($sql, $params);
        $record = $stmt->fetch();

        if ($record->cnt > 0) {
            $this->errors[$name][__FUNCTION__] = '既に登録されています。';
        }

        return $this;
    }

    /**
     * 半角英数をチェック 
     * 
     * @since 11.12 setString(string $str) 実装した。
     * 
     * @param string $name フォーム項目名
     * @param mixed $val チェックする値
     * @return self
     */
    public function alphanumeric(string $name, $val): self
    {
        if (!empty($val) && !preg_match("/^[a-zA-Z0-9]+$/", $val)) {
            $this->errors[$name][__FUNCTION__] = '半角英数字で入力してください。';
        }

        return $this;
    }

    /**
     * 整数値をチェック
     *
     * @param string $name
     * @param mixed $val
     * @return self
     */
    public function integer(string $name, $val): self
    {
        if (!empty($val) && !preg_match("/^[0-9]+$/", $val)) {
            $this->errors[$name][__FUNCTION__] = '整数で入力してください。';
        }

        return $this;
    }

    /**
     * 配列に含まれるか
     *
     * @param string $name
     * @param mixed $val
     * @param array $array
     * @return self
     */
    public function inArray(string $name, $val, array $array): self
    {
        if (!empty($val) && !in_array($val, $array)) {
            $this->errors[$name][__FUNCTION__] = '不正な値です。';
        }

        return $this;
    }

    /**
     * 日付形式か
     *
     * @param string $name
     * @param mixed $val
     * @return self
     */
    public function date(string $name, $val): self
    {
        if (!empty($val) && !strtotime($val)) {
            $this->errors[$name][__FUNCTION__] = '有効な日付を指定してください。';
        }

        return $this;
    }

    /**
     * 指定した日付以前か
     *
     * @param string $name
     * @param mixed $val
     * @param string $date
     * @param string $msg
     * @return self
     */
    public function beforeOrEqual(string $name, $val, string $date, ?string $msg = null): self
    {
        if (!empty($val) && $val > $date) {
            $this->errors[$name][__FUNCTION__] = $msg ?: "{$date}以前の日付を指定してください。";
        }

        return $this;
    }

    /**
     * 指定した日付以降か
     *
     * @param string $name
     * @param mixed $val
     * @param string $date
     * @param string $msg
     * @return self
     */
    public function afterOrEqual(string $name, $val, string $date, ?string $msg = null): self
    {
        if (!empty($val) && $val < $date) {
            $this->errors[$name][__FUNCTION__] = $msg ?: "{$date}以前の日付を指定してください。";
        }

        return $this;
    }

    /**
     * 数字か
     *
     * @param string $name
     * @param mixed $val
     * @return self
     */
    public function numeric(string $name, $val): self
    {
        if (!empty($val) && !is_numeric($val)) {
            $this->errors[$name][__FUNCTION__] = '数字を入力してください。';
        }

        return $this;
    }

    /**
     * バリデーションエラー内容を配列で取得
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * バリデーションエラーがあったかどうか
     *
     * @return bool
     */
    public function hasError(): bool
    {
        return count($this->errors) > 0;
    }
}
