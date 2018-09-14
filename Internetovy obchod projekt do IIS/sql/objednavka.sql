CREATE TABLE Objednavka
(
  id 	INTEGER PRIMARY KEY AUTO_INCREMENT,
  id_zakaz  INTEGER   NOT NULL,
  id_zam    INTEGER   DEFAULT NULL,
  id_kuryr  INTEGER   NOT NULL,
  
  cena      INTEGER   NOT NULL,
  adresa    VARCHAR(60) NOT NULL,
  datum		DATETIME    NOT NULL,
  zaplaceno	INTEGER(1)	DEFAULT 0
);