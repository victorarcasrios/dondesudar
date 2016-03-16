<?php

require "../../db.php";
require_once '../../models/users.php';

switch ($_POST['action']) {
    case "isAnAvailableEmail":
        isAnAvailableEmail($_POST['email'], $_POST["except"]);
        break;
    case "nickIsAvailable":
        nickIsAvailable($_POST["nick"], $_POST["except"]);
        break;
}

function isAnAvailableEmail($email, $except){
    $return = 0;
    $id = users::getIdWhereEmailIs($email);

    // No hay otro o es el actual
    if(!$id || $id === $except)
    	$return = 1;
    else
    	$return = 0;

    echo $return;
}

function nickIsAvailable($nick, $except){
    $return = 0;
	$id = users::getIdWhereNickIs($nick);

	// No hay otro o es el actual
	if(!$id || $id === $except)
		$return = 1;
	else
		$return = 0;

	echo $return;
}