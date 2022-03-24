<?php
  session_start();
   
  if(!isset($_SESSION['loggedin'])){
    $_SESSION['loggedin'] = 0;
  }

  if(!isset($_SESSION['isAdmin'])){
    $_SESSION['isAdmin']=0;
  }
    
  
  require('db_connect.php');
     
     $query = "SELECT * FROM games ORDER BY gameName ASC";

     $statement = $db->prepare($query);

     $statement->execute(); 

     
     


?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Aggregate Critic</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div id="wrapper">
  <div id="header">
    <h1><a href="index.php"> Aggregate Critic</a></h1>
  </div> 
  
  <?php if($_SESSION['loggedin']== 1):?>
    <h4>Name: <?=$_SESSION['username'] ?></h4>
    <h4>userID: <?=$_SESSION["id"]?></h4>
    <h4>logged in: <?=$_SESSION['loggedin'] ?></h4>
    <h4>Admin?: <?=$_SESSION['isAdmin'] ?></h4>
  <?php endif ?>

  <nav class="nav nav-pills nav-fill">  
    <a href="index.php"  class="nav-item nav-link active"> Home</a>      
    <a href="create.php" class="nav-item nav-link" >New Review</a>
      
      <?php if($_SESSION['loggedin']== 1):?>           
        <a href="logout.php"class="nav-item nav-link" >Sign out</a>  
      <?php endif ?>

      <?php if($_SESSION['loggedin']== 0):?>
          <a href="LoginPage.php?" class="nav-item nav-link">Sign in</a>
        <?php endif ?>
</nav>
 
  
  
  
  

  <h1>There are over <?= $statement->rowCount()-1 ?> Games reviewed!</h1>
  

 <div class="row">
  <?php while($row = $statement->fetch()): ?>
    <div class ="card" style="width: 350px";> 
      <div class="card-body text-center">
        <img src="boxart.png" class="card-img-top" alt="Game picture here">
        <h5><a href="show.php?id=<?=$row['gameID'] ?>"><?= $row['gameName'] ?></a></h5>
        <p class="card-text"><?=$row['gameDescription'] ?></p>
        <a href="show.php?id=<?=$row['gameID'] ?>" class="btn btn-primary">View Game</a>        
      </div>
    </div>
  <?php endwhile ?> 
 </div>
     
  
  



</body>
</html>
