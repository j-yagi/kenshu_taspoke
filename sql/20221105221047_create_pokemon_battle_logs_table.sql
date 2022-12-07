--
-- ポケモン戦闘ログテーブル作成
--
CREATE TABLE IF NOT EXISTS pokemon_battle_logs (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT '戦闘ログID',
    task_id INT(11) COMMENT 'タスクID',
    user_id INT(11) COMMENT 'ユーザーID',
    pokemon_id INT(11) COMMENT 'ポケモンID',
    action_code INT(1) NOT NULL COMMENT 'アクションコード(1:戦闘開始,2:敗北,3:勝利)',
    message VARCHAR(100) NOT NULL COMMENT 'メッセージ',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '作成日時',
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新日時'
);
