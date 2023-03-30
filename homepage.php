<?php
session_start();
require("connect.php");
require("infos.php");
include("header2.php");
   


?>
 
<!DOCTYPE html>
<html lang="en">
 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content=
        "width=device-width, initial-scale=1.0">
				<link rel="stylesheet" href="./css/homepage.css">
              
    <title></title>
</head>
 
<body id="body-el">
  
    

  

<div class="publication">
	<?php echo $_SESSION["pseudo"]; ?> <h2>Publiez quelques chose :</h2> 
    <form method='post' action='postimage.php'
        enctype='multipart/form-data'>
        <input type='file' name='files[]' multiple />
				<textarea name="publication" rows="5" cols="35" placeholder="Ecrivez"></textarea><br>
        <input type='submit' value='Submit' name='submit' />
    </form>
 
    
		<?php

// Get images from the database
$query = $pdo->query("SELECT * FROM images ORDER BY id ASC");
if ($query->rowCount() > 0) {
    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        $imageURL = $row["image"];
        $author = $row["pseudo"];
        $publication = $row['publication'];
        $likes = $row['likes'];
        $dislikes = $row['dislikes'];
?>
        <img class="imagehomepage" src="<?php echo $imageURL; ?>" alt="" />
        <p>Publier par : <?php echo $author; ?> <div class="posthomepage"> <?php echo $publication; ?> </div></p>

        

        

      

        
        <?php
        // Récupérer les commentaires pour cette image depuis la base de données
        $stmt = $pdo->prepare("SELECT author, comment_text FROM comments WHERE image_id = :image_id");
        $stmt->bindValue(':image_id', $row['id'], PDO::PARAM_INT);
        $stmt->execute();
        $comments = $stmt->fetchAll();?>
        <br>
<!-- ///////////////// -->

<!-- Partie des likes et dislikes : -->
<div class="like-buttons">
        <?php if (isset($_SESSION['pseudo'])) {
                // Récupération du like et du dislike de l'utilisateur pour cette image
                $stmt = $pdo->prepare('SELECT * FROM images WHERE pseudo = :pseudo AND id = :image_id');
                $stmt->execute(['pseudo' => $_SESSION['pseudo'], 'image_id' => $row['id']]);
                $vote = $stmt->fetch();

                // Affichage du bouton like si l'utilisateur n'a pas déjà voté pour cette image ou s'il a voté dislike
                if (!$vote || $vote['dislikes'] == 1) {
                    if (!$vote) {
                        echo '<form method="post" action="like.php">';
                        echo '<input type="hidden" name="image_id" value="' . $row['id'] . '">';
                        echo '<button class="button-like like-size center-text" type="submit" name="like" >' . $likes . '</button>';
                    } else {
                        echo '<button class="button-like like-size center-text disabled" disabled>' . $likes . '</button>';
                    }
                    echo '</form>';
                }

                // Affichage du bouton dislike si l'utilisateur n'a pas déjà voté pour cette image ou s'il a voté like
                if (!$vote || $vote['likes'] == 1) {
                    if (!$vote) {
                    echo '<form method="post" action="like.php">';
                    echo '<input type="hidden" name="image_id" value="' . $row['id'] . '">';
                    echo '<button class="button-dislike dislike-size center-text" type="submit" name="dislike"> ' . $dislikes . '</button>';
                    } else {
                        echo '<button class="button-dislike dislike-size center-text disabled" disabled>' . $dislikes . '</button>';
                    }  
                    echo '</form>';
                } 
            } else {
                // Affichage d'un message invitant l'utilisateur à se connecter s'il n'est pas connecté
                echo 'Connectez-vous pour voter';
            }
?>










<!-- //////////////////////// -->
        <!-- <a href="#" onclick="myFunction('1')">Voir les commentaires</a> -->
        <div id="comm">

        <?php
        // Afficher les commentaires
        foreach ($comments as $comment) {
            echo "<div id='comment '>";
            echo "<span class='commentauthor' >"  . $comment['author'] . " :  </span>";
            echo "<span class='commenttext'>" . $comment['comment_text'] . "</span>";
            echo "</div>";
        }
        ?>
        </div>
        <?php
    }
} else {
    echo "<p>No image(s) found...</p>";
}
?>

</div>



<script src="./js/comm.js"></script>
</body>
 
</html>
