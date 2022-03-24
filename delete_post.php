<?php
session_start();

	require('db_connect.php');

	if ($_POST && !empty($_POST['reviewcontent']) && !empty($_POST['reviewid'])) {
        
        $userid  = filter_input(INPUT_POST, 'userid', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $reviewcontent = filter_input(INPUT_POST, 'reviewcontent', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $reviewid = filter_input(INPUT_POST, 'reviewid', FILTER_SANITIZE_NUMBER_INT);

        $query = "DELETE FROM reviews WHERE reviewID = :reviewid AND userID = :userid  LIMIT 1";
        $statement = $db->prepare($query);       
        echo($reviewid);
        echo($userid);


        $statement->bindValue(':reviewid', $reviewid, PDO::PARAM_STR);
        $statement->bindValue(':userid', $userid, PDO::PARAM_STR);


		if($statement->execute()){
            header("Location: show.php?id=".$_SESSION['gameID']);
        }


		

	}



?>