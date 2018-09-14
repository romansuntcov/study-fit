CREATE TABLE Zakaznik
(
  id_zakaz  INTEGER       PRIMARY KEY AUTO_INCREMENT,
  jmeno     VARCHAR(15)     NOT NULL,
  prijmeni  VARCHAR(15)     NOT NULL,
  adresa    VARCHAR(60)     NOT NULL,
  e_mail    VARCHAR(60)     NOT NULL,
  heslo		VARCHAR(20)		NOT NULL
);