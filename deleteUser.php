<?php

session_start();

	require('db_connect.php');	
    
    if ($_POST && !empty($_POST['userID'])) {
        
        $userid  = filter_input(INPUT_POST, 'userID', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $query = $query = "DELETE FROM users WHERE userID = :userid  LIMIT 1";
        
        if($statement = $db->prepare($query)){
            $statement->bindValue(':userid', $userid, PDO::PARAM_INT);
            
            if($statement->execute()){
               header("Location: users.php");
            }
        
        }   
        
	}



?>