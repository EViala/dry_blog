<?php
require_once "components/database.php";

$title = "Envoyer un commentaire";
$onParse = false;
$errors = array();
$userID = false;

include_once "components/header.php";
//Parsing du post
if(count($_POST)>0){
  $onParse = true;
  $post = array();
  foreach($_POST as $key => $value){
    $post[$key] = trim(strip_tags($value));
  }

  //Check all fields:
  if(empty($post["nickname"])) {
    $errors[] = "Le pseudo ne peut pas être vide.";
  } else {
    if(strlen($post["nickname"])>150) {
      $errors[] = "Le pseudo ne peut être plus grand que 150 caractères.";
    }
  }
  if(empty($post["comment"])) {
    $errors[] = "Le commentaire de l'article ne peut pas être vide.";
  }

  if(!count($errors)>0){
    //Check if user already exist
    $req = $pdo_database->prepare("SELECT * FROM users WHERE nickname = :nick");
    $req->bindValue(":nick", $post["nickname"], PDO::PARAM_STR);
    if($req->execute() === false){
      $errors[] = "Une erreur est intervenue.";
    } else {
      $users = $req->fetchAll();
      if(count($users)>0){
        $userID = $users[0]["id"];
      }
    }
    //If not then we create it:
    if($userID === false){
      $req = $pdo_database->prepare("INSERT INTO users (nickname, date_registered) VALUES (:nick , NOW())");
      $req->bindValue(":nick", $post["nickname"], PDO::PARAM_STR);
      if($req->execute() === false){
        $errors[] = "Une erreur est intervenue.";
      } else {
        $userID = $pdo_database->lastInsertId(); // Permet de récup le dernier ID inséré
      }
    }
    if(!count($errors)>0){
      //Then create the comment
      $req = $pdo_database->prepare("INSERT INTO comments (comment, id_article, id_user, date) VALUES (:comm, :idArticle, :idUser, NOW())");
      $req->bindValue(":comm", $post["comment"], PDO::PARAM_STR);
      $req->bindValue(":idArticle", $post["idArticle"], PDO::PARAM_INT);
      $req->bindValue(":idUser", $userID, PDO::PARAM_INT);
      if($req->execute() === false){
        $errors[] = "Une erreur est intervenue.";
      }
    }
  }

} else {
  //Formulaire d'envoi du commentaire.
  if(isset($_GET["id"])){
    $articleId = intval($_GET["id"]);
    if(empty($articleId)){
      $errors[] = "Aucun article n'est spécifié.";
    } else {
      $req = $pdo_database->prepare("SELECT title FROM articles WHERE id = :ArticleId");
      $req->bindValue(":ArticleId", $articleId, PDO::PARAM_INT);
      if(!$req->execute()){
        $errors[] = "Une erreur est intervenue.";
      } else {
        $articleTitle = $req->fetch()["title"];
      }
    }
  } else {
    $errors[] = "Aucun article n'est spécifié.";
  }

}

if(!$onParse && count($errors)==0):  ?>

<div class="row">
  <h3>Envoyer un commentaire:</h3>
  <p>Article: <?=$articleTitle ?></p>
   <form class="col s12" action="nouveaucomm.php" id="nouveaucomm" method="post">
     <input type="hidden" name="idArticle" value="<?=$articleId ?>">
     <div class="row">
       <div class="input-field col s6">
         <input placeholder="Pseudo" id="nickname" name="nickname" type="text" class="validate">
         <label for="nickname">Pseudo</label>
       </div>
     </div>
     <div class="row">
      <div class="input-field col s12">
        <textarea id="comment" name="comment" class="materialize-textarea"></textarea>
        <label for="comment">Commentaire</label>
      </div>
    </div>
  </form>
  <button class="btn waves-effect waves-light" type="submit" form="nouveaucomm">Envoyer
    <i class="material-icons right">send</i>
  </button>
 </div>
 <div class="row right-align">
   <p><a href="article.php?id=<?=$articleId ?>">Retour</a></p>
 </div>

<?php elseif(count($errors)>0): ?>
<div class="row">
  <div class="col s12 m6">
    <div class="card blue-grey darken-1">
      <div class="card-content white-text">
        <div clas="row"><span class="card-title">Erreur !</span></div>
        <?php foreach($errors as $err){
          echo("<p>".$err."</p>");
        } ?>
      </div>
      <div class="card-action">
        <a href="nouveaucomm.php?id=<?=$post["idArticle"] ?>">Retour</a>
      </div>
    </div>
  </div>
</div>
<?php else: ?>

  <div class="row">
    <div class="col s12 m6">
      <div class="card blue-grey darken-1">
        <div class="card-content white-text">
          <span class="card-title">Commentaire envoyé !</span>
        </div>
        <div class="card-action">
          <a href="article.php?id=<?=$post["idArticle"] ?>">Retour</a>
        </div>
      </div>
    </div>
  </div>

<?php
endif;
include_once "components/bottom.php";
?>
