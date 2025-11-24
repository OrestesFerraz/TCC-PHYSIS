CREATE TABLE jardim (
    id           INT             NOT NULL    AUTO_INCREMENT,
    id_planta   INT             NOT NULL,
    id_usuario   INT             NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id),
    FOREIGN KEY (id_planta) REFERENCES plantas(id)
)