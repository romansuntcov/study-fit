CREATE TABLE Zamestnanec
(
  id_zam    INTEGER       PRIMARY KEY AUTO_INCREMENT,
  rodne_cislo DECIMAL(10, 0)     UNIQUE NOT NULL CHECK(MOD(rodne_cislo, 11) = 0),
  vedouci	INTEGER(1)		DEFAULT 0,
  jmeno     VARCHAR(15)     NOT NULL,
  prijmeni  VARCHAR(15)     NOT NULL,
  adresa    VARCHAR(30)     NOT NULL,
  telefon   DECIMAL(9, 0)  	NOT NULL,
  heslo		VARCHAR(20)		NOT NULL
);