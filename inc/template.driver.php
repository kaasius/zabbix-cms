<?php

/*
 * Драйвер для работы шаблонов
 */

define("PATH",$_SERVER['DOCUMENT_ROOT']."/tpl/");


function driver_save_template($name, $file, $array_vars, $create){
    $path = PATH.$name;    
    if (is_file($path) != true || is_file($path) == true && $create == true){
        file_put_contents($path, $file);
        if (!is_file($path)) {
            die("1");
            return false;  
        }
        driver_save_vars($array_vars,$name);
    } else if (is_file($path) == true && $create != false){
        die("2");
        return false; 
    }
    return true;
} 

function driver_save_vars($array_vars,$name_tmp_vars){ 
    $path = PATH.$name_tmp_vars; 
    $vars_ini = ''; // обнуляем переменную на всякий случай  
    foreach ($array_vars as $key=>$val) {
        $vars_ini .= "[$key]\n";
        $vars_ini .= "value = ".$val['value']."\n";
        $vars_ini .= "type = ".$val['type']."\n\n";
    }
    $t = file_put_contents($path.".ini", $vars_ini);
    return true; 
    /*доделать эту часть*/
}  

function driver_load_template($name){
    $tpl = file_get_contents(PATH.$name);
    return $tpl;        
    
}  

function driver_load_vars($name){
    $vars = parse_ini_file(PATH.$name.".ini", true);
    return $vars;
}
    
    
    
function driver_template_check($name){
    if ( is_file(PATH.$name) )
        return true;
    else
        return false;
}

function driver_template_delete($name){
    if (unlink(PATH.$name) == false || unlink(PATH.$name.".ini") == false){
        return false;
    }
return true;        
}

?>
