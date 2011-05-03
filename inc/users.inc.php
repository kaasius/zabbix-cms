<?php

/*
 * Библиотека для работы с пользователями
 */
define(SALT, 'zabbix-cms');

function users_add($email,$pass){
    if (empty($email)){
        return false;
    }
    if (preg_match('/^([0-9a-zA-Z]([-.w]*[0-9a-zA-Z])*@([0-9a-zA-Z][-w]*[0-9a-zA-Z].)+[a-zA-Z]{2,9})$/si', $email) != "1"){
        return false;
    }
    if (empty($pass)){
        return false;
    } 
    $password = md5($pass.SALT);
    if (driver_add_user($email, $password) == false)
            return false;
    return true;
}

function users_delete($id){
    if (driver_del($id) == false)
            return false;
    return true;    
}

function users_update($id,$array){
    $user_array = driver_load_user($id);
    if(empty($user_array)){
        return false;
    }
    return true;    
}

function users_auth($username,$pswd){
    if (empty($pswd)){
        return false;
    }
    if (empty($username)){
        return false;
    }    
    $user_array = driver_load_user($username);
    if ($user_array == false) {
        return false;
    }   
    $password = md5($pswd.SALT);
    $real_password = $user_array['password'];
    $real_username = $user_array['email'];    
    if ($password == $real_password && $username == $real_username){
        return true;
    } else {
        return false;
    }
}

function users_login($email,$password){
    if (empty($email)){
        return false;
    }
    if (empty($password)){
        return false;
    } 
    $user_array = driver_load_user($email);
    if ($user_array == false) {
        return false;
    }
    $password = md5($password.SALT);
    $real_password = $user_array['password'];
    $real_email = $user_array['email'];
    if ($password == $real_password && $email == $real_email){
        session_start();
        $_SESSION['username'] == $email;
        $_SESSION['pwd'] == $password;
        return true;
    } else {
        return false;
    }    
}


function users_logout($email){
    if (empty($email)){
        return false;
    }
//    if (empty($pass)){
//        return false;
//    } 
    $user_array = driver_load_user($email);
    if ($user_array == false) {
        return false;
    }    
    session_start();
    $password = md5($_SESSION['pwd'].SALT);
    $real_password = $user_array['password'];
    $real_email = $user_array['email'];
    if ($password == $real_password && $_SESSION['username'] == $real_email){
        $_SESSION['username'] == '';
        $_SESSION['pwd'] == '';
        session_destroy();
        return true;
    } else {
        return false;
    }       
}
?>