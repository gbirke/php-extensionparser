<?php
class ExtensionparserAutoload {
	public static  function autoload ($classname) {
	  if (strpos ($classname, '.') !== false || strpos ($classname, '/') !== false
		  || strpos ($classname, '\\') !== false || strpos ($classname, ':') !== false) {
		return;
	  }
	  $classpath = strtr($classname,'_','/');
	  require_once $classpath.'.php';
	}
}

ini_set('include_path', realpath(dirname(__FILE__).'/../src').PATH_SEPARATOR.
	dirname(__FILE__).PATH_SEPARATOR.
	ini_get('include_path'));
spl_autoload_register(array('ExtensionparserAutoload', 'autoload'));