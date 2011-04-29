<?php
/*****************************************************************************************
 * Библиотека для работы с шаблонами, состоящая из четырёх основных функций:             *
 *                                                                                       *
 * template_add - добавление шаблона. Параметры: $template (шаблон), $name (название),   * 
 * $create (при true - разрешить перезапись, если шаблон существует)                     *
 *                                                                                       *
 * template_update - обновление шаблона. Параметры: $name (название)                     *
 * и $array_upd (массив: ключ action - одно из 3-ёх возможных видов обновления:          * 
 * var, template, all - обновление одной переменной, шаблона и всего соответственно;     * 
 * ключ template - шаблон; ключ vars - переменные)                                       *
 *                                                                                       *
 * template_fetch - вывод шаблона на сайт, параметр один- $name (название)               *
 *                                                                                       *        
 * template_delete - удаление шаблона, параметр один- $name (название)                   *
 *****************************************************************************************/


if(!defined('TEST_MODE')) define("TEST_MODE",false);
require_once("errors.list.php");
require_once("template.driver.php");


// Обновляем одну переменную при редактировании шаблона
function __template_update_var($array_new_vars,$name){ 
    $array_old_vars = driver_load_vars($name);
    $array_vars = array_merge($array_old_vars, $array_new_vars);   
    return $array_vars;
}


// Обновляем переменные при редактировании шаблона
function __template_update_vars($array_vars,$name){
    $array_new = __template_parse_vars($array_vars);
    $array_prev_vars = driver_load_vars($name);
    $array_new_vars = array_intersect_key($array_new, $array_prev_vars);
    return $array_new_vars;
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
function template_update($name,$array_upd){
    if (empty($name)){
        error_handler("empty_name", "lib_tmp");
        return false;
    }
    if (empty($array_upd)){
        error_handler("error", "lib_tmp");
        return false;
    }
    $action = $array_upd["action"];
    switch($action) {
        case "var":
            __template_update_var($array_upd["vars"],$name);
            if (driver_save_vars($array_new_vars, $name) == false){
                error_handler("error", "lib_tmp");
                return false;
            }
            return true;  
            break;
        case "template":
            $array_new_vars = __template_update_vars($array_upd["template"],$name);
             if (driver_save_vars($array_new_vars, $name) == false){
                error_handler("error", "lib_tmp");
                return false;
            }
            return true;           
            break;
        case "all":
            $array_new = __template_parse_vars($array_upd["template"]);
            $array_new_vars = array_intersect_key($array_upd["vars"], $array_new);
             if (driver_save_vars($array_new_vars, $name) == false){
                error_handler("error", "lib_tmp");
                return false;
            }
            return true;              
            break;
        default:
            "var";
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