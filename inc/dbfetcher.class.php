<?php
/* 
 * Fetcher for database placeholders
 */

class dbFetcher{
    private static $error=false;
    private static $classname='';
    private function  __construct() {

    }

    private function  __clone() {

    }

    static function fetch($classname, $query, $args)
    {
        self::$error=false;
        $peaces=explode('?', $query);
        $count=count($peaces)-1;
        if($count!=count($args)){
            self::_error('Illegal parameters number');
            return FALSE;
        }
        self::$classname=$classname;
        for($i=1;$i<=count;$i++){
            $name=$peaces[$i][0].'PlaceHolder';
            if(is_callable("self::$name")){
                $res=self::$name($args[$i]);
            }
            else{
                $res=self::regularPlaceHolder($args[$i]);
            }
            if(FALSE===$res){
                return(FALSE);
            }
            $peaces[$i]=str_replace($peaces[$i][0], $res, $peaces[$i], 1);
        }
        $ret=implode('',$peaces);
    }

    private static function TPlaceHolder($data)
    {
        $name=self::$classname;
        $data=$name::tableName($data);
        return $data;
    }

    private static function fPlaceHolder($data)
    {
        $name=self::$classname;
        $data=$name::quote($data,TRUE);
        return $data;
    }

    private static function aPlaceHolder($data)
    {
        if(!is_array($data)){
            self::_error('Illegal placeholder type - must be an array');
            return FALSE;
        }
        $name=self::$classname;
        foreach ($data as $key => $val){
            if(is_int($key)){
                $val=$name::quote($val);
                $ret[]=$val;
            }
            else{
                $key=$name::quote($key,TRUE);
                $val=$name::quote($val);
                $ret[]="{$key}={$val}";
            }
        }
        $ret=implode(',',$ret);
        return $ret;
    }

    private  static function tPlaceHolder($data)
    {
        if(!is_array($data)){
            self::_error('Illegal placeholder type - must be an array');
            return FALSE;
        }
        $name=self::$classname;
        foreach ($data as $row){
            if(is_array($row)){
                foreach($row as $val){
                    $val=$name::quote($val);
                    $temp[]=$val;
                }
            }
            $ret[]='('.implode(',',$temp).')';
        }
        $ret=implode(',',$ret);
        return($ret);
    }

    private  static function nPlaceHolder($data)
    {
        if(!is_numeric($data)){
            self::_error('Illegal placeholder type - must be a numeric');
            return FALSE;
        }
        return($data);
    }

    private static function _error($error)
    {
        self::$error=$error;
    }

}

?>
