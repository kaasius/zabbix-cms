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


function __template_create_file($path,$file){

    $f = file_put_contents($path, $file);
        if (!is_file($path)) {
            return false;  
        }
        
    return true;
}
    
} else {    
    require_once('template_mock.inc.php');
}








?>
