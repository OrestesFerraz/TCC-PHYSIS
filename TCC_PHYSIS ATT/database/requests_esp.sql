CREATE TABLE requisicoes_esp (
    id            INT          NOT NULL AUTO_INCREMENT,
    id_usuario    INT          NOT NULL,
    profissao     VARCHAR(250) NOT NULL,
    bio           VARCHAR(250) NOT NULL,
    telefone      VARCHAR(250) NOT NULL,
    cpf           VARCHAR(250) NOT NULL,
    certificado   VARCHAR(500) NOT NULL,
    status        ENUM('pendente', 'aprovada', 'rejeitada') DEFAULT 'pendente',
    data_requisicao DATETIME   DEFAULT CURRENT_TIMESTAMP,
    data_resposta   DATETIME   DEFAULT NULL,
    id_admin        INT        DEFAULT NULL,
    motivo_rejeicao TEXT       DEFAULT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id),
    FOREIGN KEY (id_admin) REFERENCES usuarios(id),
    PRIMARY KEY (id)
) ENGINE = InnoDB;