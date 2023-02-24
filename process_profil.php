<?php
session_start();
require("connect.php");
require("infos.php");


if (!isset($_SESSION['pseudo'])) {
  // Rediriger l'utilisateur vers la page de connexion
  echo '<script type="text/javascript"> window.location="login.php";</script>'; 
}



// enregistrer les photos téléchargées par l'utilisateur dans la base de données :

if (!empty($_FILES['avatar']['name'])) {
    
 // Vérifier si le fichier est une image
 $imageFileType = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));
 if ($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png" && $imageFileType != "gif") {
   echo "Seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés.";
   exit();
 }
  
  // Télécharger l'image et la stocker dans le dossier "avatars"
  $targetDir = "avatars/";
  $avatarName = $_SESSION['pseudo'] . "." . $imageFileType;
  $targetFile = $targetDir . $avatarName;
  move_uploaded_file($_FILES['avatar']['tmp_name'], $targetFile);
  
  // Mettre à jour la colonne "nom" dans la table "utilisateurs" en cas de modification
  $stmt = $pdo->prepare("UPDATE utilisateurs SET avatar=? WHERE pseudo=?");
  $stmt->execute([$avatarName, $_SESSION['pseudo']]);

} 



  // Mettre à jour la colonne "date_naissance" dans la table "utilisateurs" en cas de modification
  if(!empty($_POST['date_de_naissance'])) {
  $dateNaissance = $_POST['date_de_naissance'];
  $stmt = $pdo->prepare("UPDATE utilisateurs SET date_naissance=? WHERE pseudo=?");
  $stmt->execute([$dateNaissance, $_SESSION['pseudo']]);
  }
  
  // Mettre à jour la colonne "descrip" dans la table "utilisateurs" en cas de modification

  if(!empty($_POST['description'])) {
  $description = $_POST['description'];
  $stmt = $pdo->prepare("UPDATE utilisateurs SET description=? WHERE pseudo=?");
  $stmt->execute([$description, $_SESSION['pseudo']]);
  }

 


  // Rediriger l'utilisateur vers la page de profil
echo '<script type="text/javascript"> window.location="profil.php";</script>';  
  

?>
















  
