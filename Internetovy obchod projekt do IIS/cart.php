<?php

class CartPage extends AbstractPage {
	private $msg = '';

	public function getTitle() {
		return 'Kosik';
	}
	
	public function getContent() {
		if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id_zakaz']))
			return 'Neopravneny vstup';
			
		// Kontrola k vymazani
		if ($this->checkForDelete()) {
			header('Location: ' . Url::make(array('p' => 'cart')));
			die();
		}
		
		$items = Database::getInstance()->getCart($_SESSION['user']['id_zakaz']);
		
		if (!empty($items) && $this->checkForSend()) {
			header('Location: ' . Url::make(array('p' => 'ordered')));
			die();
		}
	
		$couriers = Database::getInstance()->getCouriers();
		
		return $this->parseView('vcart', array(
			'items' => $this->generateItems($items),
			'count' => $this->getCount($items),
			'sum' => $this->getSum($items),
			'couriers' => $this->generateCouriers($couriers)
		));
	}
	
	private function checkForDelete() {
		if (!isset($_GET['delete']))
			return false;
		
		Database::getInstance()->deleteFromCart($_SESSION['user']['id_zakaz'], $_GET['delete']);	
		return true;
	}
	
	private function checkForSend() {
		if (!isset($_POST['objednat']))
			return false;
			
		if (!Helper::setNotEmpty($_POST['ulice']) || !Helper::setNotEmpty($_POST['mesto']) || 
			!Helper::setNotEmpty($_POST['psc'])) {
		
			$this->msg .= 'Nejsou vyplneny vsechny dorucovaci udaje';
			return false;
		}
		
		$address = $_POST['ulice'].', '.$_POST['mesto'].' '.$_POST['psc'];
		$_SESSION['user']['last_order_id'] = Database::getInstance()->order($_SESSION['user']['id_zakaz'], $address, $_POST['kuryr']);
		return true;
	}
	
	private function generateItems($items) {
		$str = '';
		foreach ($items as &$item) {
			$item['deleteUrl'] = Url::make(array('p' => 'cart', 'delete' => $item['id_sort']));
			$str .= $this->parseView('vcart_item', $item);
		}
		
		return $str;
	}
	
	private function getCount($items) {
		$i = 0;
		foreach ($items as &$item) {
			$i += $item['pocet'];
		}
		return $i;
	}
	
	private function getSum($items) {
		$i = 0;
		foreach ($items as &$item) {
			$i += $item['pocet'] * $item['cena'];
		}
		return $i;
	}
	
	private function generateCouriers($couriers) {
		$str = '';
		$first = true;
		
		foreach ($couriers as &$man) {
			if ($first) {
				$selected = 'checked="checked"';
				$first = false;
			} else {
				$selected = '';
			}
			
			$str .= '<label><input type="radio" name="kuryr" value="'. $man['id_kuryr'].'" '.$selected.'> '.
					$man['jmeno'].' '.$man['prijmeni'].' ('.$man['zpusob'].')</label><br>';
		}
		
		return $str;
	}
}