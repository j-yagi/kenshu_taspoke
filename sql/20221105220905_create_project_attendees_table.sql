--
-- プロジェクト参加者テーブル作成
--
CREATE TABLE IF NOT EXISTS project_attendees (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'プロジェクト参加者ID',
    project_id INT(11) NOT NULL COMMENT 'プロジェクトID',
    user_id INT(11) COMMENT 'ユーザーID',
    email VARCHAR(255) COMMENT '招待ユーザーメールアドレス',
    role_code INT(1) NOT NULL DEFAULT 0 COMMENT '権限コード(0:一般,1:管理者)',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '作成日時',
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新日時'
);
