--
-- ポケモンテーブル作成
--
CREATE TABLE IF NOT EXISTS pokemons (
    id INT(11) NOT NULL PRIMARY KEY COMMENT 'pokeapi.ポケモンID',
    name_en VARCHAR(50) COMMENT '英語名',
    name_ja VARCHAR(50) COMMENT '日本語名',
    front_img_url VARCHAR(255) COMMENT '前面画像URL',
    back_img_url VARCHAR(255) COMMENT '前面画像URL',
    img_url VARCHAR(255) COMMENT 'デフォルト画像URL',
    height FLOAT(3,1) COMMENT '高さ',
    weight FLOAT(3,1) COMMENT '重さ',
    type_name VARCHAR(50) COMMENT 'タイプ名',
    genera_name VARCHAR(50) COMMENT '分類名',
    flavor_text VARCHAR(255) COMMENT '説明文',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '作成日時',
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新日時'
);
