<?php

function __autoload($classname) {
	if (file_exists(dirname(__FILE__) . '/classes/' . $classname . '.php')) {
		include(dirname(__FILE__) . '/classes/' . $classname . '.php');
	} elseif (preg_match('/Test$/', $classname) && file_exists(dirname(__FILE__) . '/tests/' . $classname . '.php')) {
		include(dirname(__FILE__) . '/tests/' . $classname . '.php');
	}
}

if (array_key_exists('action', $_GET)) {
	
}
