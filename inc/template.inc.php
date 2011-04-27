<?php
/*
Библиотека для работы с шаблонами
 */

if(!defined('TEST_MODE')) define("TEST_MODE",false);
require_once("errors.list.php");
require_once("template.driver.php");


/*
 * Обновляем переменные
 */
function __template_update_vars($array_new_vars,$name){
    
    $array_old_vars = driver_load_template_vars($name);
    $array_vars = array_merge($array_old_vars, $array_new_vars);   
    return $array_vars;
}


/*
 * Парсим переменные
 */
function __template_parse_vars($template){

    $array_vars = array();
    preg_match_all("/{[^}]*}/", $template, $vars);
        for ($i=0; $i< count($vars[0]); $i++) {
             $array_vars[$vars[0][$i]] = array("value"=>"", "type"=>"text");
             }
    return $array_vars;
}


/*
 * Добавление шаблона
 */
function template_add($template,$name,$create=false){
    
    if (empty($template) || empty($name)){
        return false;
    }
    
    $array_vars = __template_parse_vars($template);
    if (driver_save_template($name, $template, $array_vars, $create) == false){
        return false;
    }
    
return true;
}


/*
 * Удаление шаблона
 */
function template_delete($name){

    if (driver_template_delete($name) == false){
        return false;
    }
    
    return true;        
    
}


/*
 * Обновление шаблона
 */
function template_update($name,$array_new_vars){
	if (!is_file($name) && __template_check($name) == true){  
            __template_update_vars($array_new_vars, $name);
            if (driver_save_vars($name, $template, $array_vars, $create) == false){
                return false;
            }
            return true; 
        } else if (is_file($name) && __template_check($name) == true) {
            $array_new_vars = __template_parse_vars($name);  
            __template_update_vars($array_new_vars, $name);
            if (driver_save_vars($name, $template, $array_vars, $create) == false){
                return false;
            }            
            return true;            
        } else if (is_file($name) && __template_check($name) == false) {
            template_add($template,$name,true);
            return true;           
        } else {
            return false;            
        }        
}


/*
 * Обрабатываем переменные в шаблоне
 */
function template_fetch($name){ 
    
    $args =''; //аргументы kernel_run_script

    /* В случае если шаблон и переменные будут хранится в одном массиве
    $arr = load_template($name);
    if ($arr == false)
        return false;
    $arr_vars = unserialize($arr['vars']);
    $tpl = $arr['html'];
    */
    
    $arr_vars = driver_load_template_vars($name);
    $tpl = driver_load_template($name);
    
            foreach($arr_vars as $key=>$val_s){ 
//                $val = unserialize($val_s);
                $val = $val_s;
                $type = $val['type'];
                switch ($type) {
                    case "text":
                        $tpl = preg_replace("/{".$key."}/", $val['value'], $tpl);
                        break;
                    case "template":
                        $tpl = template_fetch($val['value']);
                        break;
                    case "freescript":
                        $r = kernel_run_script($val['value'],$args);
                        $tpl = preg_replace("/{".$key."}/", $r, $tpl);
                        break;                    
                    default:
                        "text";
                }
            }    
//        echo var_dump($tpl);
        return $tpl;
        return true;
}
?>