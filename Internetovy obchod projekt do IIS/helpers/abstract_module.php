<?php

/**
 *	Abstraktni trida starajici se o jednotne rozhrani pouzitych 'podmodulu' v ramci aplikace
 *	!Kod inspirovany projektem z ITU
 */
abstract class AbstractModule {

	/**
	 *	@param name string Jmeno sablony (bez .php)
	 *	@param params array Asociovane pole promennych
	 *	@return string Obsah sablony s doplnenymi promennymi
	 */
	protected function parseView($_name, $_params = null) {
		if ($_params != null)
		extract($_params, EXTR_OVERWRITE);
		
		ob_start();
		require 'modules/views/' . $_name . '.php';
		$_result = ob_get_contents();
		ob_end_clean();
		return $_result;
	}
	
	/** Funkce prevazne urcena k prepsani, slouzi k vystupu data z modulu */
	abstract public function getContent();
}
