<?php

class Helper {

	public static function getFilesFromFolder($src, $extensions = array('*')) {
		$suff = join(',', $extensions);
		return glob(self::endWithSlash($src) . '*.{'.$suff.'}', GLOB_BRACE);
	}
	
	public static function endWithSlash($str) {
		if (substr($str, -1) != '/')
			return $str.'/';
		return $str;
	}
	
	public static function endWithoutSlash($str) {
		if (substr($str, -1) == '/')
			return substr($str, 0, -1);
		return $str;
	}
	
	public static function setNotEmpty($var) {
		if (isset($var) && !empty($var))
			return true;
		return false;
	}
}