CREATE TABLE Sortiment
(
  id		INT 	     	PRIMARY KEY AUTO_INCREMENT,
  nazev		VARCHAR(30)		NOT NULL,
  id_poloz	INT UNSIGNED	NOT NULL,
  cena      DECIMAL(5, 2)   NOT NULL,
  skladem	INTEGER			NOT NULL DEFAULT 0,
  sleva   	INTEGER     	NOT NULL DEFAULT 0,
  id_dodav  INTEGER    		NOT NULL
);