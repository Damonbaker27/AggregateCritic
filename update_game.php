<?php
session_start();

require 'C:\xampp\htdocs\wd2\Project\php-image-resize-master\lib\ImageResize.php';
require 'C:\xampp\htdocs\wd2\Project\php-image-resize-master\lib\ImageResizeException.php';
use \Gumlet\ImageResize;

    require('db_connect.php');



// file_upload_path() - Safely build a path String that uses slashes appropriate for our OS.
   // Default upload path is an 'uploads' sub-folder in the current folder.
   function file_upload_path($original_filename, $upload_subfolder_name = 'uploads') {
       $current_folder = dirname(__FILE__);
       
       // Build an array of paths segment names to be joins using OS specific slashes.
       $path_segments = [$current_folder, $upload_subfolder_name, basename($original_filename)];
       
       // The DIRECTORY_SEPARATOR constant is OS specific.
       return join(DIRECTORY_SEPARATOR, $path_segments);
    }

    // file_is_an_image() - Checks the mime-type & extension of the uploaded file for "image-ness".
    function file_is_an_image($temporary_path, $new_path) {
        $allowed_mime_types      = ['image/gif', 'image/jpeg', 'image/png'];
        $allowed_file_extensions = ['gif', 'jpg', 'jpeg', 'png'];
        
        $actual_file_extension   = pathinfo($new_path, PATHINFO_EXTENSION);
        $actual_mime_type        = getimagesize($temporary_path)['mime'];
        
        $file_extension_is_valid = in_array($actual_file_extension, $allowed_file_extensions);
        $mime_type_is_valid      = in_array($actual_mime_type, $allowed_mime_types);
        
        return $file_extension_is_valid && $mime_type_is_valid;
    }
    
    $file_upload_detected = isset($_FILES['file']) && ($_FILES['file']['error'] === 0);
    
    $upload_error_detected = isset($_FILES['file']) && ($_FILES['file']['error'] > 0);

    if ($file_upload_detected) { 
        $image_filename        = $_FILES['file']['name'];
        $temporary_image_path  = $_FILES['file']['tmp_name'];
        $new_image_path        = file_upload_path($image_filename);
        $allowed_file_mime_types = ['application/pdf'];
        $allowed_image_mime_types = ['image/gif', 'image/jpeg', 'image/png'];
        $originalname = basename($image_filename);
        $databaselocation = 'uploads'. '/' . $image_filename;
          
       if(in_array(mime_content_type($_FILES['file']['tmp_name']),$allowed_image_mime_types)){                   
            if(file_is_an_image($temporary_image_path, $new_image_path)){             
                $imagemedium = new ImageResize($temporary_image_path);
                $imagemedium->resize(600, 2000);
                $imagemedium->save($new_image_path); 
                
               if(isset($_POST['imagePath'])){     
                    $imageQuery = "SELECT imageID FROM Images WHERE imagePath = :imagePath" ;
                    $imageStatement = $db->prepare($imageQuery);
               
                    $imageStatement->bindValue(":imagePath", $_POST['imagePath']);
                
                    $imageStatement ->execute();
                    $row = $imageStatement->fetch();
                
                    if($imageStatement->rowCount() == 1){
                        $imageQuery = "UPDATE images SET imagePath = :imagePath, imageName = :image 
                        WHERE imageID = :imageID" ;
                        $imageStatement = $db->prepare($imageQuery);
                        $imageStatement->bindValue(":imagePath", $databaselocation );
                        $imageStatement->bindValue(":imageID", $row['imageID']);
                        $imageStatement->bindValue(":image", $originalname);
                        $imageStatement ->execute();
                        echo('image replaced');
                
                     }else{
                        $imageQuery = "INSERT INTO images (imagePath, imageName) 
                        VALUES(:imagePath, :imageName)" ;
                        $imageStatement = $db->prepare($imageQuery);
                        $imageStatement->bindValue(":imagePath", $databaselocation );
                        $imageStatement->bindValue(":imageName", $originalname);
                        $imageStatement ->execute();

                        $gamesQuery = "SELECT imageID  FROM images  
                        WHERE imageID = (SELECT max(imageID) FROM images)";
                        $gameStatement = $db->prepare($gamesQuery);
                        $gameStatement ->execute();      
                        $imageRow = $gameStatement->fetch();

                        $gameID = filter_input(INPUT_POST, 'gameID', FILTER_SANITIZE_FULL_SPECIAL_CHARS);    
                              
                        //insert the values into the games table along with the new image id from the last query.
                        $updateQuery = "UPDATE Games SET imageID = :imageID 
                        WHERE gameID = :gameID";      
                        
                        $updateStatement = $db->prepare($updateQuery);

                        //bind the value to the placeholder in the query.
                        $updateStatement->bindValue(":gameID", $gameID);
                        $updateStatement->bindValue(":imageID", $imageRow['imageID']);
                        //execute the query.
                        $updateStatement->execute();

                     }
               } else{
                    echo('this condition');
               }   
            }
       }else{
           echo('not an image');
       }
    }else{
        echo('no upload detected');
    }  



//deletes the image if the checkbox is selected.
if(isset($_POST['image'])){
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

//updates the game description and information.
if ($_POST && !empty($_POST['gameName']) && !empty($_POST['gameDescription'])) {
        
    //Sanitize the inputs from the creation form.
    $gameName = filter_input(INPUT_POST, 'gameName', FILTER_SANITIZE_FULL_SPECIAL_CHARS);    
    $description = filter_input(INPUT_POST, 'gameDescription', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $score = filter_input(INPUT_POST, 'score', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $gameID =  filter_input(INPUT_POST, 'gameID', FILTER_SANITIZE_FULL_SPECIAL_CHARS);   
        
    //insert the values into the games table along with the new image id from the last query.
    $updateQuery = "UPDATE Games SET gameName = :gameName, gameDescription = :gameDescription, reviewScore = :score
     WHERE gameID = :gameID ";      
    
    $updateStatement = $db->prepare($updateQuery);

    //bind the value to the placeholder in the query.
    $updateStatement->bindValue(":gameName", $gameName);
    $updateStatement->bindValue(":gameDescription", $description);
    $updateStatement->bindValue(":score", $score); 
    $updateStatement->bindValue(":gameID", $gameID);
    //$updateStatement->bindValue(":imageID", $imageRow['imageID']);

    //execute the query.
    $updateStatement->execute();
    header("location: index.php");
}






























?>