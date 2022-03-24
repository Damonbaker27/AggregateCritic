<?php

session_start();

	require('db_connect.php');


	
    
    if ($_POST && !empty($_POST['reviewcontent']) && !empty($_POST['reviewid'])) {
        
        $userid  = filter_input(INPUT_POST, 'userid', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $reviewcontent = filter_input(INPUT_POST, 'reviewcontent', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $reviewid = filter_input(INPUT_POST, 'reviewid', FILTER_SANITIZE_NUMBER_INT);

        
        $query = "UPDATE reviews SET reviewContent = :reviewcontent WHERE userID = :userid 
                AND reviewID = :reviewid";
        
        if($statement = $db->prepare($query)){
            $statement->bindValue(':reviewcontent', $reviewcontent, PDO::PARAM_STR);
            $statement->bindValue(':userid', $userid, PDO::PARAM_INT);
            $statement->bindValue(':reviewid', $reviewid, PDO::PARAM_INT);
            
            if($statement->execute()){
                header("Location: show.php?id=" .$_SESSION['gameID']);
            }
        
        }   
        
        
                
        

		


		

	}



?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Error</title>
</head>
<body>
    <div id="wrapper">
    <h1>An Error Occured.</h1>
    <h2>please enter at least one character for the title and post.</h2>
    <a href="index.php">Return Home</a>
</div>
</body>
</html>