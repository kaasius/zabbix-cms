<?
//TODO Сделать плэйсхолдер "список имен"

if(true!==TEST_MODE){
require_once ("errors.inc.php");
//require_once ("common.inc.php");
global $_CHANEL,$_PREFIX,$_DBSTACK;

/***************************************************************
Процедура инициализации базы данных
***************************************************************/
function db_init_db()
{
    $schema=_db_test_zone();   // тестируем зону, в которой находится страница, для определения схемы БД
    db_select_db($schema);	   // выбираем БД
}

function _db_test_zone()
{
        global $zone;
        for($i=0;$i<count($zone);$i++)
    {
        list($key,$val)=each($zone);
        if(preg_match("/$val/",$_SERVER[REQUEST_URI])) return($key);
    }
    return("main");
}


/***************************************************************
Процедура соединения с сервером БД
***************************************************************/
function _connect_db($dbparams)
{
    if(!$chanel=mysql_connect($dbparams['hostname'],$dbparams['username'],$dbparams['password']))
    {
       error_handler(SERVER_NOT_REACHED,__FILE__.':'.__LINE__);
       exit;
    }
    return($chanel);
}

/***************************************************************
Процедура выбора сервера и БД для текущих запросов.
***************************************************************/
function db_select_db($schema)
{
	global $_CHANEL,$_PREFIX, $dbparams;
	static $chanels, $_schema;
	
	if($_schema === $schema) return;
	$_schema=$schema;
	if(!$chanels[$schema]['chanel'])  // проверяем, определена ли уже эта схема
    {                                       //Если нет - сначала проверяем, нет ли уже подключения
		for($i=0;$i<count($chanels);$i++)	//к данному серверу с тем же именем пользователя
        {                                   //если есть - то и канал уже есть - просто копируем его
        	list($key,$val)=each($chanels);
            if(($dbparams[$schema]['hostname']==$val['hostname'])&&($dbparams[$shema]['username']==$val['username']))
            {
            	$chanels[$schema]['hostname']=$val['hostname'];
                $chanels[$schema]['username']=$val['username'];
                $chanels[$schema]['chanel']=$val['chanel'];
                break;
            }
        }
    }
	if(!$chanels[$schema]['chanel'])	// снова проверяем, определена ли уже эта схема
    {                               // если нет - надо подключаться
		if(!$chanels[$schema]['chanel']=_connect_db($dbparams[$schema])) die("Критическая ошибка при выборе базы данных");
        $chanels[$schema]['hostname']=$dbparams[$schema]['hostname'];
       	$chanels[$schema]['username']=$dbparams[$schema]['username'];
    }
    $_CHANEL=$chanels[$schema]['chanel'];    // Задаем подключение, префикс
    $_PREFIX=$dbparams[$schema]['prefix'];   // и выбираем базу данных на сервере
    if(!mysql_select_db($dbparams[$schema]['dbname'],$_CHANEL))error_handler("dberror","Ошибка выбора базы ".$dbparams[$shema]['dbname'].": ". mysql_error());
}

/***************************************************************
Процедура выборки из базы данных
Принимает в качестве параметров запрос и переменные, замещающие плэйсхолдеры
Возвращает двумерный массив - таблицу с результатом.
***************************************************************/
function db_select()
{
	global $_CHANEL;
	if(!@mysql_ping($_CHANEL)) db_init_db();
	$args=func_get_args();
    $q=new fetcher($args);
    $query=$q->fetch_query();
    if(!$result=mysql_query($query,$_CHANEL)) error_handler("dberror",mysql_error()."\n".$query);
    while($s=@mysql_fetch_assoc($result))
    	$ret[]=$s;
    return($ret);
}

/***************************************************************
Процедура выборки из базы данных
Принимает в качестве параметров запрос и переменные, замещающие плэйсхолдеры
Возвращает хеш с указанным полем в роли ключа
***************************************************************/
function db_select_hash()
{
    global $_CHANEL;
    if(!@mysql_ping($_CHANEL)) db_init_db();
    $args=func_get_args();
    $field=array_shift($args);
    $q=new fetcher($args);
    $query=$q->fetch_query();
    if(!$result=mysql_query($query,$_CHANEL)) error_handler("dberror",mysql_error()."\n".$query);
    while($s=@mysql_fetch_assoc($result))
    	$ret[$s[$field]]=$s;
    return($ret);
}

/***************************************************************
Процедура выборки из базы данных
Принимает в качестве параметров запрос и переменные, замещающие плэйсхолдеры
Возвращает одномерный массив с результатом.
***************************************************************/
function db_select_row()
{
	global $_CHANEL;
	if(!@mysql_ping($_CHANEL)) db_init_db();
	$args=func_get_args();
    $q=new fetcher($args);
    $query=$q->fetch_query();
    if(!$result=mysql_query($query,$_CHANEL)) error_handler("dberror",mysql_error()."\n".$query);
    $s=@mysql_fetch_assoc($result);
    return($s);
}

function db_select_col()
{
	global $_CHANEL;
	$ret=array();
	if(!@mysql_ping($_CHANEL)) db_init_db();
	$args=func_get_args();
    $q=new fetcher($args);
    $query=$q->fetch_query(); //error_handler("selectcol",$query);
    if(!$result=mysql_query($query,$_CHANEL)) error_handler("dberror",mysql_error()."\n".$query);
    while($s=@mysql_fetch_array($result))
    	$ret[]=$s[0];
    return($ret);
}

/***************************************************************
Процедура произвольного запроса в базу данных
Принимает в качестве параметров запрос и переменные, замещающие плэйсхолдеры
Возвращает в зависимости от типа запроса либо последний ID, либо количество
строк, над которыми БД проделала действия.
***************************************************************/
function db_query()
{
	global $_CHANEL;
	if(!@mysql_ping($_CHANEL)) db_init_db();
	$args=func_get_args();
    $q=new fetcher($args);
    $query=$q->fetch_query();  //error_handler("query",$query);
    if(!$result=mysql_query($query,$_CHANEL)) 
    {
	error_handler("dberror",mysql_error()."\n".$query);
	return(false);
    }
	switch(_query_type($query))
    {
    	case "update":
        case "delete":
        	return(mysql_affected_rows());
        case "insert":
        	return(mysql_insert_id($_CHANEL));
        default:
        	return(true);
    }
}

/***************************************************************
Процедура определения типа запроса
Возвращает строку - тип запроса
***************************************************************/
function _query_type($query)
{
	preg_match("/^(update|insert|delete)/sxi",ltrim($query),&$type);
    if(is_array($type))return($type[0]);
    return($type);
}

/***************************************************************
Процедура стартует транзакцию
***************************************************************/
function db_start_transaction($drop=0)
{
	static $flag;
    if($flag)
    	if($drop)
        {
        	$flag=0;
            return(false);
        }
        else return(true);
    $flag=1;
	global $_CHANEL;
	if(!@mysql_ping($_CHANEL)) db_init_db();
	$query="start transaction";
    mysql_query($query,$_CHANEL);
}

/***************************************************************
Процедура подтверждает транзакцию
***************************************************************/
function db_commit()
{
	global $_CHANEL;
	if(!@mysql_ping($_CHANEL)) db_init_db();
	$query="commit";
    mysql_query($query,$_CHANEL);
    start_transaction(1);
}

/***************************************************************
Процедура отменяет транзакцию
***************************************************************/
function db_rollback()
{
	global $_CHANEL;
	if(!@mysql_ping($_CHANEL)) db_init_db();
	$query="rollback";
    mysql_query($query,$_CHANEL);
    start_transaction(1);
}

function db_set($schema)
{
	global $_DBSTACK;
	if( !is_array( $_DBSTACK ) ) $_DBSTACK = array();
	array_push(&$_DBSTACK,$schema);
	if($dbparams[$schema]['dbname']) db_iselect_db($schema);
	else db_iselect_db("main");
	return(true);
}

function db_restore()
{
	global $_DBSTACK;
	$schema=array_pop(&$_DBSTACK);
	if($dbparams[$schema]['dbname']) db_iselect_db($schema);
	else db_iselect_db("main");
	return(true);	
}


/***************************************************************
В данном случае пришлось использовать класс, дабы не городить
еще один глобальный массив, ибо работа с ним была бы крайне неудобна.
***************************************************************/
class fetcher
{

	var $queryandargs;

    function fetcher($args)
    {
    	$this->queryandargs=$args;
    }

	/***************************************************************
	Процедура разбора и подстановки плэйсхолдеров
	***************************************************************/
	function fetch_query()
	{
	    global $_PREFIX;
	    if(!is_array($this->queryandargs)){return("");}
	    $query=array_shift($this->queryandargs);      // получаем собственно запрос
	    $query=str_replace("?_",$_PREFIX,$query);     // заменяем ?_ на установленный префикс
        $query=preg_replace_callback('/(\?)([FSnat#]?)/sx',	// а это собственно вызов замены
        array(&$this, 'fetchcallback'),                    // плэйсхолдеров их значениями
        $query,count($this->queryandargs));             // с использованием коллбэк функции,
        if(QUERY_LOGGING===true) echo $query."<br>";
        return($query);                                  // ради чего и создавался класс
	}

	/***************************************************************
	Коллбэк функция для замены плэйсхолдеров
	***************************************************************/
    function fetchcallback($matches)
    {
		global $_CHANEL;
    	$replace=array_shift($this->queryandargs);
        switch($matches[2])
        {
        	case "a":                         //Векторный плэйсхолдер
	            if(!is_array($replace)) return(false);
                foreach ($replace as $key=>$val)
                {
                    $val=$val!==null?$this->_quote($val):'NULL';
                    if (!is_int($key))                  //ассоциативный массив
                    {
                        $key=$this->_quote($key,true);
                        $ret.="$key=$val,";
                    }
                    else
                        $ret.="$val,";                  //индексный массив
                }
                return (rtrim($ret,","));
                break;
            case "t":                                   //табличный плэйсхолдер - сугубо для инсерта
	            if(!is_array($replace)) return(false);
                for($i=0;$i<count($replace);$i++)
                {
                	$temp="";
                 	for($j=0;$j<count($replace[$i]);$j++)
                    	$temp.=$this->_quote($replace).",";
                    $ret.="(".rtrim($temp,",")."),";
                }
                return(rtrim($ret,","));
                break;
			case "S":									 // Плэйсхолдер для сортировки - массив с полями и порядком сортировки для каждого
				if(!is_array($replace)) 
				{
					error_handler(BAD_DB_PLACEHOLDER,__FILE__.":".__FUNCTION__);
					return(false);
				}
				//print_r($replace);
				foreach($replace as $field => $order) 
				{
					$temp[]= "`$field` ".$order;
				}
				return (implode(",",$temp));
				break;
            case "#":                                    // именной плэйсхолдер
            	return($this->_quote($replace,true));
                break;
			case "n":									// Цифроой плэйсхолдер (должно быть число)
				if(is_numeric($replace)) return($replace);
				else return (false);
				break;
			case 'F':
                $valid_operations=array('>','<','=','>=','<=','<>','like','is');
                if(!is_array($replace)) return ('1'); 
                foreach ($replace as $filter)
                {
                    if(!in_array($filter['operation'], $valid_operations)) return(false);
                    $ret[]=$this->_quote($filter['left'],true).' '.$filter['operation'].' '.$this->_quote($filter['right']);
                }
                return(implode(' and ',$ret));
            case "":                                      // простой плэйсхолдер
                if (!is_scalar($replace)) return(false);
                return ($this->_quote($replace));
                break;
            default:
                return ($this->_quote($replace));

        }
    }
	/***************************************************************
	Процедура экранирования значений плэйсхолдеров
	***************************************************************/
    function _quote($str,$isname=false)
    {
    	global $_CHANEL;
		
        if(get_magic_quotes_gpc()) $str=stripslashes($str);
        if (!$isname)
            return ("'".mysql_real_escape_string($str, $_CHANEL)."'");
        else
            return ("`".mysql_real_escape_string(str_replace('`', '``', $str), $_CHANEL)."`");
    }
}
}
// Загрузка тестовых заглушек
else{
    require_once('db_mock.inc.php');
}
?>