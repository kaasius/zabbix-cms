<?php
/* 
 * Universal database class
 */

defined(DB_INI_FILE) or define('DB_INI_FILE',$_SERVER['DOCUMENT_ROOT'].'/ini/db.ini');
defined(DB_PREFIX_PH) or define('DB_PREFIX_PH','?_');

class db{
    static private $error;
    static private $presets;
    static private $classname;

    private function  __clone() {

    }

    private function  __construct($name='main')
    {
        $data=file(DB_INI_FILE);
        if(is_array($data)){
            foreach($data as $row){
                $temp=explode('=', $row);
                $name=$temp[0];
                $val=parse_url($temp[1]);
                $val['database']=$val['path'];
                unset($val['path']);
                $val['prefix']=$val['query'];
                unset($val['query']);
                self::$presets[$name][]=$val;
            }
            self::selectDb($name);
        }
        else{
            die('No Database INI file exists');
        }
    }

    public static function selectDb($name)
    {
        self::$error=false;
        if(!self::$presets) {
            new db($name);
        }
        $set=self::$presets[$name];
        if(!$set) {
            self::_error(self::DB_ILLEGAL_NAME);
        }
        self::$classname='db_'.$name;
        'db_'.$name::configure($set);
    }

    public static function select()
    {
        if(!self::$classname) {
            new db();
        }
        self::$error=false;
        $name=self::$classname;
        if(!($ret=$name::select(func_get_args()))){
            self::_error($name::lastError());
            $ret=false;
        }
        return $ret;
    }

    public static function selectRow()
    {
        if(!self::$classname) {
            new db();
        }
        self::$error=false;
        $name=self::$classname;
        if(!($ret=$name::selectRow(func_get_args()))){
            self::_error($name::lastError());
            $ret=false;
        }
        return $ret;
    }

    public static function selectCol()
    {
        if(!self::$classname) {
            new db();
        }
        self::$error=false;
        $name=self::$classname;
        if(!($ret=$name::selectCol(func_get_args()))){
            self::_error($name::lastError());
            $ret=false;
        }
        return $ret;
    }

    public static function selectCell()
    {
        if(!self::$classname) {
            new db();
        }
        self::$error=false;
        $name=self::$classname;
        if(!($ret=$name::selectCell(func_get_args()))){
            self::_error($name::lastError());
            $ret=false;
        }
        return $ret;
    }

    public static function selectHash()
    {
        if(!self::$classname) {
            new db();
        }
        self::$error=false;
        $name=self::$classname;
        if(!($ret=$name::selectHash(func_get_args()))){
            self::_error($name::lastError());
            $ret=false;
        }
        return $ret;
    }

    public static function query()
    {
        if(!self::$classname) {
            new db();
        }
        self::$error=false;
        $name=self::$classname;
        if(!($ret=$name::query(func_get_args()))){
            self::_error($name::lastError());
            $ret=false;
        }
        return $ret;
    }

    private static function _error($error)
    {
        self::$error=$error;
    }
}

?>
