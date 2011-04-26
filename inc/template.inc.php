<?php
/*
Библиотека для работы с шаблонами
 */




/*
 * Проверяем существует ли шаблон
 */
function __template_check($name){

	if ( is_file($_SERVER['DOCUMENT_ROOT']."/".$name) )
	    return true;
	else
	    return false;
}

/*
 * Обновляем переменные
 */
function __template_update_vars($array_new_vars){
    
    $array_old_vars = array();
    $array_vars = array_merge($array_old_vars, $array_new_vars);    
}


/*
 * Парсим переменные
 */
function __template_parse_vars($template){

    $array_vars = array();

    preg_match_all("/{[^{+^}]*}/", $template, $vars);
//    	echo "Список переменных: <br /><br />";
	    for ($i=0; $i< count($vars[0]); $i++) {
//		 echo "$i:  " . $vars[0][$i] . "<br />";
		 $array_vars[$vars[0][$i]] = array("value"=>"", "type"=>"text");
		 }
return $array_vars;
}


/*
 * Создание файла (функция драйвера)
 */
function __template_create_file($path,$file){
echo $path;
    $f = file_put_contents($path, $file);
        if (!is_file($path))
            return false;        
}


/*
 * Добавление шаблона
 */
function template_add($template,$name){
        if (empty($template))
		return 0;
	if (__template_check($name) == true)
		return 1;
	$array_vars = __template_parse_vars($template);
     
$path = $_SERVER['DOCUMENT_ROOT']."/".$name;


if (__template_create_file($path,$template) == false)
        return false;
    else
        return true;

}


/*
 * Удаление шаблона
 */
function template_delete($name){

	if (__template_check($name) == false || empty($name))
            return false;

        if (unlink($_SERVER['DOCUMENT_ROOT']."/".$name) == false)
            return false;

    return true;        
    
}


/*
 * Обновление шаблона
 */
function template_update($name,$array_new_vars){

	if (!is_file($name) && __template_check($name) == true){  
            __template_update_vars($array_new_vars);
            return true; 
        } else if (is_file($name) && __template_check($name) == true) {
            $array_new_vars = __template_parse_vars($name);  
            __template_update_vars($array_new_vars);
            return true;            
        } else if (is_file($name) && __template_check($name) == false) {
            template_add($name);
            return true;           
        } else {
            return false;            
        }
        
        

}



/*
 * Обрабатываем переменные в шаблоне (прототип)
 */
function template_fetch($name, $params){
    
	if (__template_check($name) == false)
		return 0;    

        $tpl = preg_replace("/{[^{+^}]*}/", $replacement, $template);
        
        return $tpl;
}
?>