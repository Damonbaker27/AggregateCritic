<?php
session_start();

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
    <a href="create.php" class="nav-item nav-link active" >New Review</a>
      
      <?php if($_SESSION['loggedin']== 1):?>           
        <a href="logout.php"class="nav-item nav-link" >Sign out</a>  
      <?php endif ?>

      <?php if($_SESSION['loggedin']== 0):?>
          <a href="LoginPage.php?" class="nav-item nav-link">Sign in</a>
        <?php endif ?>
</nav> 
    
<div class="form-group">
<form action="create_post.php" method="post" enctype='multipart/form-data'>           
    <fieldset>
    <legend>Add a New Game</legend>           
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
         <!--<input type='submit' name='submit' value='Upload Image'>-->
            
        <div class="px-3">
            <input type="submit" name="command" value="Create" class="btn btn-primary" />
        </div>

    </fieldset>
</form>

                    
    </div>
       
    </div> 
</body>
</html>
