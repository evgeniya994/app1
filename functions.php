<?php
session_start();
require_once ('connect.php');
require_once ('user_functions.php');


/**
 * Возвращает список статусов
 * @return array
 */
function getStatuses()
{
    global $handle;
    $query = "SELECT name_status,id_status
	       FROM statuses
	       ORDER BY name_status ASC";
    $result = $handle->query($query);
    $res = $result->fetch_all(MYSQLI_ASSOC);
    return $res;
}

function getCities()
{
    global $handle;
    $query = "SELECT name_city,id_city
	       FROM cities
	       ORDER BY name_city ASC";
    $result = $handle->query($query);
    $res = $result->fetch_all(MYSQLI_ASSOC);
    return $res;
}

function getStreets()
{
    global $handle;
    $query = "SELECT name_street,id_street
	       FROM streets
	       ORDER BY name_street ASC";
    $result = $handle->query($query);
    $res = $result->fetch_all(MYSQLI_ASSOC);
    return $res;
}


function getUsers()
{
    global $handle;
    $sql = "SELECT *
	       FROM users
	       WHERE id_users=$_SESSION[id_users]";
    $result = $handle->query($sql);
    if ($result->num_rows == 0) {
        return null;
    }
    return $result->fetch_assoc();
}