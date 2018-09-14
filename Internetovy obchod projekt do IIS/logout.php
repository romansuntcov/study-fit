<?php

require_once 'helpers/abstract_page.php';

/**
 * Odhlasi a hodi na hlavni stranku
 */
class LogoutPage extends AbstractPage {

	public function getTitle() {
		return 'Logout';
	}

	public function getContent() {
		unset ($_SESSION['user']);
		header('Location: ' . Url::base());
	}
}