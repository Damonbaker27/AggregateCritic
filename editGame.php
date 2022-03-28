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
    <title>Create post</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div id="wrapper">
  <div id="header">
    <h1><a href="index.php"> Aggregate Critic</a></h1>
</div>


<nav class="nav nav-pills nav-fill">  
    <a href="index.php"  class="nav-item nav-link"> Home</a>      
      
      <?php if($_SESSION['loggedin']== 1):?>           
        <a href="logout.php"class="nav-item nav-link" >Sign out</a>  
      <?php endif ?>

      <?php if($_SESSION['loggedin']== 0):?>
          <a href="LoginPage.php?" class="nav-item nav-link">Sign in</a>
        <?php endif ?>
</nav> 
    
<div class="form-group">
<form action="post" method="post" enctype='multipart/form-data'>           
    <fieldset>
    <legend>Edit Game</legend>           
    <div class="col-sm-5">
        <img src="uploads/boxart.jpg" class="card-img-top h-100">     
    </div>
    
    
    
    
        <div class="px-3" >
            <label for="gameName">Game Name</label>
            <input name="gameName" id="gameName" class="form-control" placeholder="Game Name" />
        </div> 
            
        <div class="px-3">
            <label for="score">Aggregate Critic Score</label>
            <input name="score" id="score"  class="form-control" placeholder="Score" />
        </div>
            
        <div class="px-3">
            <label for="gameDescription">Game Description</label>
            <textarea name="gameDescription" id="gameDescription" class="form-control" placeholder="Description here..."></textarea>
        </div>
        
        <label for='image'>Image Filename:</label>
         <input type='file' name='file' id='image'>
         
        <div class="px-3">
            <input type="checkbox" id="image" name="image" value="Remove image?">
            <label for="image"> Remove image?</label>
        </div>   
        
        <div class="px-3">
            <input type="submit" name="command" value="Update" class="btn btn-primary" />
        </div>

    </fieldset>
</form>

                    
    </div>
       
    </div> 
</body>
</html>
