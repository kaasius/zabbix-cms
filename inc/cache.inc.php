<?
require_once ("$_SERVER[DOCUMENT_ROOT]/config.inc.php");
//require_once ("$inc_path/log.inc.php");
/*ATTENTION! Do not change this section, if you want to redefine default
constants - do it in config.inc.php*/
if(!defined('KEY_PREFIX')) define ("KEY_PREFIX",$_SERVER['HTTP_HOST']);
if(!defined('CACHE_DIR')) define ("CACHE_DIR",$_SERVER['DOCUMENT_ROOT']."/cache/");
if(!defined('STATIC_CACHE_DIR')) define ("STATIC_CACHE_DIR",$_SERVER['DOCUMENT_ROOT']."/static_cache/");
if(!defined('MEMCACHE_ENABLE')) define("MEMCACHE_ENABLE","NO");
if(!defined('DEBUG_MODE')) define("DEBUG_MODE",false);
if(!defined('CACHE_TAGS_FILE')) define('CACHE_TAGS_FILE',CACHE_DIR."_TAGS_");
if(!defined('MEMCACHE_DEFAULT_HOST')) define('MEMCACHE_DEFAULT_HOST',"localhost");
if(!defined('MEMCACHE_DEFAULT_PORT')) define('MEMCACHE_DEFAULT_PORT',11211);

function mkdir_recursive($pathname)
{
    is_dir(dirname($pathname)) || mkdir_recursive(dirname($pathname));
    return is_dir($pathname) || @mkdir($pathname);
}

function cache_connect()
{
     global $_MEMCACHE_SERVERS_;
     static $channel;
     if($channel) return($channel);
     $channel=memcache_connect(MEMCACHE_DEFAULT_HOST, MEMCACHE_DEFAULT_HOST);
     if(is_array($_MWMCACHE_SERVERS_))
          foreach ($_MWMCACHE_SERVERS_ as $key=>$val)
               memcache_add_server($channel, $key,$val);
     return($channel);
}

function normalize_path($str)
{
	if($str=="/" or (!$str)) return ("/index.htm?");
    $pathinfo=pathinfo($str);
    if(!$pathinfo[basename]) return($str."/index.htm?");
//	if(!$pathinfo[extension]) return($str."?");
    return($str."?");
}

function set_static_cache($key,$value,$tags="")
{
	if(USE_CACHE=="NO") return("");
	$key=normalize_path($key);
    $cachefile=str_replace("//","/",STATIC_CACHE_DIR . $key);
    if(!@file_put_contents($cachefile,$value))
    {
               	if(!mkdir_recursive(dirname($cachefile)))
                {
                	cache_error("Не могу сохранить кеш в $cachefile, файл ".__FILE__.", строка ".__LINE__.", функция ".__FUNCTION__);
                	return(0);
                }
                if(!@file_put_contents($cachefile,$value))
                {
                	cache_error("Не могу сохранить кеш в $cachefile, файл ".__FILE__.", строка ".__LINE__.", функция ".__FUNCTION__);
                	return(0);
                }
    }
}

function get_static_cache($key)
{
	if(USE_CACHE=="NO") return("");
	$key=normalize_path($key);
    $cachefile=str_replace("//","/",STATIC_CACHE_DIR . $key);
    return(@file_get_contents($cachefile));
}

function delete_static_cache($key)
{
	if(USE_CACHE=="NO") return("");
	$key=normalize_path($key);
    $cachefile=str_replace("//","/",STATIC_CACHE_DIR . $key);
    if(!@file_exists($cachefile)) return(true);
    if(!@unlink($cachefile))
    {
        cache_error("Не могу удалить файл $cachefile, файл ".__FILE__.", строка ".__LINE__.", функция ".__FUNCTION__);
        return (false);
    }
    return(true);
}

function get_cache($key)
{
	if(USE_CACHE=="NO") return("");
    if(MEMCACHE_ENABLE=="YES" && function_exists("memcache_connect"))
    {
        if(!$connection=cache_connect())
        {
             cache_error("Не могу подключиться к memcached, файл ".__FILE__.", строка ".__LINE__.", функция ".__FUNCTION__);
             return ("");
        }
        return(memcache_get($connection,KEY_PREFIX . $key));
    }
    else
    {
    	$key=normalize_path($key);
        $cachefile=str_replace("//","/",CACHE_DIR . $key);
        if($t=@filemtime($cachefile)!=0 && $t<time()) return("");
        $temp=unserialize(@file_get_contents($cachefile));
        return ($temp);
    }
}

function set_cache($key,$value,$tags="",$time=0)
{
	if(USE_CACHE=="NO") return("");
    if(MEMCACHE_ENABLE=="YES" && function_exists("memcache_connect"))
    {
        if(!$connection=cache_connect())
        {
                cache_error("Не могу подключиться к memcached, файл ".__FILE__.", строка ".__LINE__.", функция ".__FUNCTION__);
            return ("");
        }
        if(!memcache_set($connection,$key,$value,0,$time))
        {
                cache_error("Не могу сохранить кеш в memcached, файл ".__FILE__.", строка ".__LINE__.", функция ".__FUNCTION__);
                        return(0);
        }
    }
    else
    {
    	$key=normalize_path($key);
        $cachefile=str_replace("//","/",CACHE_DIR . $key);
        if(!@file_put_contents($cachefile,serialize($value)))
        {
               	if(!mkdir_recursive(dirname($cachefile)))
                {
                	cache_error("Не могу сохранить кеш в $cachefile, файл ".__FILE__.", строка ".__LINE__.", функция ".__FUNCTION__);
                	return(0);
                }
                if(!@file_put_contents($cachefile,serialize($value)))
                {
                	cache_error("Не могу сохранить кеш в $cachefile, файл ".__FILE__.", строка ".__LINE__.", функция ".__FUNCTION__);
                	return(0);
                }
        }
        if(!$time)
        	@touch($cachefile,0);
        else
        	@touch($cachefile,time()+$time);
    }
    if($tags)
        if(!add_tags($key,is_array($tags)?$tags:@explode(",",$tags)))
        {
               cache_error("Не могу сохранить теги для ключа $key, кеш удален, файл ".__FILE__.", строка ".__LINE__.", функция ".__FUNCTION__);
               delete_cache($key);
               return(0);
        }
}

function delete_cache($key)
{
	if(USE_CACHE=="NO") return("");
    if(MEMCACHE_ENABLE=="YES" && function_exists("memcache_connect"))
    {
                if(!$connection=cache_connect())
        {
                cache_error("Не могу подключиться к memcached, файл ".__FILE__.", строка ".__LINE__.", функция ".__FUNCTION__);
            return (false);
        }
        return(memcache_delete($connection,KEY_PREFIX . $key));
    }
    else
    {
       	$key=normalize_path($key);
        $cachefile=str_replace("//","/",CACHE_DIR . $key);
        if(!@file_exists($cachefile)) return(true);
        if(!@unlink($cachefile))
        {
            cache_error("Не могу удалить файл $cachefile, файл ".__FILE__.", строка ".__LINE__.", функция ".__FUNCTION__);
            return (false);
        }
        return ("");
    }
}


function get_tags()
{
    $oldtags=array();
    while((!@rename(CACHE_TAGS_FILE,CACHE_DIR."_TEMPTAGS_")) and $i<1000)
    {
        if(!file_exists(CACHE_DIR."_TEMPTAGS_"))
        {
            cache_error ("Теги отсутствуют, создайте файл ".CACHE_TAGS_FILE." и разрешите в него запись для всех, файл ".__FILE__.", строка ".__LINE__.", функция ".__FUNCTION__);
            return(false);
        }
        $i++;
        usleep(100);
    }
    if($i>=1000)
    {
        cache_error ("deadlock, файл ".__FILE__.", строка ".__LINE__.", функция ".__FUNCTION__);
        return(false);
    }
    $oldtags=unserialize(@file_get_contents(CACHE_DIR."_TEMPTAGS_"));
    if($oldtags && (!@is_array($oldtags)))
    {
        cache_error("Неверные данные в структуре тегов, файл ".__FILE__.", строка ".__LINE__.", функция ".__FUNCTION__);
        if(!@rename(CACHE_DIR."_TEMPTAGS_",CACHE_TAGS_FILE)) cache_error ("deadlock, файл ".__FILE__.", строка ".__LINE__.", функция ".__FUNCTION__);
        return(false);
    }
    if(!$oldtags)$oldtags=array();
    return($oldtags);
}

function put_tags($tags)
{
    if(!file_put_contents(CACHE_TAGS_FILE,serialize($tags)))
    {
        cache_error("Не могу записать теги, файл ".__FILE__.", строка ".__LINE__.", функция ".__FUNCTION__);
        return(false);
    }
    @unlink(CACHE_DIR."_TEMPTAGS_");
}

function restore_tags()
{
    if(!@rename(CACHE_DIR."_TEMPTAGS_",CACHE_TAGS_FILE))
    {
        cache_error("Не могу восстановить теги, файл ".__FILE__.", строка ".__LINE__.", функция ".__FUNCTION__);
        return(false);
    }
}


function add_tags($key,$tags)
{
    if(!$tags) return(true);
    if(!is_array($tags))$tags=array($tags);
    if(($oldtags=get_tags())===false)
    {
        cache_error("Ошибка чтения тегов, файл ".__FILE__.", строка ".__LINE__.", функция ".__FUNCTION__);
		restore_tags();
    	return(false);
    }
    for($i=0;$i<@count($tags);$i++)
    {
        $oldtags[$tags[$i]][]=$key;
        $oldtags[$tags[$i]]=@array_unique($oldtags[$tags[$i]]);
    }
    put_tags($oldtags);
    return(true);
}

function delete_tags($tags,$static=0)
{
    if(!$tags) return(true);
    if(!is_array($tags))$tags=array($tags);
    if($oldtags=get_tags()===false) return(false);
    for($i=0;$i<@count($oldtags);$i++)
    {
        $delete=$oldtags[$i];
        $notdeleted=array();
        for($j=0;$j<@count($delete);$j++)
        {

            if(!delete_cache($delete[$j]))
            {
                cache_error("Не могу удалить тег $delete[$j], файл ".__FILE__.", строка ".__LINE__.", функция ".__FUNCTION__);
                $notdeleted[]=$delete[$j];
            }
            if(!delete_static_cache($delete[$j]))
            {
                cache_error("Не могу удалить тег $delete[$j], файл ".__FILE__.", строка ".__LINE__.", функция ".__FUNCTION__);
                $notdeleted[]=$delete[$j];
            }

        }
        $oldtags[$i]=$notdeleted;
    }
    put_tags($oldtags);
    return(true);
}



function cache_error($str)
{
    if(DEBUG_MODE)
        logging("cache_errors",$str);
    return(1);
}
?>