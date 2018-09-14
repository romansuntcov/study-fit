<?php

// Pomocnik pro vytvareni URL odkazu z poli
class Url {

	private $elements;			// scheme, host, path, query
	private $query = array();	// Zpracovane query (?a=b&c=d --> pole)
	private $base;				// Zakladni adresa
	private $file;				// Aktivni php soubor (index.php)

	private static $instance;
	private function __construct() {
		// Ziskam elementy
		$this->elements = parse_url(self::current());
		
		// Ziskam pole GET
		if (isset($this->elements['query']))
			$this->query = parse_str($this->elements['query'], $this->query);
			
		// Ziskam nazev souboru a zakladni cestu
		if (substr($this->elements['path'], -1) == '/') {
			$this->file = '';
			$this->base = $this->elements['scheme'].'://'.$this->elements['host'].$this->elements['path'];
		} else {
			$pos = strrpos($this->elements['path'], '/');
			$this->file = substr($this->elements['path'], $pos + 1);
			$this->base = $this->elements['scheme'].'://'.$this->elements['host'].
							substr($this->elements['path'], 0, $pos + 1);
		}
	}
	
	private static function getInstance() {
		if (self::$instance == null)
			self::$instance = new self();
		return self::$instance;
	}

	public static function base() {
		return self::getInstance()->base;
	}

	public static function current() {
		return "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	}
	
	// Vytvori adresu nezavisle na predchozich parametrech GET
	public static function make($arr, $file = null) {
		$me = self::getInstance();
		return self::base() . $me->file . '?' . http_build_query($arr);
	}
	
	// Prida parametry do aktualnich a vrati adresu
	public static function add($arr) {
		$me = self::getInstance();
		return self::base() . $me->file . '?' . http_build_query(array_merge($_GET, $arr));
	}
}