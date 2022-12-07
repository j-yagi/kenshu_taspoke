--
-- ユーザーポケモンテーブル作成
--
CREATE TABLE IF NOT EXISTS user_pokemons (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'ユーザーポケモンID',
    user_id INT(11) NOT NULL COMMENT 'ユーザーID',
    pokemon_id INT(11) NOT NULL COMMENT 'ポケモンID',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '作成日時',
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新日時',

    -- 1ユーザーに同一ポケモンデータは1体
    UNIQUE (user_id, pokemon_id)
);
