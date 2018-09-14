<?php 

class CartModule extends AbstractModule {
	
	public function getContent() {
		if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id_zakaz']))
			return '';
			
		// Nactu data z databaze
		$data = Database::getInstance()->getCartPreview($_SESSION['user']['id_zakaz']);
		return $this->parseView('vcart', $data);
	}
}