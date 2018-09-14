<?php

if (file_exists(dirname(__FILE__) . '/../config.php'))
	require_once dirname(__FILE__) . '/../config.php';

class Database {

	/** NASTAVENI PRO UZIVATELE TOHOTO MODULU */
	private $dbHost;
	private $dbName;
	private $dbUser;
	private $dbPass;

	private static $instance = null;
	private function __construct() {
		if (class_exists('Config')) {
			$this->dbHost = Config::DB_HOST;
			$this->dbName = Config::DB_NAME;
			$this->dbUser = Config::DB_USER;
			$this->dbPass = Config::DB_PASS;
		}
	}

	/** Pri destrukci zavrit spojeni */
	public function __destruct() {
		$this->close();
	}

	/** Vrati / vytvori instanci databaze
	 *	@return Database
	 */
	public static function getInstance() {
		if (self::$instance === null)
			self::$instance = new Database();
		return self::$instance;
	}

	private $openned = false;	///< True pokud je spojeni otevreno
	private $connection;		///< PDO objekt pripojeny k databazi

	/** @return PDO objekt, ktery zprostredkovava pripojeni */
	public function getConnection() {
		if ($this->openned)
			return $this->connection;
		throw new Exception('Spojeni nebylo otevreno');
	}

	/** Otevreni spojeni s danymi parametry
	 *	@param dbHost string Host databaze
	 *	@param dbName string Jmeno databaze
	 *	@param dbUser string Uzivatel databaze
	 *	@param dbPassword string Heslo uzivatele databaze
	 */
	public function openWith($dbHost, $dbName, $dbUser, $dbPassword) {
		if ($this->openned)
			throw new Exception("Spojeni s databazi je jiz jednou otevreno!");

		try {
			$this->connection = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8",
									$dbUser, $dbPassword);
			$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			// $this->connection->exec("set names latin2");
			$this->openned = true;

		} catch (PDOException $e) {
			throw new Exception("Nelze se spojit s databazi: " . $e->getMessage());
		}

		return $this->connection;
	}

	public function openWithDefaut() {
		if (!class_exists('Config')) {
			throw new Exception('Nejdrive je nutne modul spravne nainstalovat. Chybi udaje k pristupu do databaze.', 1);
		}
		return $this->openWith($this->dbHost, $this->dbName, $this->dbUser, $this->dbPass);
	}

	/** Uzavreni spojeni, je-li otevreno */
	public function close() {
		if ($this->openned) {
			$this->connection = null;
			$this->openned = false;
		}
	}

	/** FUNKCE pro komunikaci s databazi ----------------------- */
	public function registerUser($email, $pass, $firstname, $lastname, $address) {
		try {
			$statement = $this->getConnection()->prepare('INSERT INTO Zakaznik(jmeno, prijmeni, adresa, e_mail, heslo)
				VALUES (?, ?, ?, ?, ?)');
			$statement->execute(array($firstname, $lastname, $address, $email, $pass));

			$id = $this->getConnection()->lastInsertId();
			$statement = $this->getConnection()->prepare('SELECT * FROM Zakaznik WHERE id_zakaz=? LIMIT 1');
			$statement->execute(array(intval($id)));
			$user = $statement->fetch(PDO::FETCH_ASSOC);

			if (empty($user))
				return 'Data vlozena, ale zadna neprisla zpet';
		} catch (Exception $e) {
			return $e;
		}


		$_SESSION['user'] = array();
		$_SESSION['user'] = $user;
		return true;
	}

	public function logUser($name, $pass) {
		try {
			// Zkusim prihlasit zakaznika
			$statement = $this->getConnection()->prepare('SELECT * FROM Zakaznik WHERE e_mail=? AND heslo=? LIMIT 1');
			$statement->execute(array($name, $pass));
			$user = $statement->fetch(PDO::FETCH_ASSOC);

			if (empty($user)) {
				return false;
			}
		} catch (Exception $e) {
			return $e;
		}

		$_SESSION['user'] = array();
		$_SESSION['user'] = $user;
		return true;
	}

	public function logEmployee($id, $pass) {
		try {
			// Zkusim prihlasit zakaznika
			$statement = $this->getConnection()->prepare('SELECT * FROM Zamestnanec WHERE rodne_cislo=? AND heslo=? LIMIT 1');
			$statement->execute(array($id, $pass));
			$user = $statement->fetch(PDO::FETCH_ASSOC);

			if (empty($user)) {
				return false;
			}
		} catch (Exception $e) {
			return $e;
		}

		$_SESSION['user'] = array();
		$_SESSION['user'] = $user;
		return true;
	}

	public function getItems($kategory) {
		// Zkusim ziskat veci
		$statement = $this->getConnection()->prepare(
			'SELECT S.*, SP.val1, SP.val2, SP.val3, SP.val4, SP.val5
			FROM Sortiment S
			JOIN SortimentPolozka SP ON SP.id=S.id_poloz AND SP.id_typ=?');

		$statement->execute(array($kategory));
		$data = $statement->fetchAll();

		$statement = $this->getConnection()->prepare(
			'SELECT * FROM SortimentSkupina WHERE id=? LIMIT 1');

		$statement->execute(array($kategory));
		$labels = $statement->fetch();

		return array('data' => $data, 'labels' => $labels);
	}

	public function getCategories($full = false) {
		// Zkusim ziskat veci
		if ($full) {
			$str = 'SELECT * FROM SortimentSkupina';
		} else {
			$str = 'SELECT id, nazev FROM SortimentSkupina';
		}

		$statement = $this->getConnection()->prepare($str);
		$statement->execute();
		return $statement->fetchAll();
	}

	public function getCategory($id) {
		// Zkusim ziskat veci
		$statement = $this->getConnection()->prepare('SELECT * FROM SortimentSkupina WHERE id=? LIMIT 1');
		$statement->execute(array($id));
		return $statement->fetch();
	}

	public function saveCategory($id, $name, $v1, $v2, $v3, $v4, $v5) {
		$arr = array(
			'name' => $name,
			'v1' => (empty($v1)) ? null : $v1,
			'v2' => (empty($v2)) ? null : $v2,
			'v3' => (empty($v3)) ? null : $v3,
			'v4' => (empty($v4)) ? null : $v4,
			'v5' => (empty($v5)) ? null : $v5
		);

		if ($id <= 0) {
			$statement = $this->getConnection()->prepare('INSERT INTO SortimentSkupina
				(nazev, label1, label2, label3, label4, label5)
				VALUES (:name, :v1, :v2, :v3, :v4, :v5)');

		} else {
			$statement = $this->getConnection()->prepare('UPDATE SortimentSkupina
				SET nazev=:name, label1=:v1, label2=:v2, label3=:v3, label4=:v4, label5=:v5
				WHERE id=:id LIMIT 1');
			$arr['id'] = $id;
		}

		return $statement->execute($arr);
	}

	public function getSortimentItems($category) {
		$statement = $this->getConnection()->prepare('SELECT * FROM SortimentPolozka WHERE id_typ=?');
		$statement->execute(array($category));
		return $statement->fetchAll();
	}

	public function getSortimentItemsAll() {
		$statement = $this->getConnection()->prepare('SELECT SP.*, SS.nazev FROM SortimentPolozka SP
			JOIN SortimentSkupina SS ON SP.id_typ=SS.id');
		$statement->execute();
		return $statement->fetchAll();
	}

	public function saveSortimentItem($id, $type, $v1, $v2, $v3, $v4, $v5) {
		$arr = array(
			'type' => $type,
			'v1' => (empty($v1)) ? null : $v1,
			'v2' => (empty($v2)) ? null : $v2,
			'v3' => (empty($v3)) ? null : $v3,
			'v4' => (empty($v4)) ? null : $v4,
			'v5' => (empty($v5)) ? null : $v5
		);

		if ($id <= 0) {
			$statement = $this->getConnection()->prepare('INSERT INTO SortimentPolozka
				(id_typ, val1, val2, val3, val4, val5)
				VALUES (:type, :v1, :v2, :v3, :v4, :v5)');

		} else {
			$statement = $this->getConnection()->prepare('UPDATE SortimentPolozka
				SET id_typ=:type, val1=:v1, val2=:v2, val3=:v3, val4=:v4, val5=:v5
				WHERE id=:id LIMIT 1');
			$arr['id'] = $id;
		}

		return $statement->execute($arr);
	}

	public function deleteSortimentItem($id) {
		$statement = $this->getConnection()->prepare('DELETE FROM SortimentPolozka WHERE id=? LIMIT 1');
		$statement->execute(array($id));

		// Musim smazat zavislosti
		$statement = $this->getConnection()->prepare('DELETE FROM Sortiment WHERE id_poloz=?');
		$statement->execute(array($id));
	}

	public function getSortiment() {
		$statement = $this->getConnection()->prepare('SELECT * FROM Sortiment');
		$statement->execute();
		return $statement->fetchAll();
	}

	public function saveSortiment($id, $name, $item, $price, $warehouse, $sale, $delivery) {
		$arr = array(
			'name' => $name,
			'item' => $item,
			'price' => $price,
			'warehouse' => $warehouse,
			'sale' => $sale,
			'delivery' => $delivery
		);

		if ($id <= 0) {
			$statement = $this->getConnection()->prepare('INSERT INTO Sortiment
				(nazev, id_poloz, cena, skladem, sleva, id_dodav)
				VALUES (:name, :item, :price, :warehouse, :sale, :delivery)');

		} else {
			$statement = $this->getConnection()->prepare('UPDATE Sortiment
				SET nazev=:name, id_poloz=:item, cena=:price, skladem=:warehouse, sleva=:sale, id_dodav=:delivery
				WHERE id=:id LIMIT 1');
			$arr['id'] = $id;
		}

		return $statement->execute($arr);
	}

	public function deleteSortiment($id) {
		$statement = $this->getConnection()->prepare('DELETE FROM Sortiment WHERE id=? LIMIT 1');
		$statement->execute(array($id));
	}

	public function getDelivery() {
		$statement = $this->getConnection()->prepare('SELECT * FROM Dodavatel');
		$statement->execute();
		return $statement->fetchAll();
	}

	public function getCartPreview($id) {
		// Zkusim ziskat veci
		$statement = $this->getConnection()->prepare('SELECT cena, pocet
				FROM KosikPolozka K
				JOIN Sortiment S ON K.id_sort=S.id AND K.id_zakaz=?');
		$statement->execute(array($id));
		$data = $statement->fetchAll();

		if (empty($data))
			return array('cena' => 0, 'pocet' => 0);

		$count = $sum = 0;
		foreach ($data as &$item) {
			$count += $item['pocet'];
			$sum += $item['pocet'] * $item['cena'];
		}

		return array('cena' => $sum, 'pocet' => $count);
	}

	public function getCart($id) {
		// Zkusim ziskat veci
		$statement = $this->getConnection()->prepare('SELECT *
				FROM KosikPolozka K
				JOIN Sortiment S ON K.id_sort=S.id AND K.id_zakaz=?');
		$statement->execute(array($id));
		return $data = $statement->fetchAll();
	}

	public function addToCart($user, $item, $count) {
		$statement = $this->getConnection()->prepare('SELECT id_polozka, pocet FROM KosikPolozka
				WHERE id_zakaz=? AND id_sort=?');
		$statement->execute(array($user, $item));
		$result = $statement->fetch(PDO::FETCH_ASSOC);

		if (empty($result)) {
			$statement = $this->getConnection()->prepare('INSERT INTO KosikPolozka (id_zakaz, id_sort, pocet)
				VALUES (?, ?, ?)');
			return $statement->execute(array($user, $item, $count));
		}

		$statement = $this->getConnection()->prepare('UPDATE KosikPolozka SET pocet=? WHERE id_polozka=?');
		return $statement->execute(array($result['pocet'] + $count, $result['id_polozka']));
	}

	public function deleteFromCart($user, $id) {
		$statement = $this->getConnection()->prepare('DELETE FROM KosikPolozka
				WHERE id_zakaz=? AND id_sort=?');
		return $statement->execute(array($user, $id));
	}

	public function getCouriers() {
		// Zkusim ziskat veci
		$statement = $this->getConnection()->prepare('SELECT * FROM Kuryr');
		$statement->execute();
		return $data = $statement->fetchAll();
	}

	public function order($user, $address, $courier) {
		// Presunu do ObjednavkaPolozka (copy, delete)
		// Vytvorim udaj v Objednavka, pridam informace a datum

		// Ziskam data
		$statement = $this->getConnection()->prepare('SELECT id_polozka, id_zakaz, K.id_sort as id_sort, pocet, cena
				FROM KosikPolozka K JOIN Sortiment S ON K.id_sort=S.id AND K.id_zakaz=?');
		$statement->execute(array($user));
		$data = $statement->fetchAll();

		// Vytvorim sumarizujici udaj
		$sum = 0;
		foreach ($data as &$item)
			$sum += $item['cena'] * $item['pocet'];

		$statement = $this->getConnection()->prepare('INSERT INTO Objednavka (id_zakaz, id_kuryr, cena, adresa, datum)
				VALUES (?, ?, ?, ?, NOW())');
		$statement->execute(array($user, $courier, $sum, $address));
		$obj_id = $this->getConnection()->lastInsertId();

		// Presunu a smazu
		foreach ($data as &$item) {
			$add = $this->getConnection()->prepare('INSERT INTO ObjednavkaPolozka (id_obj, id_zakaz, id_sort, pocet)
				VALUES(?, ?, ?, ?)');
			$add->execute(array($obj_id, $item['id_zakaz'], $item['id_sort'], $item['pocet']));

			$rm = $this->getConnection()->prepare('DELETE FROM KosikPolozka WHERE id_polozka=? LIMIT 1');
			$rm->execute(array($item['id_polozka']));
		}

		return $obj_id;
	}

	public function removeOrder($id) {
		$statement = $this->getConnection()->prepare('DELETE FROM Objednavka
				WHERE id=? LIMIT 1');
		$statement->execute(array($id));

		// Odstranim zavislosti
		$statement = $this->getConnection()->prepare('DELETE FROM ObjednavkaPolozka
				WHERE id_obj=?');
		$statement->execute(array($id));
	}

	public function updateCustomer($user, $firstname, $lastname, $address) {
		$statement = $this->getConnection()->prepare('UPDATE Zakaznik SET jmeno=?, prijmeni=?,
						adresa=? WHERE id_zakaz=?');
		if (!$statement->execute(array($firstname, $lastname, $address, $user)))
			return false;

		$_SESSION['user']['jmeno'] = $firstname;
		$_SESSION['user']['prijmeni'] = $lastname;
		$_SESSION['user']['adresa'] = $address;
		return true;
	}

	public function updateEmployee($user, $firstname, $lastname, $address, $phone) {
		$statement = $this->getConnection()->prepare('UPDATE Zamestnanec SET jmeno=?, prijmeni=?,
						adresa=?, telefon=? WHERE id_zam=?');
		if (!$statement->execute(array($firstname, $lastname, $address, $phone, $user)))
			return false;


		$_SESSION['user']['jmeno'] = $firstname;
		$_SESSION['user']['prijmeni'] = $lastname;
		$_SESSION['user']['adresa'] = $address;
		$_SESSION['user']['telefon'] = $phone;
		return true;
	}

	public function getOrders($user, $payed = 0) {
		$statement = $this->getConnection()->prepare('SELECT * FROM Objednavka WHERE id_zakaz=? AND zaplaceno=?');
		$statement->execute(array($user, $payed));
		return $data = $statement->fetchAll();
	}

	public function getAllOrders($payed = 0) {
		$statement = $this->getConnection()->prepare('SELECT * FROM Objednavka WHERE zaplaceno=?');
		$statement->execute(array($payed));
		return $data = $statement->fetchAll();
	}

	public function getOrderDetail($user, $id) {
		if ($user == null) {
			$statement = $this->getConnection()->prepare('
				SELECT O.*, P.*, S.*, Z.jmeno z_jmeno, Z.prijmeni z_prijmeni, K.jmeno k_jmeno, K.prijmeni k_prijmeni, K.cislo k_telefon
					FROM Objednavka O
					JOIN ObjednavkaPolozka P ON P.id_obj=O.id AND O.id=?
					JOIN Sortiment S ON S.id=P.id_sort
					JOIN Zakaznik Z ON Z.id_zakaz=O.id_zakaz
					JOIN Kuryr K ON K.id_kuryr=O.id_kuryr');
			$statement->execute(array($id));
		} else {
			$statement = $this->getConnection()->prepare('
				SELECT O.*, P.*, S.*, Z.jmeno z_jmeno, Z.prijmeni z_prijmeni, K.jmeno k_jmeno, K.prijmeni k_prijmeni, K.cislo k_telefon
					FROM Objednavka O
					JOIN ObjednavkaPolozka P ON P.id_obj=O.id AND O.id_zakaz=? AND O.id=?
					JOIN Sortiment S ON S.id=P.id_sort
					JOIN Zakaznik Z ON Z.id_zakaz=O.id_zakaz
					JOIN Kuryr K ON K.id_kuryr=O.id_kuryr');
			$statement->execute(array($user, $id));
		}

		return $data = $statement->fetchAll();
	}

	public function orderSetPayed($id) {
		$statement = $this->getConnection()->prepare('UPDATE Objednavka SET zaplaceno=1 WHERE id=?');
		return $statement->execute(array($id));
	}
}













































