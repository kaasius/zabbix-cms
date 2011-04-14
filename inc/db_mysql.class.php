<?php
/* 
 * Mysql database class
 */

class db_mysql{

    private static $connection;
    private static $error;
    private static $prefix;

    private function __clone()
    {

    }

    private function  __construct()
    {

    }

    static function configure($data)
    {
        if(count($data>1)){
            die('Данная библиотека db_mysql.class.php не поддерживает многосерверную конфигурацию базы данных');
        }
        $set=$data[0];
        $server=$set['port']?$set['host'].':'.$set['port']:$set['host'];
        self::$connection=mysql_connect($server, $set['user'], $set['pass']);
        if(!self::$connection){
            self::_error(mysql_error());
            return(false);
        }
        if(!mysql_select_db($set['database'], self::$connection)){
            self::_error(mysql_error(self::$connection));
            return(FALSE);
        }
        self::$prefix=$set['prefix'];
        return(TRUE);
    }

    private static function _error($error)
    {
        self::$error=$error;
    }

    static function select($args)
    {
        $ret=FALSE;
        $result=self::_query($args);
        while ($row=@mysql_fetch_assoc($result)){
            $ret[]=$row;
        }
        mysql_free_result($result);
        return($ret);
    }

    static function selectRow($args)
    {
        $result=self::_query($args);
        $row=@mysql_fetch_assoc($result);
        mysql_free_result($result);
        return $row;
    }

    static function selectCol($args)
    {
        $ret=FALSE;
        $result=self::_query($args);
        while ($row=@mysql_fetch_array($result)){
            $ret[]=$row[0];
        }
        mysql_free_result($result);
        return($ret);
    }

    static function selectCell($args)
    {
        $result=self::_query($args);
        $row=@mysql_fetch_array($result);
        $ret=$row[0];
        mysql_free_result($result);
        return($ret);

    }

    static function selectHash($args)
    {
        $field=array_shift($args);
        $result=self::_query($args);
        while ($row=@mysql_fetch_array($result)){
            $ret[$row[$field]]=$row;
        }
        mysql_free_result($result);
        return($ret);
    }

    static function query($args)
    {
        $result=self::_query($args);
        $type=strtolower(substr(ltrim($args[0]),0,6));
        switch ($type){
            case 'insert':
                return(mysql_insert_id(self::$connection));
                break;
            case 'update':
            case 'delete':
                return(mysql_affected_rows(self::$connection));
                break;
            default:
                if($result){
                    return TRUE;
                }
                break;
        }
        return(FALSE);
    }

    private static function _query($args)
    {
        self::$error=false;
        if(!count($args)){
            self::_error('Empty query');
            return(false);
        }
        $query=array_shift($args);
        $query=self::_fetchQuery($query, $args);
        if(TRUE===DB_LOG_QUERY){
            $time=microtime(true);
            $ret=mysql_query($query,self::$connection);
            $delta=microtime(TRUE)-$time;
            self::_logQuery($query,$delta);
        }
        else{
            $ret=mysql_query($query,self::$connection);
        }
        if(!$ret){
            $error=mysql_error(self::$connection)."\n query=".$query;
            self::_error($error);
            return(false);
        }
    }

    private static function _fetchQuery($query,$args)
    {
        $query=dbFetcher::fetch('db_mysql',$query,$args);
        return($query);
    }

    static function quote($str,$is_name=false)
    {
        if($is_name){
            $ret="`".mysql_real_escape_string(str_replace('`', '``', $str), self::$connection)."`";
        }
        else{
            $ret="'".mysql_real_escape_string($str, self::$connection)."'";
        }
        return($ret);
    }

    static function tableName($name)
    {
        $name=self::$prefix.$name;
        $name=self::quote($name,TRUE);
        return $name;
    }

}

?>
