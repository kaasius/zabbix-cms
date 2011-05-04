<?php

/*
 * Библиотека для работы с пользователями
 */
define(SALT, 'zabbix-cms');
if(!defined('TEST_MODE')) define("TEST_MODE",false);
define('DEBUG_MODE',true);
require_once("errors.list.php");
require ("$HTTP_SERVER_VARS[DOCUMENT_ROOT]/config.inc.php");
require ("$inc_path/_db.inc.php");



function users_valid_email($email){
    if (!preg_match('/^([0-9a-zA-Z]([-.w]*[0-9a-zA-Z])*@([0-9a-zA-Z]*.)+[a-zA-Z]{2,9})$/si', $email)){
        return false;
    } else 
        return true;
}

function users_add_post($email,$pass){
    $email = $_POST["email"];
    $password = $_POST["password"];    
    if (empty($email)){
        return false;
    }
    if (users_valid_email($email) == false){
        return false;
    }
    if (empty($pass)){
        return false;
    } 
    $password = md5($pass.SALT);
    if (users_add_user($email, $password) == false){
            return false;
    }
    return true;
}

function users_delete($id){
    if (users_del($id) == false)
            return false;
    return true;    
}

function users_update($id,$array){
    $user_array = users_load_user($id);
    if(empty($user_array)){
        return false;
    }
    return true;    
}

function users_auth(){
    session_start();
    if (empty($_SESSION['userid'])){
        return false;
    } else {
        return true;
    }
}

function users_login_post(){
    $email = $_POST["email"];
    $password = $_POST["password"];     
    if (empty($email)){
        return false;
    }
    if (empty($password)){
        return false;
    } 
    $user_array = users_load_user($email);
    if ($user_array == false) {
        return false;
    }
    $password = md5($password.SALT);
    $real_password = $user_array['password'];
    $real_email = $user_array['email'];
    if ($password == $real_password){
        session_start();
        $_SESSION['userid'] = $user_array['id'];
//        $_SESSION['username'] = $user_array['email'];
        return true;
    } else {
        return false;
    }    
}


function users_logout_post($email){
    session_start();
    if (empty($_SESSION['userid'])){
        return false;
    } else {
        unset($_SESSION['userid']);
//        unset($_SESSION['username']);
        session_destroy();
        return true;
    }
}




function users_add_user($email, $password){
    echo $email;
    $query = ("INSERT INTO users (email, password) VALUES(?, ?)");
    if (query($query, $email, $password) == false){
        echo "-";
        return false;
    } else {
    echo "+";
    return true;
    }
}

function users_load_user($email){
    $query = selectrow("SELECT * from users WHERE email = ?", $email);
    if ($query == false){
        return false;
    }
    return $query;
}

function users_del($id){
    $query = ("DELETE from users WHERE id=?");
    if (query($query, $id) == false){ 
        return false;
    }
    return true;       
}
?>