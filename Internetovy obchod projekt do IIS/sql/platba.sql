CREATE TABLE Platba
(
  cislo_uctu  DECIMAL(20, 0)  PRIMARY KEY,
  stav        VARCHAR(20)   NOT NULL,
  nazev_banky VARCHAR(20)   NOT NULL,
  id_zam    INTEGER   NOT NULL,
  id_objednavka INTEGER   NOT NULL
);