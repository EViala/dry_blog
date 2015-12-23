<?php
$errors = array();
require_once "components/database.php";

if(empty($_GET)){
  $errors[] = "Mauvaise requête.";
} else {
  foreach($_GET as $key => $value){
    $get[$key] = trim(strip_tags($value));
  }
  $searchval = $get["search"];

  if(empty($get["search"])){
    $errors[] = "Mauvaise requête.";
  } else {
    //Les titres en priorité (début de titre uniquement):
    $req_titles = $pdo_database->prepare("SELECT * FROM articles WHERE title LIKE :recherche");
    $req_titles->bindValue(":recherche", $get["search"].'%', PDO::PARAM_STR);
    if($req_titles->execute()){
      $titles = $req_titles->fetchAll();
    } else {
      $errors[] = "Une erreur est intervenue.";
    }

    //Ensuite les contenus d'articles
    if(count($errors)==0){
      if(count($titles)>0){
        $idList = "";
        foreach($titles as $checkId){
          $idList.=$checkId["id"].",";
        }
        $idList= trim($idList, ",");
        $req_content = $pdo_database->prepare('SELECT * FROM articles WHERE content LIKE :recherche AND id NOT IN ('.$idList.')');
      } else {
        $req_content = $pdo_database->prepare("SELECT * FROM articles WHERE content LIKE :recherche");
      }
      $req_content->bindValue(":recherche", '%'.$get["search"].'%', PDO::PARAM_STR);
      if($req_content->execute()){
        $contents = $req_content->fetchAll();
      } else {
        $errors[] = "Une erreur est intervenue.";
      }
    }
    if(count($titles)==0 && count($contents)==0){
      $errors[] = "Aucun article n'a été trouvé !";
    }
  }
}

$title = "Recherche";

include_once "components/header.php";
?>
<?php if(count($errors)==0): ?>
<table class="bordered"><tbody>
<?php foreach($titles as $resultat_titre): ?>
  <tr><td>
    <?php $mod_titre = preg_replace('/'.$get["search"].'/i', '<span class="red-text">$0</span>' , $resultat_titre["title"]); ?>
    <h4><a href="article.php?id=<?=$resultat_titre["id"]; ?>"><?=$mod_titre; ?></a></h4>
    <p>
    <?php echo(preg_replace('/'.$get["search"].'/i', '<strong class="red-text">$0</strong>' , $resultat_titre["content"])); ?>
    </p>
  </td></tr>
<?php endforeach; ?>
<?php foreach($contents as $resultat_content): ?>
  <tr><td>
    <h4><a href="article.php?id=<?=$resultat_content["id"]; ?>"><?=$resultat_content["title"]; ?></a></h4>
    <p>
    <?php echo(preg_replace('/'.$get["search"].'/i', '<strong class="red-text">$0</strong>' , $resultat_content["content"])); ?>
    </p>
  </td></tr>
<?php endforeach; ?>
</tbody></table>
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
        <a href="home.php">Retour</a>
      </div>
    </div>
  </div>
</div>
<?php
endif;
include_once "components/bottom.php";
 ?>
