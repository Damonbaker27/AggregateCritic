<?php 

session_start();

if(isset($_SESSION["id"]) || isset($_SESSION['username']) ){
    $_SESSION =[];
    $_SESSION['loggedin']= false;
}else{
    echo("not logged in");
    echo($_SESSION['username']);
    echo($_SESSION['id']);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h2>You have successfully logged out.</h2>
    <h4><a href="index.php?">Return Home</a></h4>
</body>
</html>