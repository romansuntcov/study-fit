<?php

class AccountPage extends AbstractPage {
	private $msg = '';
	
	public function getTitle() {
		return 'Sprava uctu';
	}
	
	public function getContent() {
		if (!isset($_SESSION['user']))
			return 'Nemas opravneni tu byt';
	
		if (isset($_POST['aktualizovat']) && $this->update()) {
			$this->update();
			header('Location: ' . Url::current());
			die();
		}
	
		$arr = $_SESSION['user'];
		$arr['msg'] = $this->msg;
		return $this->parseView('vaccount', $arr);
	}
	
	private function update() {
		if (Helper::setNotEmpty($_POST['jmeno']) &&
			Helper::setNotEmpty($_POST['prijmeni']) &&
			Helper::setNotEmpty($_POST['adresa'])) {
			
			if (isset($_POST['id_zakaz'])) {
				return Database::getInstance()->updateCustomer($_SESSION['user']['id_zakaz'], $_POST['jmeno'],
						$_POST['prijmeni'], $_POST['adresa']);
				
			} else if (isset($_POST['id_zam'])) {
				return Database::getInstance()->updateEmployee($_SESSION['user']['id_zam'], $_POST['jmeno'],
						$_POST['prijmeni'], $_POST['adresa'], $_POST['telefon']);
			}
		}
		
		return false;
	}
}