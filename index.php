<?php 
	
	session_start();

	
	define('CONTROLLERS', 'app/controllers/');
	define('VIEWS', 'app/views/');
	define('MODELS', 'app/models/');
	define('HELPERS', 'system/helpers/');

	require_once('system/System.php');
	require_once('system/Controller.php');
	require_once('system/Model.php');
	
	function __autoload( $file ){
		if(file_exists(MODELS . $file . '.php'))
			require_once(MODELS . $file . '.php');
		elseif(file_exists(CONTROLLERS . $file . '.php'))
			require_once(CONTROLLERS . $file . '.php');
		else if ( file_exists(HELPERS . $file . '.php')) 
			require_once(HELPERS . $file . '.php');
		else
			die("Model ou Helper não encontrado");
	}

	$start = new System;
	$start->run();