<?php

class OrdersPage extends AbstractPage {

	public function getTitle() {
		return 'Moje objednavky';
	}

	public function getContent() {
		if (!isset($_SESSION['user']))
			return 'Nemas tu co delat!';

		if (isset($_SESSION['user']['id_zakaz'])) {
			return $this->parseView('vorders', array(
				'not_payed_orders' => $this->generateNotPayedOrders(),
				'payed_orders' => $this->generatePayedOrders()
			));
		}

		// Kontrola pokud zamestnanec potvrdil platbu
		if (isset($_GET['id']) && !isset($_GET['remove'])) {
			$this->pay();
			header('Location: ' . Url::make(array('p' => 'orders')));
			die();
		} else if (isset($_GET['id']) && isset($_GET['remove'])) {
			Database::getInstance()->removeOrder($_GET['id']);
			header('Location: ' . Url::make(array('p' => 'orders')));
			die();
		}

		return $this->parseView('vorders_zam', array(
				'not_payed_orders' => $this->generateEmployeeNotPayedOrders(),
				'payed_orders' => $this->generateEmployeePayedOrders()
		));
	}

	private function pay() {
		Database::getInstance()->orderSetPayed($_GET['id']);
	}

	private function generateNotPayedOrders() {
		$items = Database::getInstance()->getOrders($_SESSION['user']['id_zakaz'], 0);
		$str = '';

		foreach ($items as &$item) {
			$item['url'] = Url::make(array('p' => 'order', 'id' => $item['id']));
			$str .= $this->parseView('vorders_item', $item);
		}

		return $str;
	}

	private function generatePayedOrders() {
		$items = Database::getInstance()->getOrders($_SESSION['user']['id_zakaz'], 1);
		$str = '';

		foreach ($items as &$item) {
			$item['url'] = Url::make(array('p' => 'order', 'id' => $item['id']));
			$str .= $this->parseView('vorders_item', $item);
		}

		return $str;
	}

	private function generateEmployeeNotPayedOrders() {
		$items = Database::getInstance()->getAllOrders(0);
		$str = '';

		foreach ($items as &$item) {
			$item['url'] = Url::make(array('p' => 'order', 'id' => $item['id']));
			$item['urlpayed'] = Url::make(array('p' => 'orders', 'id' => $item['id']));
			$item['urlremove'] = Url::make(array('p' => 'orders', 'id' => $item['id'], 'remove' => 'true'));
			$str .= $this->parseView('vorders_item', $item);
		}

		return $str;
	}

	private function generateEmployeePayedOrders() {
		$items = Database::getInstance()->getAllOrders(1);
		$str = '';

		foreach ($items as &$item) {
			$item['url'] = Url::make(array('p' => 'order', 'id' => $item['id']));
			$str .= $this->parseView('vorders_item', $item);
		}

		return $str;
	}
}