<?php

	session_start();

	require('db_connect.php');

    
    $userId = filter_input(INPUT_GET, 'userID', FILTER_SANITIZE_NUMBER_INT);
	$query = "SELECT * FROM users WHERE userID = :userID LIMIT 1";
    
    $statement = $db->prepare($query);
      
    $statement->bindValue(':userID', $userId, PDO::PARAM_INT);
    
    $statement->execute();
    $row = $statement->fetch();


?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Edit users</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

    <h1><a href="index.php"> Aggregate Critic</a></h1>


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

</nav>   
 



<form method="post">
    <fieldset>
<div class="wrapper" >
      <legend>Edit user</legend>
        <div >
        <h3><?=$row['userName']?></h3>
        </div>
        <div class="form-group">
            <label class="form-label" for="userName">userName</label>
            <input class="form-control" name="userName" id="userName" value="<?=$row['userName'] ?>"> 
        </div>    
        
        <div class="form-group">
            <label class="form-label" for="roleLevel">Temporary password</label>
            <input class="form-control" name="tempPass" id="tempPass">
        </div>

        <label class="form-label" for="roleLevel">Privilege level</label>
        <div class="form-group">
            <select class="form-select" name="roleLevel">
                <option selected>Choose...</option>
                <option value="0">Admin</option>
                <option value="1">Regular User</option>
            </select>
        </div>

      
        <div class="form-group">
            <input type="hidden" name="userID" value="<?=$row['userID']?>" />
            <input type="submit" name="command" value="Update" formaction="updateUser.php" />
            <input type="submit" name="command" value="Delete" formaction="deleteUser.php" onclick="return confirm('Are you sure you wish to delete this post?')" />
        </div>
<div class="wrapper" >
    </fieldset>
</form>
</div>
</body>
</html>