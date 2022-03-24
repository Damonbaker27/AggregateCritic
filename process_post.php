<?php

	//require 'authenticate.php';

	require('db_connect.php');

	$query = "SELECT * FROM a32 WHERE id = :id LIMIT 1";
    $statement = $db->prepare($query);
	
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    $statement->bindValue('id', $id, PDO::PARAM_INT);
    
    $statement->execute();

	$row = $statement->fetch();



?>