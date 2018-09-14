<?php

require_once 'abstract_module.php';


abstract class AbstractPage extends AbstractModule {
	
	/**
	 *	@param name string Jmeno sablony (bez .php)
	 *	@param params array Asociovane pole promennych
	 *	@return string Obsah sablony s doplnenymi promennymi
	 */
	protected function parseView($_name, $_params = null) {
		if ($_params != null)
		extract($_params, EXTR_OVERWRITE);
		
		ob_start();
		require 'views/' . $_name . '.php';
		$_result = ob_get_contents();
		ob_end_clean();
		return $_result;
	}
	
	abstract public function getTitle();
}