<?php 

require('db_connect.php');

echo($_POST['imageID']);
echo($_POST['gameID']);




	if (!empty($_POST['gameID']) ) {
        
        $gameID  = filter_input(INPUT_POST, 'gameID', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $query = "DELETE FROM games WHERE gameID = :gameID LIMIT 1";
        $statement = $db->prepare($query);       

        $statement->bindValue(':gameID', $gameID, PDO::PARAM_INT);

		$statement->execute();
    }   




//deletes the image if the checkbox is selected.
if(isset($_POST['imageID'])){
    $imagePath  = filter_input(INPUT_POST, 'imagePath', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $imageID = filter_input(INPUT_POST, 'imageID', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $updateGameID = filter_input(INPUT_POST, 'gameID', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    echo("deleted selected");
    $path = $imagePath;
    if (is_file($path)) {
        unlink($path);
        
        $query = " DELETE FROM images
         WHERE imageID = :imageID LIMIT 1";
        
        $statement = $db->prepare($query);
        $statement->bindValue(':imageID', $imageID, PDO::PARAM_INT);
        $statement->execute();


        $query = "UPDATE games SET imageID = NULL
         WHERE gameID = :gameID LIMIT 1";
        $statement = $db->prepare($query);
        $statement->bindValue(':gameID', $updateGameID, PDO::PARAM_INT);
        $statement->execute();



    } else {
        die('your image not found');
    } 
}
		


//header("location:index.php");






















?>