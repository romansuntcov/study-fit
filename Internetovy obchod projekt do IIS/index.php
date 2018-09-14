<?php

error_reporting(E_ALL); ini_set('display_errors', 1);
header('Content-Type: text/html; charset=utf-8');

// Nastaveni automatickeho vkladani souboru
spl_autoload_register(function ($class) {

	switch ($class) {
		case 'AbstractModule': require_once 'helpers/abstract_module.php'; break;
		case 'AbstractPage': require_once 'helpers/abstract_page.php'; break;
		case 'Database': require_once 'helpers/database.php'; break;
		case 'Helper': require_once 'helpers/helper.php'; break;
		case 'Url': require_once 'helpers/url.php'; break;
		case 'Page': require_once 'page.php'; break;
		case 'Config':
			if (file_exists('config.php'))
				require_once 'config.php';
			break;

		default:
			if (strpos($class, 'Page') !== false) {
				$file = strtolower(str_replace('Page', '', $class)) . '.php';
			} else if (strpos($class, 'Module') !== false) {
				$file = 'modules/'.strtolower(str_replace('Module', '', $class)) . '.php';
			} else {
				$file = strtolower($class).'.php';
			}

			require_once $file;
			break;
	}
});

$controller = null;
session_start();

// Pokud exituje soubor s informacemi, pak jej nactu, jinak spustim instalaci
$page = 'main';
if (isset($_GET['p']))
	$page = $_GET['p'];
if (!file_exists('config.php'))
	$page = 'install';
else
	Database::getInstance()->openWithDefaut();


// Ulozim si do globalni promenne
$_SESSION['page'] = $page;

// Vytvorim ovladac aktualni stranky
$name = ucfirst($page).'Page';
$controller = new $name();

// Vlozim jej do sablony stranky
$page = new Page($controller);
echo $page->getContent();