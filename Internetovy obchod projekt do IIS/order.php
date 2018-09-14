<?php

class OrderPage extends AbstractPage {
	
	public function getTitle() {
		return 'Detail objednavky';
	}
	
	public function getContent() {
		if (!isset($_SESSION['user']))
			return 'Neopravneny pristup';
		if (!isset($_GET['id']))
			return 'Nastala chyba!';
			
		
		if (isset($_SESSION['user']['id_zam']))
			$items = Database::getInstance()->getOrderDetail(null, $_GET['id']);
		else
			$items = Database::getInstance()->getOrderDetail($_SESSION['user']['id_zakaz'], $_GET['id']);
			
		return $this->parseView('vorder', array(
			'data' => $items[0],
			'items' => $this->generateItems($items),
			'sum' => $this->getSum($items),
			'zaplaceno' => ($items[0]['zaplaceno'] == 1) ? true : false
		));
	}
	
	private function generateItems($items) {
		$str = '';
		foreach ($items as &$item) {
			$str .= $this->parseView('vorder_item', $item);
		}
		
		return $str;
	}

	private function getSum($items) {
		$sum = 0;
		foreach ($items as &$item) {
			$sum += $item['cena'] * $item['pocet'];
		}
		return $sum;
	}
}