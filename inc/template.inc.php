<?php
/*
Библиотека для работы с шаблонами
 */

if(!defined('TEST_MODE')) define("TEST_MODE",false);
require_once("errors.list.php");
require_once("template.driver.php");


// Обновляем переменные
function __template_update_vars($array_new_vars,$name){ 
    $array_old_vars = driver_load_vars($name);
    $array_vars = array_merge($array_old_vars, $array_new_vars);   
    return $array_vars;
}


// Парсим переменные
function __template_parse_vars($template){
    $array_vars = array();
    preg_match_all("/{(^{)*(^})*([A-Za-z0-9_\-]+)}/", $template, $vars);
        for ($i=0; $i< count($vars[0]); $i++) {
             $array_vars[$vars[3][$i]] = array("value"=>"", "type"=>"text");
             }
                 $error_list = array("null", "yes", "no", "true", "false", "on", "off", "none"); //зарезервированные слова, запрещённые к использованию в качестве ключей
                     if (in_array($error_list,$array_vars) !== false){//проверяем верность ключей
                         error_handler("error_w", "lib_tmp");
                         return false;
                     }
    return $array_vars;
}


// Добавление шаблона
function template_add($template,$name,$create=false){
    if (empty($template)){
        error_handler("empty_tmp", "lib_tmp");
        return false;
    }
        if (empty($name)){
            error_handler("empty_name", "lib_tmp");
            return false;
        }    
            $array_vars = __template_parse_vars($template);
            if (driver_save_template($name, $template, $array_vars, $create) != true){
                    error_handler("error", "lib_tmp");
                    return false;
            }
return true;
}


// Удаление шаблона
function template_delete($name){
    driver_template_delete($name);
    return true;          
}


// Обновление шаблона
function template_update($name,$array_new_vars){
	if (!is_file($name) && driver_template_check($name) == true){ 
            __template_update_vars($array_new_vars, $name);
            if (driver_save_vars($array_new_vars, $name) == false){
                error_handler("error", "lib_tmp");
                return false;
            }
            return true; 
        } else if (is_file($name) && driver_template_check($name) == true) {
            $array_new_vars = __template_parse_vars($name);  
            __template_update_vars($array_new_vars, $name);
            if (driver_save_vars($array_vars, $name) == false){
                error_handler("error", "lib_tmp");
                return false;
            }            
            return true;            
        } else if (is_file($name) && driver_template_check($name) == false) {
            template_add($template,$name,true);
            return true;           
        } else {
            error_handler("error_upd", "lib_tmp");
            return false;            
        }        
}


// Выводим шаблон
function template_fetch($name){
    if(driver_template_check($name) == false){
        error_handler("error", "lib_tmp");
        return false;
    }
    $arr_vars = driver_load_vars($name);
    $tpl = driver_load_template($name);
        foreach($arr_vars as $key=>$val){ 
            $type = $val['type'];
            switch ($type) {
                case "text":
                    $tpl = str_replace("{".$key."}", $val['value'], $tpl);
                    break;
                case "template":
                    $tpl = template_fetch($val['value']);
                    break;
                case "freescript":
                    $r = kernel_run_script($val['value'],$args);
                    $tpl = str_replace("{".$key."}", $r, $tpl);
                    break;                    
                default:
                    "text";
            }
        }              
    return $tpl;
}
?>