<?php

/**
 * init file includes all required classes
 *
 * @version 1.1
 * @author MPI
 * */

/* load required files */
require "include/lib/System.class.php";
$m = System::findAllFiles("app", array(".", ".."));
$n = System::findAllFiles("include/lib", array(".", "..", "System.class.php"));
$x = array_merge($m, $n);
sort($x);
autoload($x);

/* init session */
session_start();
System::initSession();

/**
 * Load classes in given list.
 *
 * @param array $file_list
 *        	1D with files for include
 *
 */
function autoload($file_list) {
	foreach($file_list as $file) {
		if (file_exists($file)) {
			require __DIR__ ."/". $file;
		}
	}
}
?>
