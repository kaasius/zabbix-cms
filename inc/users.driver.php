<?php

/*
 * Драйвер для библиотеки пользователей
 */

function driver_add_user($email, $password){
    $query = ("INSERT INTO users (email, password) VALUES(?, ?)");
    if (query($query, $email, $password) == false){
        return false;
    }
    return true;
}

function driver_load_user($email){
    $query = selectrow("SELECT * from email WHERE id = ?", $email);
    if ($query == false){
        return false;
    }
    return $query;
}

function driver_del($id){
    $query = ("DELETE from users WHERE id=?");
    if (query($query, $id) == false){ 
        return false;
    }
    return true;       
}
?>
