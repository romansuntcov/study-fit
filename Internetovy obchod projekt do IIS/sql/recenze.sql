CREATE TABLE Recenze
(
  id_recenze INTEGER      PRIMARY KEY AUTO_INCREMENT,
  datum    DATE   NOT NULL,
  obsah    VARCHAR(120)    NOT NULL,
  nazev      VARCHAR(30)    NOT NULL,
  id_zakaz   INTEGER    NOT NULL
);