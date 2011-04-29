<?php
/***********************************************************************************************************
 * Драйвер для работы библиотеки шаблонов, записывающий шаблон и переменные из него (в ini файл) на диск.  *
 * Так же считывает, удаляет, обновляет шаблон и переменные.                                               *         
 ***********************************************************************************************************/


require_once($_SERVER["DOCUMENT_ROOT"]."/lang/ru/lang_inc.php");
define("PATH",$_SERVER['DOCUMENT_ROOT']."/tpl/");


// запись шаблона на диск
function driver_save_template($name, $file, $array_vars, $create){
    $path = PATH.$name;    
    if (is_file($path) != true || is_file($path) == true && $create == true){ //если файл шаблона не существует или разрешена перезапись
        file_put_contents($path, $file); 
        if (!is_file($path)) {
            error_handler("tmp_file", "driver_tmp");
            return false;  
        }
        driver_save_vars($array_vars,$name); // сохраняем переменные
    } else if (is_file($path) == true && $create != false){ //если файл существует и перезапись не разрешена (по умолчанию)
        error_handler("save_template", "driver_tmp");
        return false; 
    }
    return true;
} 

//запись переменных в ini файл
function driver_save_vars($array_vars,$name_tmp_vars){ 
    $path = PATH.$name_tmp_vars; 
    $vars_ini = ''; // обнуляем переменную на всякий случай  
    foreach ($array_vars as $key=>$val) { //формируем нужный формат строки
        $vars_ini .= "[$key]\n";
        $vars_ini .= "value = \"".$val['value']."\"\n";
        $vars_ini .= "type = \"".$val['type']."\"\n\n";
    }
    if (file_put_contents($path.".ini", $vars_ini) == "0"){
        error_handler("ini_file", "driver_tmp");
        return false;
    }
return true;     
}  

//загружаем шаблон
function driver_load_template($name){
    $tpl = file_get_contents(PATH.$name);
    return $tpl;            
}  

//загружаем переменные шаблона
function driver_load_vars($name){
    $vars = parse_ini_file(PATH.$name.".ini", true);
    return $vars;
}
    
    
//проверяем существует ли шаблон    
function driver_template_check($name){
    if ( is_file(PATH.$name) ){
        return true;
    } else {
        error_handler("tmp_not_exist", "driver_tmp");
        return false;
    }
}

//удаляем шаблон и переменные
function driver_template_delete($name){
    if (@unlink(PATH.$name) == false || @unlink(PATH.$name.".ini") == false){
        error_handler("del", "driver_tmp");
        return true;
    }
return true;        
}

?>
