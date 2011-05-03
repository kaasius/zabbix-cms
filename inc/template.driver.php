<?php
/*
 * Драйвер для работы библиотеки шаблонов                                     
 */


require_once($_SERVER["DOCUMENT_ROOT"]."/lang/ru/lang_inc.php");
define("PATH",$_SERVER['DOCUMENT_ROOT']."/tpl/");


/** запись шаблона на диск
 * @param string $name имя шаблона
 * @param file $file файл шаблона
 * @param array $array_vars переменные шаблона
 * @param bool $create разрешать перезапись или нет
 * @return bool 
 */
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

/** запись переменных в ini файл
 *
 * @param array $array_vars массив переменных
 * @param string $name_tmp_vars имя файла
 * @return bool 
 */
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

/** загружаем шаблон
 *
 * @param string $name имя шаблона
 * @return string 
 */
function driver_load_template($name){
    $tpl = file_get_contents(PATH.$name);
    return $tpl;            
}  

/** загружаем переменные шаблона
 *
 * @param string $name имя шаблона
 * @return array
 */
function driver_load_vars($name){
    $vars = parse_ini_file(PATH.$name.".ini", true);
    return $vars;
}
    
/** удаляем шаблон и переменные
 *
 * @param string $name имя шаблона
 * @return bool
 */
function driver_template_delete($name){
    if (@unlink(PATH.$name) == false || @unlink(PATH.$name.".ini") == false){
        error_handler("del", "driver_tmp");
        return true;
    }
return true;        
}

?>
