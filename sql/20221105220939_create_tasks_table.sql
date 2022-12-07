--
-- タスクテーブル作成
--
CREATE TABLE IF NOT EXISTS tasks (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'タスクID',
    project_id INT(11) NOT NULL COMMENT 'プロジェクトID',
    code INT(11) NOT NULL COMMENT 'プロジェクト内タスクNO',
    title VARCHAR(100) NOT NULL COMMENT '件名',
    description TEXT COMMENT '説明',
    status_code INT(1) NOT NULL DEFAULT 0 COMMENT '状態コード(0:未対応,1:対応中,2:完了)',
    assign_user_id INT(11) COMMENT '担当者ユーザーID',
    start_date DATE COMMENT '開始日',
    expired_date DATE COMMENT '期限日',
    complete_date DATE COMMENT '完了日',
    expectation_time FLOAT(3,2) COMMENT '予定時間',
    actual_time FLOAT(3,2) COMMENT '実績時間',
    pokemon_id INT(11) COMMENT 'ポケモンID',
    battle_status_code INT(1) NOT NULL DEFAULT 1 COMMENT 'ポケモン戦闘状態コード(1:戦闘中,2:敗北,3:勝利)',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '作成日時',
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新日時'
);
