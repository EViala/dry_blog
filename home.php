<?php
require_once "components/database.php";

include_once "components/header.php";

$errors = array();
$res = $pdo_database->prepare('SELECT id, title, img, content, date FROM articles ORDER BY id DESC LIMIT 6');

if(!$res->execute()){
  $errors[] = "Une erreur est survenue.";
} else {
  $data = $res->fetchAll();
  echo('  <div class="row">');
  foreach($data as $article):
    $content = substr($article["content"],0,180)."... ";
    if(strlen($article["title"])>25){
      $article_titre = substr($article["title"],0,25)."... ";
    } else {
      $article_titre = $article["title"];
    }

    ?>
    <div class="col s12 m6 l4">
      <div class="card large">
        <div class="card-image waves-effect waves-block waves-light">
          <img src="<?=$article["img"] ?>">
          <span class="card-title"><?=$article_titre ?></span>
        </div>
        <div class="card-content">
          <p><?=$content ?></p>
        </div>
        <div class="card-action right-align">
          <a class="" href="article.php?id=<?=$article["id"]; ?>">Suite..</a>
        </div>
      </div>
    </div>
<?php endforeach;
echo("  </div>");
}
include_once "components/bottom.php";
?>
