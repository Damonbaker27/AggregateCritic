<div class='blog_content'>
      
      <?php if(strlen($row['gamedescription']) > 200):  ?>

      <?php  $substr = substr($row['gamedescription'], 0, 100).'...'; ?>

      <?= $substr ?>
      <a href="show.php?id=<?=$row['gameiD'] ?>">Read more</a>
      
      <?php else: ?>

      <?= $row['gamedescription'] ?>

      <?php endif ?>




      <p>
        <small>
         
        <?php $newDate = date("F d, Y, g:i a",strtotime($row['date']));?>

         <?= $newDate . ' -' ?>
          <a href="edit.php?id=<?=$row['gameID'] ?>">edit</a>
        </small>
      </p>



      $query = "SELECT Games.gameName, Games.gameDescription, games.reviewScore, Reviews.reviewContent, reviews.reviewID, Users.UserName FROM Games 
      JOIN bridgeTable ON games.gameID=bridgetable.gameID
      JOIN Reviews ON BridgeTable.reviewID=Reviews.reviewID
      JOIN Users ON reviews.userID=Users.userID 
      WHERE games.gameid = :id";
       
    $statement = $db->prepare($query);	
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    $statement->bindValue('id', $id, PDO::PARAM_INT);
    $statement->execute();
	  $row = $statement->fetch();
    
 
    

   
if($statement->rowCount() == 0){
    
    $noComments = true;
    $query = "SELECT Games.gameName, Games.gameDescription, games.reviewScore FROM Games  
      WHERE games.gameid = :id";
       
    $statement = $db->prepare($query);	
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    $statement->bindValue('id', $id, PDO::PARAM_INT);
    $statement->execute();
	  $row = $statement->fetch();

    
   }






















