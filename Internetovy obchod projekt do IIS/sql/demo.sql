INSERT INTO Zamestnanec (rodne_cislo, jmeno, prijmeni, adresa, telefon, heslo)
	VALUES (11, 'Jmeno', 'Prijmeni', 'Adresa', 721122568, 'heslo');

INSERT INTO Zakaznik (jmeno, prijmeni, adresa, e_mail, heslo)
	VALUES
		('Jmeno', 'Prijmeni', 'Adresa', 'email', 'heslo'),
		('Jmeno2', 'Prijmeni', 'Adresa', 'email2', 'heslo'),
		('Jmeno3', 'Prijmeni', 'Adresa', 'email3', 'heslo'),
		('Jmeno4', 'Prijmeni', 'Adresa', 'email4', 'heslo');

INSERT INTO Dodavatel(nazev, adresa, telefon)
	VALUES
		('Pastelky.cz', 'Slovinska 85, 841 00, Praha 2', '773 845 987'),
		('Kancelarske potreby, Ostrava s.r.o', 'Bozetechova 25, 481 00, Ostrava', '773 846 123');


INSERT INTO SortimentSkupina (id, nazev, label1, label2, label3, label4, label5)
	VALUES
		(1, 'Pastelky', 'Druh', 'Barva', 'Delka', NULL, NULL),
		(2, 'Skicaky', 'Gramaz', 'Format', 'Pocet Papiru', NULL, NULL);

INSERT INTO SortimentPolozka (id, id_typ, val1, val2, val3, val4, val5)
	VALUES
		(1, 1, 'obycejna', 'cervena', '15', NULL, NULL),
		(2, 1, 'obycejna', 'modra', '15', NULL, NULL),
		(3, 1, 'obycejna', 'zelena', '15', NULL, NULL),
		(4, 1, 'voskovka', 'cervena', '15', NULL, NULL),
		(5, 1, 'voskovka', 'modra', '15', NULL, NULL),
		(6, 2, '80', 'A5', 10, NULL, NULL),
		(7, 2, '80', 'A4', 10, NULL, NULL),
		(8, 2, '80', 'A4', 20, NULL, NULL),
		(9, 2, '80', 'A3', 10, NULL, NULL);

INSERT INTO Sortiment (nazev, id_poloz, cena, skladem, sleva, id_dodav)
	VALUES
		('Obycejna cervena pastelka',	1, 5, 10, 50, 1),
		('Obycejna modra pastelka', 	2, 5, 10, 50, 1),
		('Obycejna zelena pastelka', 	3, 10, 10, 0, 1),
		('Voskova cervena pastelka', 	4, 10, 15, 0, 2),
		('Voskova modra pastelka', 		5, 10, 15, 0, 2),
		('Maly skicak', 				6, 30, 10, 0, 1),
		('Stredni skicak', 				7, 35, 11, 0, 1),
		('Stredni tlusty skicak', 		8, 60, 8, 0, 2),
		('Velky skicak', 				9, 40, 0, 0, 2);

INSERT INTO KosikPolozka (id_zakaz, id_sort, pocet)
	VALUES
		(1, 1, 3),
		(1, 2, 1),
		(1, 3, 1),
		(1, 4, 5),
		(1, 5, 6),
		(1, 6, 10),
		(1, 7, 1),
		(1, 8, 3);

INSERT INTO Kuryr (jmeno, prijmeni, cislo, zpusob)
	VALUES
		('Jiri', 'Novak', '721548960', 'express'),
		('Michal', 'Novotny', '125698743', 'standard');