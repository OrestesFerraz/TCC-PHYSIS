CREATE TABLE plantas
(
    id           INT             NOT NULL    AUTO_INCREMENT,
    nome         VARCHAR(150)    NOT NULL,   
    urlfoto      VARCHAR(350)    NULL,    
    descricao    TEXT            NULL,
    altura       VARCHAR(10)     NULL,
    uso          TEXT            NULL,
    solo         TEXT            NULL,
    locali       TEXT            NULL,
    plantio      TEXT            NULL,
    rega         TEXT            NULL,
    adubacao     TEXT            NULL,
    poda         TEXT            NULL,
    dificuldade  TEXT            NULL,
    id_especie INT             NOT NULL,
    id_usuario   INT             NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (id_especie) REFERENCES especies(id),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
) ENGINE = InnoDB;