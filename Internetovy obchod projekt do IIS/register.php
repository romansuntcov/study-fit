<?php

require_once 'helpers/abstract_page.php';

class RegisterPage extends AbstractPage {

	private $msg = '';

	public function getTitle() {
		return 'Registrace';
	}

	public function getContent() {
		if (isset($_POST['registrovat']) && $this->check()) {
			header('Location: ' . Url::base());
			die();
		} else {
			if (isset($_POST['registrovat']))
				$this->msg .= 'Prosim vyplnte vsechny udaje spravne';
		}
		
		return $this->parseView('vregister', array(
			'email' => ((isset($_POST['email'])) ? $_POST['email'] : ''),
			'jmeno' => ((isset($_POST['jmeno'])) ? $_POST['jmeno'] : ''),
			'prijmeni' => ((isset($_POST['prijmeni'])) ? $_POST['prijmeni'] : ''),
			'adresa' => ((isset($_POST['adresa'])) ? $_POST['adresa'] : ''),
			'msg' => $this->msg
		));
	}
	
	private function check() {
		if (Helper::setNotEmpty($_POST['email']) &&
			Helper::setNotEmpty($_POST['jmeno']) &&
			Helper::setNotEmpty($_POST['prijmeni']) &&
			Helper::setNotEmpty($_POST['adresa']) &&
			Helper::setNotEmpty($_POST['heslo'])) {
		
			$ret = Database::getInstance()->registerUser($_POST['email'], $_POST['heslo'], 
				$_POST['jmeno'], $_POST['prijmeni'], $_POST['adresa']);
			
			if ($ret === true)
				return true;
				
			$this->msg .= $ret;
		}
	
		return false;
	}
}