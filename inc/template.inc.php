<?php
/*
Библиотека для работы с шаблонами
 */

$tmp = '<div class="title"><a href="/archiv_news/group:{GROUPID}" class="groupnews">
        <img src="/design/title_news.gif" alt="" width="86" height="20" border="0"></a>
        </div>
	{TR_NEWS}
	<br>
	<div align="right"><a href="/archiv_news/group:{GROUPID}" class="link_all_news">Все {NAME} &raquo;</a>';


template_add($tmp);

/*
 * Проверяем существует ли шаблон
 */
function template_check($name) {

	if ( is_file("./".$_SERVER['PHP_SELF']) )
	    return true;
	else
	    return false;
}


/*
 * Парсим переменные
 */
function template_parse_vars($template) {

    $array_vars = array();

    preg_match_all("/{[^{+^}]*}/", $template, $vars);
//    	echo "Список переменных: <br /><br />";
	    for ($i=0; $i< count($vars[0]); $i++) {
//		 echo "$i:  " . $vars[0][$i] . "<br />";
		 $array_vars[$vars[0][$i]] = "";
		 }
return $array_vars;
}


/*
 * Добавление шаблона
 */
function template_add($template){
	if (template_check($template) == false)
		return false;
	$array_vars = template_parse_vars($template);

	if (empty($array_vars)) {
	/*
	Если в шаблонет переменных нет...
	*/
	    	return false;
	    	}

$name = $_SERVER['DOCUMENT_ROOT']."/tmpname.htm";
$path = "".$name;
$file = fopen($path, 'ab');
if ($file == false)
	return false;

fwrite($file, $template);
fclose($fp);
echo "done";

return true;

}


/*
 * Удаление шаблона
 */
function template_delete($name){

	if (template_check($template) == false)
		return false;


}


/*
 * Обновление шаблона
 */
function template_update($name){

	if (template_check($template) == false)
		return false;

	if (is_file($name)){
		template_add($name);
		return true;
		}



}



/*
 * Ещё какая-то штука с шаблоном
 */
function template_fetch($name, $params){

}
?>