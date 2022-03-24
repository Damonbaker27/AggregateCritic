<?php
session_start();

require 'C:\xampp\htdocs\wd2\Challenges\Challenge 7\php-image-resize-master\lib\ImageResize.php';
require 'C:\xampp\htdocs\wd2\Challenges\Challenge 7\php-image-resize-master\lib\ImageResizeException.php';
use \Gumlet\ImageResize;
    require 'authenticate.php';

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
    
    function File_Is_Other($temporary_path, $new_path){
       $allowed_mime_types = ['application/pdf'];
       $allowed_file_extensions = ['pdf'];

       $actual_mime_type= mime_content_type($temporary_path);
       echo($actual_mime_type);
       $actual_file_extension   = pathinfo($new_path, PATHINFO_EXTENSION);
       
       $mime_type_is_valid      = in_array($actual_mime_type, $allowed_mime_types);
       $file_extension_is_valid = in_array($actual_file_extension, $allowed_file_extensions);

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
        
        
        
       if(in_array(mime_content_type($_FILES['file']['tmp_name']),$allowed_file_mime_types)){    
           if(File_Is_Other($temporary_image_path, $new_image_path)){     
               move_uploaded_file($temporary_image_path, $new_image_path);           
           }        
        }elseif(in_array(mime_content_type($_FILES['file']['tmp_name']),$allowed_image_mime_types)){           
            
            if(file_is_an_image($temporary_image_path, $new_image_path)){     
                
                $imagemedium = new ImageResize($temporary_image_path);
                $imagemedium->resizeToWidth(400);
                $imagemedium->save($new_image_path.'_medium_'.$originalname); 

                $imagethumb = new ImageResize($temporary_image_path);
                $imagethumb->resizeToWidth(50);
                $imagemedium->save($new_image_path.'_thumb_'.$originalname);
                

                //move the new image to the upload folder.
                move_uploaded_file($temporary_image_path, $new_image_path);
                
                
                if(isset($_POST['gameName']) && isset($_POST['gameDescription'])){
                    //insert the new image path into database.
                    $imageQuery = "INSERT INTO images (imagePath, imageName) VALUES (:imagePath, :image)" ;

                    $imageStatement = $db->prepare($imageQuery);
                    $imageStatement->bindValue(":imagePath", $new_image_path);
                    $imageStatement->bindValue(":image", $originalname);
                    $imageStatement ->execute();
            
                    //get the last imageID added to database. I know this is stupid.
                    $gamesQuery = "SELECT imageID  FROM images  
                    WHERE imageID = (SELECT max(imageID) FROM images)";
                    $gameStatement = $db->prepare($gamesQuery);
                    $gameStatement ->execute();      
                    $imageRow = $gameStatement->fetch();
                    
                    
                    //Sanitize the inputs from the creation form.
                    $gameName = filter_input(INPUT_POST, 'gameName', FILTER_SANITIZE_FULL_SPECIAL_CHARS);    
                    $description = filter_input(INPUT_POST, 'gameDescription', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                    $score = filter_input(INPUT_POST, 'score', FILTER_SANITIZE_FULL_SPECIAL_CHARS);    
                        
                    //insert the values into the games table along with the new image id from the last query.
                    $updateQuery = "INSERT INTO games (gameName, gameDescription, reviewScore, imageID) 
                        VALUES (:gameName, :gameDescription, :score, :imageID)";      
                    
                    $updateStatement = $db->prepare($updateQuery);
            
                    //bind the value to the placeholder in the query.
                    $updateStatement->bindValue(":gameName", $gameName);
                    $updateStatement->bindValue(":gameDescription", $description);
                    $updateStatement->bindValue(":score", $score); 
                    $updateStatement->bindValue(":imageID", $imageRow['imageID']);

                    //execute the query.
                    $updateStatement->execute();

                    header("location: index.php");
                    
                }   
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