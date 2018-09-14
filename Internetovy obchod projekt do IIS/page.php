<?php

require_once 'helpers/abstract_page.php';

class Page extends AbstractPage {
	
	private $controller;
	
	public function __construct($controller) {
		$this->controller = $controller;
	}
	
	public function getTitle() {
		return $this->controller->getTitle();
	}
	
	public function getContent() {
		return $this->parseView('vpage', array(
			'title' => $this->getTitle(),
			'header' => $this->getHeader(),
			'menu' => $this->getMenu(),
			'login' => $this->getLogin(),
			'cart' => $this->getCart(),
			'body' =>$this->controller->getContent(),
			'footer' => $this->getFooter()
		));
	}
	
	private function getHeader() {
		if ($_SESSION['page'] == 'install')
			return $this->parseView('vpage_install_header');	
		return $this->parseView('vpage_header');
	}
	
	private function getMenu() {
		if ($_SESSION['page'] == 'install')
			return '';
			
		$obj = new MenuModule();
		return $obj->getContent();
	}
	
	private function getLogin() {
		if ($_SESSION['page'] == 'install')
			return '';
			
		$obj = new LoginModule();
		return $obj->getContent();
	}
	
	private function getCart() {
		if ($_SESSION['page'] == 'install' || $_SESSION['page'] == 'cart')
			return '';
			
		$obj = new CartModule();
		return $obj->getContent();
	}
	
	private function getFooter() {
		return $this->parseView('vpage_footer');
	}
}