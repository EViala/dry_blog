<?php
require_once "components/database.php";

$title = "Articles";

//Récupération de l'article unique:
if(empty($_GET["id"])){
  $res = $pdo_database->prepare('SELECT * FROM articles ORDER BY id DESC LIMIT 25');
} else {
  $res = $pdo_database->prepare('SELECT * FROM articles WHERE id = :articleId ORDER BY id DESC LIMIT 25');
  $res->bindValue(':articleId', $_GET["id"], PDO::PARAM_INT);
}

$res->execute();
$articles = $res->fetchAll();
if(!empty($data["title"])) {
   $title = $data["title"];
}


include_once "components/header.php";
if(count($articles)>1){
  echo("<table class=\"bordered\"><tbody>");
}
foreach($articles as $data):
  //Récupération des commentaires:
  $requete_sql = 'SELECT c.*, u.nickname FROM comments AS c LEFT JOIN users as u ON u.id = c.id_user WHERE c.id_article = :articleId ORDER BY c.date DESC';
  if(count($articles)>1){
    $requete_sql.= " LIMIT 3";
  }
  $res = $pdo_database->prepare($requete_sql);
  $res->bindValue(':articleId', $data["id"], PDO::PARAM_INT);
  $res->execute();
  $commentaires = $res->fetchAll();
  if(count($articles)>1){
    echo("<tr><td>");
  } ?>
  <div class="row">
    <div class="col s12">
      <h2><a href="article.php?id=<?=$data["id"]; ?>" class="black-text"><?=$data["title"]; ?></a></h2>
      <div class="col s12 m6 l4">
        <div class="card">
          <div class="card-image waves-effect waves-block waves-light">
            <img class="" src="<?=$data["img"]; ?>">
          </div>
        </div>
      </div>
      <p><?php echo("<br />".nl2br($data["content"])); ?></p>
    </div>
  </div>
<?php
if(count($commentaires)>0): ?>
  <div class="row">
  <?php foreach($commentaires as $comment): ?>
    <div class="col s12 m12 l12">
      <div class="card blue-grey darken-1 waves-effect waves-block waves-light">
        <div class="card-content white-text">
          <p><?=$comment["comment"]; ?></p>
          <p class="right orange-text">Par <?php
            if(empty($comment["nickname"])){
              echo("anonyme");
            } else {
              echo($comment["nickname"]);
            } ?> le <?=date_format(date_create($comment["date"]), 'm/d à H:i'); ?></p>
        </div>
      </div>
    </div>
<?php endforeach; ?>
  </div>

<?php endif; //Endif count($commentaires)>0) ?>
  <div class="row right-align">
    <p><a href="nouveaucomm.php?id=<?=$data["id"] ?>">Ajouter un commentaire</a></p>
  </div>
<?php
if(count($articles)>1){
  echo("</td></tr>");
}
endforeach;
if(count($articles)>1){
echo("</tbody></table>");
echo("<div class=\"center-align\"><p><a href=\"#\">Retour en haut</a></p></div>");
}
include_once "components/bottom.php";

 ?>
