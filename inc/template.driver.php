<?php

/*
 * Драйвер для работы шаблонов
 */

if(true!==TEST_MODE){
    
function __template_check($name){

	if ( is_file($_SERVER['DOCUMENT_ROOT']."/".$name) )
	    return true;
	else
	    return false;
}
    
} else {    
    require_once('template_mock.inc.php');
}








?>
