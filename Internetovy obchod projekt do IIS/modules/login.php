<?php

class LoginModule extends AbstractModule {
	
	private $msg = '';
	
	public function getContent() {
		if (isset($_POST['zakaznik'])) {
			if (!$this->checkUser()) {
				$this->msg .= 'Uživatelské jméno nebo heslo je neplatné<br>';
			}
		} else if (isset($_POST['zamestnanec'])) {
			if (!$this->checkEmployee()) {
				$this->msg .= 'ID nebo heslo je neplatné';
			}
		}
	
		if (isset($_SESSION['user'])) {
			if (isset($_SESSION['user']['id_zakaz']) || isset($_SESSION['user']['id_zam']))
				return $this->parseView('vlogged', $_SESSION['user']);
		}
	
		return $this->parseView('vlogin', array('msg' => $this->msg));
	}
	
	private function checkUser() {
		if (!Helper::setNotEmpty($_POST['email']) || !Helper::setNotEmpty($_POST['heslo'])) {
			$this->msg .= 'Email ani heslo nesmí být prazdné<br>';
			return false;
		} else {
			if (($ret = Database::getInstance()->logUser($_POST['email'], $_POST['heslo'])) === true) {
				header('Location: ' . Url::current());
				die();
			} else {
				$this->msg .= $ret;
				return false;
			}
		}
		
		return false;
	}
	
	private function checkEmployee() {
		if (!Helper::setNotEmpty($_POST['email']) || !Helper::setNotEmpty($_POST['heslo'])) {
			$this->msg .= 'ID ani heslo nesmí být prazdné<br>';
			return false;
		} else {
			if (($ret = Database::getInstance()->logEmployee($_POST['email'], $_POST['heslo'])) === true) {
				header('Location: ' . Url::current());
				die();
			} else {
				$this->msg .= $ret;
				return false;
			}
		}
		
		return false;
	}
}