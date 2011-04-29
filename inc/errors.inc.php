<?
require_once("errors.list.php");
require_once("lang_driver.php");
if (driver_load_lang("lang_error.php", "ru") == false){
    print "<strong>Error load lang file</strong><br />";
    exit;
}
    


if(!defined('ERROR_LIST_FILE')) define ('ERROR_LIST_FILE',BASE_PATH.PATH_DELIMITER.PATH_DEFINES.PATH_DELIMITER.'error.list.txt');
if(!defined('DEBUG_MODE') ) define("DEBUG_MODE",false);

global $__LAST_ERROR;

/**
 * Функция обрабатывает возникающие в ходе работы программы ошибки.
 * В случае, если разрешен отладочный вывод - она выводит их на экран.
 * Если вывод запрещен - ничего не делает
 * TODO: сделать логгирование в файл
 * @global int $__LAST_ERROR последняя возникшая ошибка
 * @param string $message выводимое сообщение
 * @param string $module_name необязательный параметр, указывающий модуль, в котором произошла ошибка
 */
function error_handler($message, $module_name=""){
	global $__LAST_ERROR;
	
	if( !is_int( $message ) ){
		if( DEBUG_MODE )
			print "<strong>{$lang_error['error_w']}</strong> $module_name. $lang_error[$message]<Br>";
	}
	else{
		$__LAST_ERROR = $message;
		if( DEBUG_MODE )
			print "<strong>Module:</strong> $module_name. <strong>Error:</strong> ".$GLOBALS['ERRORS_LIST'][$message]."<Br>";
		
	}
	if(DEBUG_MODE){
		echo "<pre>";
		debug_print_backtrace();
		echo "</pre>";
    }
}

function errors_get_last(){
	global $__LAST_ERROR;
	
	return $__LAST_ERROR;
}

//TODO: Сделать редирект на страницу 404 и возврат соответствующего заголовка.
/**
 * Функция генерирует ошибку 404 (страница не найдена)
 */
//function error_404()
//{
//	error_handler ("Ошибка 404 - нет такой страницы: $_SERVER[REQUEST_URI]");
//	exit(0);
//}

function parse_error_list($file='')
{
    if($file=='') $string=file_get_contents(ERROR_LIST_FILE);
    else $string=file_get_contents(ERROR_LIST_FILE);
    $errors=explode(';',$string);
    if(is_array($errors))
        foreach($errors as $error)
        {
            $struct=explode(' = ',$error);
            if(!defined($struct[0]))
            {
                define($struct[0],$struct[1]);
                $GLOBALS['ERRORS_LIST'][$struct[1]]=$struct[2];
            }
        }
}
?>