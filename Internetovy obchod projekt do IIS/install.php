<?php

require_once 'helpers/abstract_page.php';
require_once 'helpers/database.php';
require_once 'helpers/url.php';


/**
 *	Program pro instalaci prostredi
 *	Kod inspirovany z projektu ITU
 */
class InstallPage extends AbstractPage {

	// Chybova zprava
	private $msg = '';

	// Nastaveni
	private $dbHost;
	private $dbName;
	private $dbUser;
	private $dbPass;

	// Soubory k instalaci
	private $files = array(
		'dodavatel', 'kosik_polozka', 'kuryr', 'objednavka', 'objednavka_polozka', 'platba', 'recenze',
		'sortiment_polozka', 'sortiment_skupina', 'sortiment', 'zakaznik', 'zamestnanec', 'demo'
	);

	public function getTitle() {
		return 'Instalace';
	}

	public function createConfigFile() {
		$file = fopen('config.php', 'w');
		fwrite($file, "<?php\n\nclass Config {\n");
		fwrite($file, "\tconst DB_HOST='{$this->dbHost}';\n");
		fwrite($file, "\tconst DB_NAME='{$this->dbName}';\n");
		fwrite($file, "\tconst DB_USER='{$this->dbUser}';\n");
		fwrite($file, "\tconst DB_PASS='{$this->dbPass}';\n");
		fwrite($file, "}");
	}

	public function installTables($host, $name, $user, $pass) {
		try {
			// Ulozim pro pozdejsi pouziti
			$this->dbHost = $host;
			$this->dbName = $name;
			$this->dbUser = $user;
			$this->dbPass = $pass;

			// TODO Otevrit transakci at vse probehne atomicky
			$db = Database::getInstance()->openWith($host, $name, $user, $pass);

			foreach ($this->files as &$file) {
				$sql = file_get_contents('sql/'. $file . '.sql');
				$qr = $db->exec($sql);
			}

		} catch (Exception $e) {
			$this->msg .= '<h3>ERROR</h3><pre>'. $e . '</pre>';
			return false;
		}

		return true;
	}

	public function getContent() {
		// Overim jestli jsou v post informace
		if ($this->checkForData()) {
			$this->createConfigFile();
			header('Location: ' . Url::base());
		}

		return $this->parseView('vinstall', array(
			'msg' => $this->msg,
			'host' => (isset($_POST['host'])) ? $_POST['host'] : '',
			'name' => (isset($_POST['name'])) ? $_POST['name'] : '',
			'user' => (isset($_POST['user'])) ? $_POST['user'] : ''
		));
	}

	private function checkForData() {
		if (isset($_POST['btnSubmit'])) {
			$ok = true;
			if (empty($_POST['host'])) {
				$this->msg .= '<div>Host nesmi byt prazdny</div>';
				$ok = false;
			}

			if (empty($_POST['name'])) {
				$this->msg .= '<div>Name nesmi byt prazdne</div>';
				$ok = false;
			}

			if (empty($_POST['user'])) {
				$this->msg .= '<div>User name nesmi byt prazdne</div>';
				$ok = false;
			}

			if (!$ok)
				return false;

			// Instalace
			return $this->installTables($_POST['host'], $_POST['name'], $_POST['user'], $_POST['pass']);
		}

		return false;
	}
}