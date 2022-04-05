<?php

require "database_Connect.php";
 session_start();

$username = "";
$password = "";
$passwordErrorFlag = false;
$userNameErrorFlag = false;
$confirmPasswordFlag = false;
$passwordError ='';
$userNameError='';
$confirmPasswordError ='';
    //echo($_POST['confirmPassword']);
if(isset($_POST['submit'])){
    //checks to see if the username and password has been set.
    if(isset($_POST['username'])){
        if(empty($_POST["username"])){
           //echo('username required');
            $userNameError='username required';
            $userNameErrorFlag = true;
        
        }elseif(!preg_match('/^[a-zA-Z0-9_]+$/', $_POST["username"])){
            //echo('username cannot contain special characters');
            $userNameError='username cannot contain special characters';
            $userNameErrorFlag = true;
        
        }else{          
            $query = "SELECT userID FROM users WHERE userName = :username";
            if($statement = $db->prepare($query)){
                $sanitizedUsername = $_POST["username"];
                $statement->bindValue(":username", $sanitizedUsername, PDO::PARAM_STR);
                   
                //if the row count is greater than zero that user exists already.
                if($statement->execute()){
                    if($statement->rowCount() == 1){
                        $userNameErrorFlag= true;
                        $userNameError="This username already exists.";
                    
                    } else{
                        $username = $_POST["username"];
                    }

                } else {
                    $userNameErrorFlag= true;
                    echo "There was an error processing your request.";
                }

                unset($statement);
            }
        }    
    }else{
        $userNameError='username required';
        $userNameErrorFlag = true;
    }    
    
    // handles the password validation. Passwords must be 8 characters or more.
    if(isset($_POST["password"])){
    if(empty(trim($_POST["password"]))){
        $passwordError ='password required';
        $passwordErrorFlag = true;
    }elseif(strlen($_POST["password"])<= 8){
        $passwordError ='password must have 8 or more characters';
        $passwordErrorFlag = true;
    }else{
        $password = trim($_POST["password"]);
    }
    }

    if(isset($_POST["confirmPassword"])){
    //check if the confirm password field matches the password field.
    if(empty(trim($_POST["confirmPassword"]))){
        $confirmPasswordError='this field is required';
        $confirmPasswordFlag = true;
    }else{
        if(trim($_POST["confirmPassword"]) != $password){
            $confirmPasswordError = 'Passwords do not match';
            $confirmPasswordFlag = true;
        }
    }

   
    

    if(!$userNameErrorFlag && !$passwordErrorFlag && !$confirmPasswordFlag){
        
        $query = "INSERT INTO users (userName, password, roleLevel) VALUES (:username, :password, :roleLevel)";
         
        if($statement = $db->prepare($query)){
            $statement->bindParam(":username", $sanitizedUsername, PDO::PARAM_STR);
            $statement->bindParam(":password", $sanitizedPassword, PDO::PARAM_STR);
            $statement->bindParam(":roleLevel", $roleLevel, PDO::PARAM_INT);
            
            $roleLevel = 1;
            $sanitizedUsername = $username;
            $sanitizedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            if($statement->execute()){
                //echo("inserted into table");
                $query = "SELECT userID, roleLevel FROM users WHERE userName = :username";
                    
                if($statement = $db->prepare($query)){
                   // echo('statement exceuted');
                    $sanitizedUsername = $_POST["username"];
                    $statement->bindValue(":username", $_POST["username"], PDO::PARAM_STR);
                        
                    if($statement->execute()){
                        $row = $statement->fetch();
                    
                        $_SESSION['id']= $row['userID'];
                        $_SESSION['username']=$sanitizedUsername;
                        $_SESSION['loggedin']= true;
                        $_SESSION['roleLevel'] = $row['roleLevel'];

                        header("location: index.php");
                    }                  
                }
            } else{
                echo "There was an error processing your request.";
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
        <a href="logout.php"class="nav-item nav-link" >Logout</a>  
      <?php endif ?>

      <?php if($_SESSION['loggedin']== 0):?>
          <a href="LoginPage.php?" class="nav-item nav-link active">Sign in</a>
        <?php endif ?>
</nav>


<form action="SignupPage.php" method="post">    
    <fieldset>
      <legend>Sign Up</legend> 
    <div class="wrapper">      
      <div class="form-group">       
            <label for="username">User Name</label>
            <input name="username" id="username" class="form-control" />
            <?php if($userNameErrorFlag):?>
                <div class="alert alert-danger" role="alert">
                    <?=$userNameError?>
                </div>
            <?php endif ?>          
      </div>    
      <div class="form-group">            
            <label for="password">Password</label>
            <input name="password" id="password" type="password" class="form-control" />
            <?php if($passwordErrorFlag):?>
                <div class="alert alert-danger" role="alert">
                    <?=$passwordError?>
                </div>
            <?php endif ?>      
      </div>  
      <div class="form-group">   
            <label for="confirmPassword">Confirm Password</label>
            <input name="confirmPassword" id="confirmPassword" type="password" class="form-control" />
            <?php if($confirmPasswordFlag):?>
                <div class="alert alert-danger" role="alert">
                    <?=$confirmPasswordError?>
                </div>
            <?php endif ?>   
      </div>
 
      <div class="form-group">  
      <p>
            <input type="submit" name="submit" value="Register" class="btn btn-primary" />
            </p>
      </div>    
            <p>Already have and Account? <a href="LoginPage.php">Sign in here</a> </P>
    </div>        
    </fieldset>
  </form>
</body>
</html>