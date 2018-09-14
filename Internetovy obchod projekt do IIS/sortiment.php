<?php

require_once 'helpers/abstract_page.php';

class SortimentPage extends AbstractPage {
	private $message;

	public function getTitle() {
		return 'Sortiment';
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
			if (isset($_POST['save_sortiment'])) {
				// Kontrola
				if ($this->setPostOrNull('cena') < 0) {
					$this->message = 'Cena musí být nezáporná';

				} elseif ($this->setPostOrNull('skladem') < 0) {
					$this->message = 'Hodnota skladem musí být nezáporná';

				} elseif ($this->setPostOrNull('sleva') < 0 || $this->setPostOrNull('sleva') > 100) {
					$this->message = 'Sleva musí být v rozmezí 0 až 100';

				} else {
					try {
						Database::getInstance()->saveSortiment($this->setPostOrNull('id'), $this->setPostOrNull('nazev'),
							$this->setPostOrNull('id_poloz'), $this->setPostOrNull('cena'), $this->setPostOrNull('skladem'),
							$this->setPostOrNull('sleva'), $this->setPostOrNull('id_dodav')
						);
					} catch (Exception $e) {
						$this->message = 'Nelze ulozit sortiment<br>'.$e;
					}
				}
			}

			// Odstraneni polozky
			if (isset($_POST['remove_sortiment'])) {
				try {
					Database::getInstance()->deleteSortiment($this->setPostOrNull('id'));
				} catch (Exception $e) {
					$this->message = 'Nelze smazat polozku<br>'.$e;
				}
			}

			// Zobrazeni
			return $this->parseView('vsortiment', array(
				'items' => $this->generateItems(),
				'message' => $this->message
			));
		}

		return 'Neoprávněný přístup';
	}

	private $stuffs;
	private function generateStuffs($active) {
		if (!isset($this->stuffs))
			$this->stuffs = Database::getInstance()->getSortimentItemsAll();

		$str = '<select name="id_poloz">';
		foreach($this->stuffs as $item) {
			$selected = ($active == $item['id']) ? 'selected' : '';
			$str .= '<option '.$selected.' value="'.$item['id'].'">['.$item['id'].'] '.$item['nazev'].
					' '.$item['val1'].' '.$item['val2'].' '.$item['val3'].' '.$item['val4'].
					' '.$item['val5'].'</option>';
		}

		return $str.'</select>';
	}

	private $delivery;
	private function generateDelivery($active) {
		if (!isset($this->delivery))
			$this->delivery = Database::getInstance()->getDelivery();

		$str = '<select name="id_dodav">';
		foreach($this->delivery as $item) {
			$selected = ($active == $item['id_dodav']) ? 'selected' : '';
			$str .= '<option '.$selected.' value="'.$item['id_dodav'].'">['.$item['id_dodav'].'] '.$item['nazev'].
					' '.$item['adresa'].' '.$item['telefon'].'</option>';
		}

		return $str.'</select>';
	}

	private function generateItems() {
		$data = Database::getInstance()->getSortiment();

		$str = '';
		$arr = array();

		if (!empty($data)) {
			foreach ($data as &$item) {
				$arr['stuff'] = $this->generateStuffs($item['id_poloz']);
				$arr['delivery'] = $this->generateDelivery($item['id_dodav']);
				$arr['item'] = $item;
				$str .= $this->parseView('vsortiment_item', $arr);
			}
		}

		// Jednu prázdnou
		$str .= $this->parseView('vsortiment_item', array(
			'stuff' => $this->generateStuffs(null),
			'delivery' => $this->generateDelivery(null),
			'item' => array(
				'id' => 0,
				'nazev' => '',
				'cena' => '',
				'skladem' => '',
				'sleva' => ''
			)
		));

		return $str;
	}
}


















