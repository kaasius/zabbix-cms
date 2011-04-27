<?php

/*
 * Драйвер для работы шаблонов
 */

define("PATH",$_SERVER['DOCUMENT_ROOT']."/tpl/");

//if(true!==TEST_MODE){
    

function driver_save_template($name, $file, $array_vars, $create){
    
    if (is_file(PATH.$name) != true || is_file(PATH.$name) == true && $create == true){
        $path = PATH.$name;
        file_put_contents($path, $file);
        if (!is_file($path)) {
            return false;  
        }
        driver_save_vars($array_vars,$path);
    } else if (is_file(PATH.$name) == true && $create != true){
        return false; 
    }
    return true;
} 

function driver_save_vars($array_vars,$path){ // только для теста
        $vars = serialize($array_vars); 
        file_put_contents(PATH.".var", $vars);    
}  

function driver_load_template($name){
    $tpl = file_get_contents(PATH.$name);
    return $tpl;        
    
}  

function driver_load_template_vars($name){
    $vars = unserialize(file_get_contents(PATH.$name.".var"));
    return $vars;
}
    
    
    
function __template_check($name){

	if ( is_file(PATH.$name) )
	    return true;
	else
	    return false;
}

function driver_template_delete($name){

	if (__template_check($name) == false)
            return false;

        if (unlink(PATH.$name) == false)
            return false;
        unlink(PATH.$name.".var");
    return true;        
    
}


function __template_create_file($path,$file){

    $f = file_put_contents($path, $file);
        if (!is_file($path)) {
            return false;  
        }
        
    return true;
}
    
//} else {   
//    require_once('template_mock.inc.php');
//}








?>
