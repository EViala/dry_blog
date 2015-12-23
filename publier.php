<?php
require_once "components/database.php";

$title = "Publier un article";
$onParse = false;
$errors = array();

if(count($_POST)>0){
  $onParse = true;
  $post = array();
  foreach($_POST as $key => $value){
    $post[$key] = trim(strip_tags($value));
  }

  //Vérification du titre
  if(empty($post["title"])) {
    $errors[] = "Le titre ne peut pas être vide.";
  } else {
    if(strlen($post["title"])>150) {
      $errors[] = "Le titre ne peut être plus grand que 150 caractères.";
    }
  }

  //Vérification de l'image
  if(empty($_FILES["image"])) {
    $errors[] = "L'image est vide.";
  } else {
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $allowed = ['image/gif', 'image/jpeg', 'image/png'];
    if(in_array($finfo->file($_FILES['image']['tmp_name']), $allowed)){
      if($_FILES['image']['size']>1024*512){
        $errors[] = 'La taille de l\'image est trop grande.';
      } else {
        //remove extension
        $tname = basename($_FILES['image']['name'], substr(strrchr($_FILES['image']['name'], '.'), 1));
        //create new extension
        $ext = '.'.substr($finfo->file($_FILES['image']['tmp_name']), 6);
        //remove whitespaces
        $tname = str_replace(' ', '', $tname);
        //transform name
        $filename = 'uploads/'.preg_replace('/[^A-Za-z0-9\-]/', '', $tname).$ext;
        //move file
        move_uploaded_file($_FILES['image']['tmp_name'], $filename);
      }
    }
  }

  //Vérification du contenu de l'article
  if(empty($post["content"])) {
    $errors[] = "Le contenu de l'article ne peut pas être vide.";
  }

  /*
  if(empty($post["passwd"])){
    $errors[] = "Veuillez entrer un mot de passe";
  } else {
    if($post["passwd"] != "manu"){
      $errors[] = "Mot de passe erroné";
    }
  }*/

  if(count($errors)==0){
    //Préparation de la requête SQL.
    $res = $pdo_database->prepare("INSERT INTO articles (title, img, content, date) VALUES (:titre , :image, :contenu, NOW())");
    $res->bindValue(':titre', $post["title"], PDO::PARAM_STR);
    $res->bindValue(':image', $filename, PDO::PARAM_STR);
    $res->bindValue(':contenu', $post["content"], PDO::PARAM_STR);
    $pdo_send = $res->execute();
    if($pdo_send === false){
      $errors[] = "Une erreur est survenue, revenez plus tard.";
    }
  }
}


include_once "components/header.php";

if(!$onParse):
  echo <<< HTML

<div class="row">
   <form class="col s12" action="publier.php" id="publier" method="post" enctype="multipart/form-data">
     <div class="row">
       <div class="input-field col s6">
         <input placeholder="Titre" id="title" name="title" type="text" class="validate">
         <label for="title">Titre</label>
       </div>
     </div>
     <div class="row">
       <div class="file-field input-field">
         <div class="btn">
           <span>Image</span>
           <input type="file" name="image">
         </div>
         <div class="file-path-wrapper">
           <input class="file-path validate" type="text">
         </div>
       </div>
     </div>
     <div class="row">
      <div class="input-field col s12">
        <textarea id="content" name="content" class="materialize-textarea"></textarea>
        <label for="content">Contenu</label>
      </div>
    </div>
    <!--
    <div class="row">
      <div class="input-field col s6">
        <input placeholder="Mot de passe" id="passwd" name="passwd" type="password" class="validate">
        <label for="passwd">Mot de passe</label>
      </div>
    </div>-->
  </form>
  <button class="btn waves-effect waves-light" type="submit" form="publier">Envoyer
    <i class="material-icons right">send</i>
  </button>
 </div>

HTML;
elseif (count($errors)==0): ?>
  <div class="row">
    <div class="col s12 m6">
      <div class="card blue-grey darken-1">
        <div class="card-content white-text">
          <span class="card-title">Article envoyé !</span>
        </div>
        <div class="card-action">
          <a href="publier.php">Retour</a>
        </div>
      </div>
    </div>
  </div>

<?php else: ?>
  <div class="row">
    <div class="col s12 m6">
      <div class="card blue-grey darken-1">
        <div class="card-content white-text">
          <div clas="row"><span class="card-title">Impossible d'envoyer l'article !</span></div>
          <?php foreach($errors as $err){
            echo("<p>".$err."</p>");
          } ?>
        </div>
        <div class="card-action">
          <a href="publier.php">Retour</a>
        </div>
      </div>
    </div>
  </div>

<?php endif;


include_once "components/bottom.php";


 ?>
