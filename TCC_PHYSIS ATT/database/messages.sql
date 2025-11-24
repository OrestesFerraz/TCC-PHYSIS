CREATE TABLE mensagens_chat
(
    id              INT             NOT NULL    AUTO_INCREMENT,
    remetente_id    INT             NOT NULL,
    destinatario_id INT             NOT NULL,
    mensagem        TEXT            NOT NULL,
    data_envio      TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,
    lida            TINYINT(1)      DEFAULT 0,
    PRIMARY KEY (`id`),
    FOREIGN KEY (remetente_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (destinatario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    INDEX idx_conversa (remetente_id, destinatario_id),
    INDEX idx_data (data_envio)
) ENGINE = InnoDB;