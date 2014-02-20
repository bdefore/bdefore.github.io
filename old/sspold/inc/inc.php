<?php

/*

	Archivo principal de librerias y funciones
	
	Para facilitar y reducir el codigo este sera el unico archivo
	a incluir en el resto de los scripts e ira incluyendo el resto
	de los archivos necesarios.

*/

// Definimos el path de los includes
define("INC_PATH", dirname(__FILE__));

// Incluimos el resto de los archivos
// Funciones de XML
require(INC_PATH . "/xml.inc.php");

// Snoopy the PHP net client
require(INC_PATH . "/Snoopy.class.php");

?>