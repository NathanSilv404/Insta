<?php
session_start();
require("connect.php");
require("infos.php");

$query = $pdo->query("SELECT * FROM images ORDER BY id ASC");
if ($query->rowCount() > 0) {
    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        $imageURL = $row["image"];
        $author = $row["pseudo"];
        $publication = $row['publication'];

if (isset($_POST['submit_comment']) && $_POST['image_id'] == $row['id']) {
  // Récupérer les données du formulaire
  $image_id = $_POST['image_id'];
  $pseudo = $_POST['pseudo'];
  $comment = $_POST['comment'];

  // Vérifier si le commentaire n'a pas déjà été enregistré dans la base de données
  $stmt = $pdo->prepare("SELECT COUNT(*) FROM comments WHERE image_id = :image_id AND author = :pseudo AND comment_text = :comment");
  $stmt->bindValue(':image_id', $image_id, PDO::PARAM_INT);
  $stmt->bindValue(':pseudo', $pseudo, PDO::PARAM_STR);
  $stmt->bindValue(':comment', $comment, PDO::PARAM_STR);
  $stmt->execute();
  $count = $stmt->fetchColumn();

  if ($count == 0) {
      // Insérer le commentaire dans la base de données
      $formcomment = $pdo->prepare('INSERT INTO comments (image_id, author, comment_text, created_at) VALUES (:image_id, :pseudo, :comment, NOW())');
      $formcomment->execute(array(
          ':image_id' => $image_id,
          ':pseudo' => $pseudo,
          ':comment' => $comment
      ));
      unset($formcomment);
  }



echo '<script type="text/javascript"> window.location="homepage.php";</script>';
    }}}