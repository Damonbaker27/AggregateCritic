<?php

session_start();

	require('db_connect.php');


	
    
	
    
    //query the database for the game passed through the get.
    $query = "SELECT Games.gameName, games.gameID, Games.gameDescription, games.reviewScore, games.imageID, images.imagePath FROM Games
    JOIN Images ON images.imageID = games.imageID  
      WHERE games.gameid = :id";
       
    $statement = $db->prepare($query);	
    $gameID = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    $statement->bindValue('id', $gameID, PDO::PARAM_INT);
    $statement->execute();
	$row = $statement->fetch();
        
        

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
<form action="update_game.php" method="post" enctype='multipart/form-data'>           
    <fieldset>
    <legend>Edit Game</legend>           
    <?php if(!empty($row['imagePath'])): ?>
    <div class="col-sm-5">
        <img src="<?=$row['imagePath'] ?>" class="card-img-top h-100">     
    </div>
    <?php endif ?>   
        
    <input type="hidden" id="imagePath" name="imagePath" value="<?=$row['imagePath']?>">
        <input type="hidden" id="imageID" name="imageID" value="<?=$row['imageID']?>">
        <input type="hidden" id="gameID" name="gameID" value="<?=$row['gameID']?>">
    
    
        <div class="px-3" >
            <label for="gameName">Game Name</label>
            <input name="gameName" id="gameName" class="form-control" value="<?=$row['gameName']?>" />
        </div> 
            
        <div class="px-3">
            <label for="score">Aggregate Critic Score</label>
            <input name="score" id="score"  class="form-control" value="<?=$row['reviewScore']?>" />
        </div>
            
        <div class="px-3">
            <label for="gameDescription">Game Description</label>
            <textarea name="gameDescription" id="gameDescription" class="form-control"><?=$row['gameDescription']?></textarea>
        </div>
        
        <label for='image'>Image Filename:</label>
         <input type='file' name='file' id='image'>
         
        
         <?php if(!empty($row['imagePath'])): ?>
        <div class="px-3">
            <input type="checkbox" id="image" name="image" value="yes">
            <label for="image"> Remove image?</label>
        </div>   
        <?php endif ?>
        
        <div class="px-3">
            <input type="submit" name="command" value="Update" class="btn btn-primary" />
        </div>

    </fieldset>
</form>

                    
    </div>
       
    </div> 
</body>
</html>
