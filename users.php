<?php 
session_start();
require('db_connect.php');
     
     $query = "SELECT * FROM users ORDER BY userID ASC";

     $statement = $db->prepare($query);

     $statement->execute(); 



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Users</title>
</head>
<body>
<div>
<h1><a href="index.php"> Aggregate Critic</a></h1>
</div>
  

<nav class="nav nav-pills nav-fill">  
    <a href="index.php"  class="nav-item nav-link "> Home</a>      
    <?php if($_SESSION['roleLevel']< 3):?>
    <a href="create.php" class="nav-item nav-link" >New Review</a>
    <?php endif ?> 
    
    <?php if($_SESSION['roleLevel'] == 0):?>
    <a href="users.php" class="nav-item nav-link active " >Manage Users</a>
    <?php endif ?> 
      <?php if($_SESSION['loggedin']== 1):?>           
        <a href="logout.php"class="nav-item nav-link" onclick="return confirm('Are you sure you would like to sign out?')" >Sign out</a>  
      <?php endif ?>

      <?php if($_SESSION['loggedin']== 0):?>
          <a href="LoginPage.php?" class="nav-item nav-link">Sign in</a>
        <?php endif ?>


    </form>
</nav>
<div class="wrapper">
    <table class="table table-striped">
    <thead>
        <tr>
            <th>userID</th>
            <th>User Name</th>
            <th>Privilege Level</th>
        </tr>
    </thead>
    <tbody>
    <?php while($row = $statement->fetch()): ?>
        <tr>
            <td><a href="editUsers.php?userID=<?=$row['userID']?>"><?=$row['userID']?></a></td>
            <td><?=$row['userName']?></td>
        
        
        
        <?php if($row['roleLevel']== 0):?>
            <td>Admin</td>       
        <?php elseif($row['roleLevel']== 1): ?>
            <td>Regular user</td>                
         <?php endif ?>
        
    
    
    
    </tr>
    <?php endwhile?>                    
    </tbody>
    </table> 
</div>      

</body>
</html>