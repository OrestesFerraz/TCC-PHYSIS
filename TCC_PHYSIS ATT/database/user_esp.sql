CREATE TABLE usuario_esp (
    id_usuario    INT          NOT NULL,
    profissao     VARCHAR(250) NOT NULL,
    bio           VARCHAR(250) NOT NULL,
    telefone      VARCHAR(250) NOT NULL,
    cpf           VARCHAR(250) NOT NULL,
    certificado   VARCHAR(500) NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id),
    PRIMARY KEY (id_usuario)
);
    