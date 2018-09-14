<?php

require_once 'helpers/abstract_page.php';

class MainPage extends AbstractPage {
	private $message;

	public function getTitle() {
		return 'Main menu';
	}

	private function setPostOrNull($name) {
		if (isset($_POST[$name]))
			return trim($_POST[$name]);
		return null;
	}

	public function getContent() {
		// Editace zbozi
		if (isset($_SESSION['user']) && isset($_SESSION['user']['id_zam'])) {
			// ZAMESTNANEC
			// Ulozeni kategorie
			if (isset($_POST['save_category'])) {
				try {
					Database::getInstance()->saveCategory($this->setPostOrNull('id'), $this->setPostOrNull('nazev'),
						$this->setPostOrNull('label1'), $this->setPostOrNull('label2'), $this->setPostOrNull('label3'),
						$this->setPostOrNull('label4'), $this->setPostOrNull('label5')
					);
				} catch (Exception $e) {
					$this->message = 'Nelze ulozit kategorii<br>'.$e;
				}
			}

			// Ulozeni polozky
			if (isset($_POST['save_stuff'])) {
				try {
					Database::getInstance()->saveSortimentItem($this->setPostOrNull('id'), $this->setPostOrNull('id_typ'),
						$this->setPostOrNull('val1'), $this->setPostOrNull('val2'), $this->setPostOrNull('val3'),
						$this->setPostOrNull('val4'), $this->setPostOrNull('val5')
					);
				} catch (Exception $e) {
					$this->message = 'Nelze ulozit polozku<br>'.$e;
				}
			}

			// Odstraneni polozky
			if (isset($_POST['remove_stuff'])) {
				try {
					Database::getInstance()->deleteSortimentItem($this->setPostOrNull('id'));
				} catch (Exception $e) {
					$this->message = 'Nelze smazat polozku<br>'.$e;
				}
			}

			// Zobrazeni
			return $this->parseView('vmain_zam', array(
				'groups' => $this->generateGroups(),
				'selection' => $this->generateSelection(),
				'items' => $this->generateStuffs(),
				'message' => $this->message
			));
		}

		// ZAKAZNIK
		// Kontrola pridani zbozi do kosiku
		if (isset($_POST['add'])) {
			if ($this->addToCart()) {
				header('Location: ' . Url::current());
				die();
			}
		}

		// Nakupovani
		return $this->parseView('vmain', array(
			'menu' => $this->generateMenu(),
			'items' => $this->generateItems(),
			'message' => $this->message
		));
	}

	private function generateMenu() {
		try {
			$data = Database::getInstance()->getCategories();
		} catch (Exception $e) {
			return 'Chyba pri ziskavani data <br>'.$e;
		}

		$str = '';
		foreach ($data as &$item) {
			$str .= $this->parseView('vmain_menu_item', array(
				'url' => Url::make(array('p' => 'main', 'kat' => $item['id'])),
				'name' => $item['nazev'],
				'active' => ((isset($_GET['kat']) && $_GET['kat'] == $item['id'])) ? 'active' : ''
			));
		}

		return $this->parseView('vmain_menu', array('items' => $str));
	}

	private function generateItems() {
		if (!isset($_GET['kat']))
			return '';

		try {
			$items = Database::getInstance()->getItems($_GET['kat']);
		} catch (Exception $e) {
			return 'Chyba pri ziskavani data <br>'.$e;
		}

		if (empty($items['data']))
			return 'Nic nenalezeno';

		$str = '';

		// Vygeneruji popisky
		$str .= $this->parseView('vmain_titles', $items['labels']);

		// Vygeneruji polozky
		foreach ($items['data'] as &$item) {
			$str .= $this->generateItem($item);
		}

		return $str;
	}

	private function generateItem($data) {
		$data['exists'] = false;
		return $this->parseView('vmain_item', $data);
	}

	private function addToCart() {
		if (intval($_POST['count']) <= 0) {
			$this->message = 'Lze objednat pouze 1 a vice kusu';
			return false;
		}

		if (intval($_POST['warehouse'] < intval($_POST['count']))) {
			$this->message = 'Omlouvame se, ale tolik polozek neni skladem';
			return false;
		}

		try {
			Database::getInstance()->addToCart($_SESSION['user']['id_zakaz'], intval($_POST['id']),
						intval($_POST['count']));
		} catch (Exception $e) {
			$this->message = 'Chyba v databazi <br> '.$e;
			return false;
		}

		return true;
	}

	private function generateGroups() {
		$groups = Database::getInstance()->getCategories();

		if (empty($groups))
			return 'Neexistuje zatím žádná kategorie zboží';

		$str = '';
		foreach ($groups as &$item) {
			$str .= $this->parseView('vmain_group_item', array(
				'url' => Url::make(array('p' => 'main', 'kat' => $item['id'])),
				'name' => $item['nazev'],
				'active' => ((isset($_GET['kat']) && $_GET['kat'] == $item['id'])) ? 'active' : ''
			));
		}

		// Pridam odkaz na pridani polozky
		$str .= $this->parseView('vmain_group_item', array(
			'url' => Url::make(array('p' => 'main', 'kat' => '0')),
			'name' => '+ Přidat novou',
			'active' => ((isset($_GET['kat']) && $_GET['kat'] == '0')) ? 'active' : ''
		));
		return $str;
	}

	private function generateSelection() {
		if (!isset($_GET['kat']))
			return 'Pro editaci je nutné zvolit kategorii';

		// Prazdný formulár pro pridani nove
		if ($_GET['kat'] == '0') {
			return $this->parseView('vmain_group_edit', array(
				'id' => 0, 'nazev' => '', 'label1' => '', 'label2' => '',
				'label3' => '', 'label4' => '' ,'label5' => '',
			));
		}

		// Editace starych udaju
		$data = Database::getInstance()->getCategory($_GET['kat']);
		if (empty($data))
			return 'Hledaná kategorie neexistuje';

		return $this->parseView('vmain_group_edit', $data);
	}

	private function generateStuffs() {
		if (!isset($_GET['kat']))
			return 'Není zvolená kategorie';

		try {
			$data = Database::getInstance()->getSortimentItems($_GET['kat']);
			$labels = Database::getInstance()->getCategory($_GET['kat']);
		} catch (Exception $e) {
			$this->message = 'Chyba pri ziskavani polozek pro danou kategorii<br>'.$e;
			return '';
		}

		$str = '';
		if (!empty($data)) {
			foreach ($data as &$item) {
				$item['label1'] = $labels['label1'];
				$item['label2'] = $labels['label2'];
				$item['label3'] = $labels['label3'];
				$item['label4'] = $labels['label4'];
				$item['label5'] = $labels['label5'];
				$str .= $this->parseView('vmain_stuff', $item);
			}
		}

		// Vyegeruji jeden prazdny pro pridani
		$item = $labels;
		$item['id'] = 0;
		$item['id_typ'] = $_GET['kat'];
		$item['val1'] = '';
		$item['val2'] = '';
		$item['val3'] = '';
		$item['val4'] = '';
		$item['val5'] = '';
		$str .= $this->parseView('vmain_stuff', $item);
		return $str;
	}
}
















