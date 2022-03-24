<?php

require "database_Connect.php";
 session_start();

$username = "";
$password = "";
$passwordErrorFlag = false;
$userNameErrorFlag = false;
 
//checks to see if the username and password has been set.
if(isset($_POST['username']) && isset($_POST['password'])){
    echo('  paswsword and username set  ');
    if(empty($_POST["username"])){
        echo('  username required  ');
        $userNameErrorFlag = true;
    
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', $_POST["username"])){
        echo('  username cannot contain special chracters  ');
        $userNameErrorFlag = true;
    
    } else {
        
        $query = "SELECT userID FROM users WHERE userName = :username";
        if($statement = $db->prepare($query)){
            $sanitizedUsername = $_POST["username"];
            $statement->bindValue(":username", $sanitizedUsername, PDO::PARAM_STR);
            
            
            //if the row count is greater than zero that user exists already.
            if($statement->execute()){
                if($statement->rowCount() == 1){
                    echo("This username already exists.");
                    exit;
                
                } else{
                    $username = $_POST["username"];
                }

            } else {
                echo "There was an error processing your request.";
            }

            unset($statement);
        }
    }
    
    // handles the password validation. Passwords must be 8 characters or more.
    if(isset($_POST["password"])){     
        if(strlen($_POST["password"])> 8){
            $password = $_POST["password"];
        }else{
            echo('password must be more than 8 characters');
            $passwordErrorFlag = true;
        }       
    }
    
    

    if(!$userNameErrorFlag && !$passwordErrorFlag){
        
        $query = "INSERT INTO users (userName, password, isAdmin) VALUES (:username, :password, :isAdmin)";
         
        if($statement = $db->prepare($query)){
            $statement->bindParam(":username", $sanitizedUsername, PDO::PARAM_STR);
            $statement->bindParam(":password", $sanitizedPassword, PDO::PARAM_STR);
            $statement->bindParam(":isAdmin", $isAdmin, PDO::PARAM_INT);
            
            $isAdmin = true;
            $sanitizedUsername = $username;
            $sanitizedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            if($statement->execute()){
                echo("inserted into table");
                $query = "SELECT userID, isAdmin FROM users WHERE userName = :username";
                    
                if($statement = $db->prepare($query)){
                    echo('statement exceuted');
                    $sanitizedUsername = $_POST["username"];
                    $statement->bindValue(":username", $_POST["username"], PDO::PARAM_STR);
                        
                    if($statement->execute()){
                        $row = $statement->fetch();
                    
                        $_SESSION['id']= $row['userID'];
                        $_SESSION['username']=$sanitizedUsername;
                        $_SESSION['loggedin']= true;
                        $_SESSION['isAdmin'] = $row['isAdmin'];

                        echo($row['userID']);
                        echo($_POST["username"]);
                        echo($row['isAdmin']);



                    }
                    
                    
                    
                }



                //header("location: index.php");
            } else{
                echo "There was an error processing your request.";
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
          <a href="LoginPage.php?" class="nav-item nav-link active">login</a>
        <?php endif ?>
</nav>


<form action="SignupPage.php" method="post"> 
    
    <fieldset>
      <legend>Sign Up</legend> 
            
            <p>
            <label for="username">User Name</label>
            <input name="username" id="username" />
            </p>
            
            <p>
            <label for="password">Password</label>
            <input name="password" id="password" />
            </p>
        
 
           <p>
            <input type="submit" name="command" value="Register" class="btn btn-primary" />
            </p>
            
            <p>Already have and Account? <a href="LoginPage.php">Login here</a> </P>
            
    </fieldset>
  </form>
</body>
</html>