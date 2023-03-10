--
-- プロジェクトテーブル作成
--
CREATE TABLE IF NOT EXISTS projects (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'プロジェクトID',
    name VARCHAR(100) NOT NULL COMMENT 'プロジェクト名',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '作成日時',
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新日時'
);
