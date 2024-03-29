<?php
session_start();

	require('db_connect.php');

  
    $_SESSION['gameID']= filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
	
    
    //query the database for the game passed through the get.
    $query = "SELECT Games.gameName, games.gameID, Games.gameDescription, games.reviewScore, games.imageID FROM Games  
      WHERE games.gameid = :id";
       
    $statement = $db->prepare($query);	
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    $statement->bindValue('id', $id, PDO::PARAM_INT);
    $statement->execute();
	  $row = $statement->fetch();

    //get the comments associated with that game.
    $commentquery = "SELECT Reviews.reviewContent, reviews.reviewID, Users.UserName, Users.userID, reviews.userScore 
    FROM Games 
    JOIN bridgeTable ON games.gameID=bridgetable.gameID
    JOIN Reviews ON BridgeTable.reviewID=Reviews.reviewID
    JOIN Users ON reviews.userID=Users.userID 
    WHERE games.gameid = :id
    ORDER BY reviews.reviewID DESC";
     
    $commentStatement = $db->prepare($commentquery);	 
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    $commentStatement->bindValue('id', $id, PDO::PARAM_INT);
    $commentStatement->execute();

    
    //query the database for the game image
    $imageQuery = "SELECT imagePath FROM images where imageID = :imageID";
    $imageStatement = $db->prepare($imageQuery);
    $imageStatement->bindValue(':imageID', $row['imageID'], PDO::PARAM_INT);
    $imageStatement->execute();
    $imagerow = $imageStatement->fetch();
    


?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Show full post</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div id="wrapper">
  <div id="header">
    <h1><a href="index.php"> Aggregate Critic</a></h1>
</div>


<nav class="nav nav-pills nav-fill">  
    <a href="index.php"  class="nav-item nav-link"> Home</a>      
    <?php if($_SESSION['roleLevel']< 3):?>
    <a href="create.php" class="nav-item nav-link" >New Review</a>
    <?php endif ?>
      
      <?php if($_SESSION['loggedin']== 1):?>           
        <a href="logout.php"class="nav-item nav-link" >Logout</a>  
      <?php endif ?>

      <?php if($_SESSION['loggedin']== 0):?>
          <a href="LoginPage.php?" class="nav-item nav-link">login</a>
        <?php endif ?>
      
      <form action="search.php" method="POST">
        <div class="input-group">
          <input type="text" placeholder="Search.." name="search" class="form-control">
          <button type="submit" class="btn btn-secondary">Submit</button>
        </div>
      </form>
</nav>   

<div>
        <div id="header">
            <h1><?=$row['gameName'] ?></h1>
        </div> 
 
  

  <div class="card" ;>
    <div class="row g-0" >
    <?php if(!empty($row['imageID'])): ?>  
    <div class="col-sm-5">
        <img src="<?=$imagerow['imagePath']?>" class="card-img-top h-100">  
      </div>
      <?php endif ?>
      <div class="col-sm-7">
        <div class="card-body">
          <h5 class="card-title">Game Information</h5>
          <p class="card-text"><?= $row['gameDescription'] ?></p>
          
          <?php if($_SESSION['roleLevel'] <= 1): ?>
          <div>
          <small><a href="editGame.php?id=<?=$row['gameID']?>">edit</a></small>
          </div>
          <?php endif ?>
        </div>
      </div>
    </div>  
  </div>
</div>    
    <?php while($commentRow = $commentStatement->fetch()): ?> 
      
    <div class="card"> 
      
      <div class="card-body">
        <h4 class="card-title"><?=$commentRow['UserName'] ?></h4>
        <h5 class="card-subtitle mb-3 text-muted"><?= $commentRow['userScore'] ?>/10</h5>
        <h5 class="card-subtitle mb-3 text-muted"><?= $commentRow['reviewContent'] ?></h5>
        <?php if($_SESSION['roleLevel'] == 0 && $_SESSION['loggedin']== 1):?>      
          <?php if($_SESSION['id']== $commentRow['userID'] || $_SESSION['roleLevel'] == 0) :?>
        
          <small><a href="edit.php?reviewid=<?=$commentRow['reviewID']?>
          &userid=<?=$commentRow['userID']?>
          &username=<?=$commentRow['UserName']?>"> Edit</a></small>
          
        
          <?php endif?>
        <?php endif?>       
      </div>
    
    
    
    
      <?php endwhile ?>
    
  <div>
 
  
  <form action="post_comment.php" method="post"> 
    <fieldset>
      <legend>New Comment</legend> 
            
            <p>
            <label for="userScore">Your review Score</label>
            <input name="userScore" id="userScore"  class="form-control" />
            </p>
            
            <p>
            <label for="userReview">Game Review</label>
            <textarea name="userReview" id="userReview" class="form-control"></textarea>
            </p>
 
           <p>
            <input type="submit" name="command" value="Post Comment" class="btn btn-primary" />
            </p>          
    </fieldset>
  </form>





  </div> 
  

      


  
  </div>
  </div>
        
    </div> 








  </body>
</html>
