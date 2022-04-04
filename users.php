<?php 

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
    <title>Users</title>
</head>
<body>
    <?php while($row = $statement->fetch()): ?>
     <div class="card" style="width: 300px;">
        <div class="card-title">
            <h2><?=$row['userID']?></h2>
            <h2><?=$row['userName']?></h2>
       </div> 
     </div>  
        
        
    <?php endwhile?>
</body>
</html>