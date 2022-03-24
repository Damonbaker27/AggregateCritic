<?php
session_start();

	require('db_connect.php');

  
    $_SESSION['gameID']= filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
	
    
    
    $query = "SELECT Games.gameName, Games.gameDescription, games.reviewScore FROM Games  
      WHERE games.gameid = :id";
       
    $statement = $db->prepare($query);	
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    $statement->bindValue('id', $id, PDO::PARAM_INT);
    $statement->execute();
	  $row = $statement->fetch();

  
    $commentquery = "SELECT Reviews.reviewContent, reviews.reviewID, Users.UserName, Users.userID 
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
    <a href="create.php" class="nav-item nav-link" >New Review</a>
      
      <?php if($_SESSION['loggedin']== 1):?>           
        <a href="logout.php"class="nav-item nav-link" >Logout</a>  
      <?php endif ?>

      <?php if($_SESSION['loggedin']== 0):?>
          <a href="LoginPage.php?" class="nav-item nav-link">login</a>
        <?php endif ?>
</nav>   

<div>
        <div id="header">
            <h1><?=$row['gameName'] ?></h1>
        </div> 
 
  

  <div class="card" style="max-width: 500px";>
    <div class="row g-0" >
      <div class="col-sm-5">
        <img src="boxart.png" class="card-img-top h-100" alt="Game Box art">
        
      </div>
      <div class="col-sm-7">
        <div class="card-body">
          <h5 class="card-title"><?=$row['gameName']?> Information</h5>
          <p class="card-text"><?= $row['gameDescription'] ?></p>
        </div>
      </div>
    </div>  
  </div>    
    
    <?php while($commentRow = $commentStatement->fetch()): ?> 
      
    <div class="card"> 
      
      <div class="card-body">
        <h4 class="card-title"><?=$commentRow['UserName'] ?></h4>
        <h5 class="card-subtitle mb-3 text-muted"><?= $commentRow['reviewContent'] ?></h5>
        <?php if($_SESSION['isAdmin']== 1 || $_SESSION['loggedin']== 1):?>      
          <?php if($_SESSION['id']== $commentRow['userID']) :?>
        
          <small><a href="edit.php?reviewid=<?=$commentRow['reviewID']?>
          &userid=<?=$commentRow['userID']?>
          &username=<?=$commentRow['UserName']?>"> Edit</a></small>
        
        
          <?php endif?>
        <?php endif?>       
      </div>
    
    
    
    
      <?php endwhile ?>
    
  <div>
 <?php if($_SESSION['loggedin']== 1):?>
  
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
<?php endif ?>
<?php if($_SESSION['loggedin']== 0):?>

  <h2><a href="LoginPage.php">login to post comment</a></h2>


<?php endif ?>



  </div> 
  

      


  
  </div>
  </div>
        
    </div> 








  </body>
</html>
