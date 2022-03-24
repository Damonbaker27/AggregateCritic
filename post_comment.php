<?php
session_start();
 echo($_SESSION['gameID']);


    require 'authenticate.php';

    require('db_connect.php');

    if($_POST && isset($_POST['userScore']) && isset($_POST['userReview'])){

       //adds the new comment to the review table
        $userScore = filter_input(INPUT_POST, 'userScore', FILTER_SANITIZE_FULL_SPECIAL_CHARS);    
        $userReview = filter_input(INPUT_POST, 'userReview', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
        $userID = $_SESSION["id"];   

        $query = "INSERT INTO Reviews (userScore, reviewContent, userID) 
                VALUES (:userScore, :reviewContent, :userID)";

        $statement = $db->prepare($query);

        $statement->bindValue(":userScore", $userScore);
        $statement->bindValue(":reviewContent", $userReview);
        $statement->bindValue(":userID", $userID);  
        $statement->execute();

 
        //gets the review id for the new comment
        $reviewIDQuery = "SELECT reviews.reviewID  FROM reviews  
            WHERE reviewID = (SELECT max(reviews.reviewID) FROM reviews)";
        
        $reviewStatement = $db->prepare($reviewIDQuery);  
        $reviewStatement ->execute();      
        $reviewRow = $reviewStatement->fetch();
        
        
        //updates the bridgetable with the newest comment 
        $bridgeUpdateQuery = "INSERT INTO bridgetable (reviewID, gameID) VALUES (:reviewID, :gameID)";

        $bridgeStatement =$db->prepare($bridgeUpdateQuery);
        $bridgeStatement ->bindValue(":reviewID", $reviewRow['reviewID']);
        $bridgeStatement ->bindValue(":gameID", $_SESSION['gameID']);
        $bridgeStatement ->execute();
        
        
        
        header('Location: show.php?id='. $_SESSION["gameID"]);

    }else {
       $errorflag = false;
    }

    

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div id="wrapper">
    <h1>An Error Occured.</h1>
    <h2>please enter at least one character for the review and reviewScore.</h2>
    <a href="index.php">Return Home</a>
</body>
</html>