<?php

class OrderedPage extends AbstractPage {


	public function getTitle() {
		return 'Objednano, dekujeme!';
	}
	
	public function getContent() {
		return $this->parseView('vordered', array('number' => $_SESSION['user']['last_order_id']));
	}
};
