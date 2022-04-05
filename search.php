<?php 
session_start();

require('db_connect.php');
     
     $query = "SELECT games.gameID, games.gameDescription, games.gameName, Images.imageID, images.imagePath 
     FROM games 
     LEFT OUTER JOIN Images ON Images.imageID = games.imageID 
     WHERE games.gameName LIKE :search
     ORDER BY gameName ASC;";

     $statement = $db->prepare($query);

     $search = filter_input(INPUT_POST, 'search', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
     $pattern = '%' . $search . '%';
     $statement->bindValue('search', $pattern, PDO::PARAM_STR);
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
    <h4>roleLevel: <?=$_SESSION['roleLevel'] ?></h4>
  <?php endif ?>

  <nav class="nav nav-pills nav-fill">  
    <a href="index.php"  class="nav-item nav-link active"> Home</a>      
    <?php if($_SESSION['roleLevel']< 3):?>
    <a href="create.php" class="nav-item nav-link" >New Review</a>
    <?php endif ?> 
    
    <?php if($_SESSION['roleLevel'] == 0):?>
    <a href="users.php" class="nav-item nav-link" >Manage Users</a>
    <?php endif ?> 
      <?php if($_SESSION['loggedin']== 1):?>           
        <a href="logout.php"class="nav-item nav-link" >Sign out</a>  
      <?php endif ?>

      <?php if($_SESSION['loggedin']== 0):?>
          <a href="LoginPage.php?" class="nav-item nav-link">Sign in</a>
        <?php endif ?>
        <form action="search.php" method="POST">
      <div class="input-group">
      <input type="text" placeholder="Search.." name="search" class="form-control">
      <button type="submit" class="btn btn-secondary">Submit</button>
      </div>
</nav>
 
<h1><?= $statement->rowCount() ?> Games found</h1>
  

 <div class="row">
  <?php while($row = $statement->fetch()): ?>
    <div class ="card" style="width: 350px";> 
      <div class="card-body text-center">
        <?php if(!$row['imageID'] == 0): ?>
          <img src="<?=$row['imagePath']?>" class="card-img-top" alt="Game picture here">
          <?php endif ?>

        <h5><a href="show.php?id=<?=$row['gameID'] ?>"><?= $row['gameName'] ?></a></h5>
        <p class="card-text"><?=$row['gameDescription'] ?></p>
        <a href="show.php?id=<?=$row['gameID'] ?>" class="btn btn-primary">View Game</a>        
      </div>
    </div>
  <?php endwhile ?> 
 </div>
     
  
  



</body>
</html>