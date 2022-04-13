<?php

session_start();

	require('db_connect.php');	
    
    if(isset($_POST['userID'])){
        
        $userID  = filter_input(INPUT_POST, 'userID', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $userName  = filter_input(INPUT_POST, 'userName', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $roleLevel  = filter_input(INPUT_POST, 'roleLevel', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
        if(!empty($_POST['tempPass'])){
            $password = filter_input(INPUT_POST, 'tempPass', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $sanitizedPassword = password_hash($password, PASSWORD_DEFAULT);

            $query = "UPDATE users SET userName = :userName, roleLevel = :roleLevel, password = :password
                    WHERE userID = :userID";

            $statement = $db->prepare($query);
            $statement->bindValue(':userID', $userID, PDO::PARAM_INT);
            $statement->bindValue(':userName', $userName, PDO::PARAM_STR);
            $statement->bindValue(':roleLevel', $roleLevel, PDO::PARAM_STR);
            $statement->bindValue(':password', $sanitizedPassword, PDO::PARAM_STR);
                
            $statement->execute();
            header("Location: users.php");

        }
        


       // echo($userid);
        //echo($userName);
        echo($roleLevel);
        echo($_POST['userID']);
        

        $query = "UPDATE users SET userName = :userName, roleLevel = :roleLevel
        WHERE userID = :userID";
        
        $statement = $db->prepare($query);
        $statement->bindValue(':userID', $userID, PDO::PARAM_INT);
        $statement->bindValue(':userName', $userName, PDO::PARAM_STR);
        $statement->bindValue(':roleLevel', $roleLevel, PDO::PARAM_STR);
        
            
        $statement->execute();
        header("Location: users.php");

          
	}else{
        echo('an error occured');
    }



?>