CREATE TABLE usuarios
(
    id       INT             NOT NULL    AUTO_INCREMENT,
    nome     VARCHAR(350)    NOT NULL,
    email    VARCHAR(350)    NOT NULL,
    urlperfil VARCHAR(350)   NOT NULL,    
    senha    VARCHAR(150)    NOT NULL,
    admin    TINYINT(1)      DEFAULT NULL,
    esp      TINYINT(1)      DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE (email)
) ENGINE = InnoDB;