<?php


function driver_load_lang($name, $lang="ru"){
    $lang_file = $_SERVER["DOCUMENT_ROOT"]."/lang/".$lang."/".$name;
    if (@readfile($lang_file) == false){
        return false;
    }
    require_once($lang_file);
    return true;
}
?>