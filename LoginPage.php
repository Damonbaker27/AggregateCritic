<?php 
session_start();

require "database_Connect.php";
 

$username = "";
$password = "";
$passwordErrorFlag = false;
$userNameErrorFlag = false;

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"]===true){
  header("location: index.php");
  exit;
}

if(isset($_POST['submit'])){
  echo('hello');

  // Check if username is empty. 
  if(!empty($_POST['username'])){  
      $username = trim($_POST["username"]);   
  }else{
    echo("username is required.");
      $userNameErrorFlag = true;
  }
    
  
  //check if the password is set
  if(!empty($_POST["password"])){
    $password = trim($_POST["password"]);
    echo('     password set    ');
    //echo($password);
  }else {
    $passwordErrorFlag=true;
    echo('    password is required    ');
  }

  //checks if there are any empty fields
  if(!$userNameErrorFlag && !$passwordErrorFlag){
    echo('no errors');
    $query = "SELECT userID, userName, password, roleLevel FROM users WHERE userName = :username";

    if($statement = $db->prepare($query)){
      echo('statement prepared');
      $sanitizedUsername = $_POST["username"];
      $statement->bindValue(":username", $sanitizedUsername, PDO::PARAM_STR);
      
    if($statement->execute()){
        echo('statment exceuted');
        if($statement->rowCount() == 1){
          echo('user exists        ');
          
          if($row = $statement->fetch()){
            
            echo("statement fetching");
            $id = $row["userID"];
            $userName = $row["userName"];
            $hashedPassword = $row["password"];             
            echo($row['password']);
            echo($password);
            echo($userName);
              //verifies the password matches database. 
            if(password_verify($password, $hashedPassword)){                
                echo('      updating session keys     ');
                //session_start();
                $_SESSION["username"]= $username;
                $_SESSION["id"] = $id;
                $_SESSION["loggedin"] = true;
                $_SESSION['roleLevel']= $row['roleLevel'];

                
                header("location: index.php");

              }else {
                $passwordErrorFlag=true;
                echo("error password did not match");
              }
          }else{
            $passwordErrorFlag=true;
          }  
        }else{
          $passwordErrorFlag=true;
          echo('no user found');
        }
      } 
    }
  }
 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{ width: 360px; padding: 20px; }
    </style>
</head>
<body>
<div id="wrapper">
  <div id="header">
    <h1><a href="index.php"> Aggregate Critic</a></h1>
</div>


<nav class="nav nav-pills nav-fill">  
    <a href="index.php"  class="nav-item nav-link"> Home</a>      
    <?php if($_SESSION['roleLevel']== 1):?>
    <a href="create.php" class="nav-item nav-link" >New Review</a>
    <?php endif ?>
      
      <?php if($_SESSION['loggedin']== 1):?>           
        <a href="logout.php"class="nav-item nav-link" >Sign out</a>  
      <?php endif ?>

      <?php if($_SESSION['loggedin']== 0):?>
          <a href="LoginPage.php?" class="nav-item nav-link active">Sign in</a>
        <?php endif ?>
</nav>



<div class="wrapper">
<form action="LoginPage.php" method="post"> 
    <fieldset>             
      
      <div class="form-group">
        <label class="form-label" for="username">User Name</label>
        <input class="form-control" name="username" id="username" placeholder="User Name" />
      </div>

      <div class="form-group">
        <label class="form-label" for="password">Password</label>
        <input class="form-control" name="password" id="password" type="password" placeholder="Password" />
      </div>

      <?php if($passwordErrorFlag || $userNameErrorFlag):?>
        <div class="alert alert-danger" role="alert">
          <p>username or password incorrect.</p>
        </div>
      <?php endif ?>
      
      
      
      <div class="form-group">
        <input type="submit" name="submit" value="Sign in" class="btn btn-primary" />
        <div >
        <small>No Account? <a href="SignupPage.php">Create one </small>
        </div>
        
      </div>
          
            
    </fieldset>
  </form>
</div>
</body>
</html>