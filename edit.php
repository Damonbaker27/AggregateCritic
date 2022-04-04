<?php

	session_start();

	require('db_connect.php');


	$reviewid = filter_input(INPUT_GET, 'reviewid', FILTER_SANITIZE_NUMBER_INT);
  $userid = filter_input(INPUT_GET, 'userid', FILTER_SANITIZE_NUMBER_INT);
  $username = filter_input(INPUT_GET, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);


	$query = "SELECT * FROM Reviews WHERE reviewID = :reviewid AND userID = :userID LIMIT 1";
    
    if($statement = $db->prepare($query)){
      $statement->bindValue('reviewid', $reviewid, PDO::PARAM_INT);
      $statement->bindValue('userID', $userid, PDO::PARAM_INT);
    
      if($statement->execute()){
        $row = $statement->fetch();
      }

	    
    }
    
    



?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>edit</title>
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



<div > 


<div >
<form method="post">
    <fieldset>
      <legend>Edit user Comment</legend>
      <p>
        
        <h3><?=$username?></h3>
      </p>
      <p>
        <label for="reviewcontent">Content</label>
        <textarea name="reviewcontent" id="reviewcontent"><?=$row['reviewContent'] ?>

</textarea>
      </p>
      <p>
        <input type="hidden" name="reviewid" value="<?=$reviewid?>" />
        <input type="hidden" name="userid" value="<?=$userid?>" />
        <input type="submit" name="command" value="Update" formaction="update_post.php" />
        <input type="submit" name="command" value="Delete" formaction="delete_post.php" onclick="return confirm('Are you sure you wish to delete this post?')" />
      </p>
    </fieldset>
</form>

</body>
</html>