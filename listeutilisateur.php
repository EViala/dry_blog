<?php
$title = "Liste des utilisateurs";
require_once "components/database.php";
$erreurs = array();
$recherche = "";

if(!empty($_GET["search"])){
  $recherche = strip_tags($_GET["search"]);
  $req = $pdo_database->prepare("SELECT * FROM users WHERE nickname LIKE :user");
  $req->bindValue(":user",'%'.$recherche.'%',PDO::PARAM_STR);
  if($req->execute()){
    $users = $req->fetchAll();
  } else {
    $erreurs[] = "Erreur avec la base de donnée.";
  }
} else {
  $req = $pdo_database->prepare("SELECT * FROM users");
  if($req->execute()){
    $users = $req->fetchAll();
  } else {
    $erreurs[] = "Erreur avec la base de donnée.";
  }
}
if(count($users)==0){
  $erreurs[] = "Aucun utilisateur n'a été trouvé.";
}

include_once "components/header.php"; ?>

<?php if(count($erreurs)>0): ?>
<div class="row">
  <div class="col s12 m6">
    <div class="card blue-grey darken-1">
      <div class="card-content white-text">
        <span class="card-title">Erreur</span>
        <?php
          foreach($erreurs as $err){
            echo('<p>'.$err.'</p>');
          }
         ?>
      </div>
      <div class="card-action">
        <a href="listeutilisateur.php">Retour</a>
      </div>
    </div>
  </div>
</div>
<?php else: ?>
<div class="row">
  <h4>Liste des utilisateurs:</h4>
</div>
<div class="row">
  <form action="listeutilisateur.php">
    <div class="row valign-wrapper">
      <div class="input-field col s4">
        <input placeholder="utilisateur" id="search" name="search" type="text" class="validate" value="<?=$recherche ?>">
        <label for="search">Utilisateur</label>
      </div>
      <button class="btn waves-effect waves-light" type="submit">Rechercher
        <i class="material-icons right">send</i>
      </button>
    </div>
  </form>
</div>
<div class="row">
<table>
  <thead>
    <tr>
      <td>Pseudo</td>
      <td>Date d'inscription</td>
    </tr>
  </thead>
  <tbody>
  <?php foreach($users as $u): ?>
    <tr>
      <td><?php echo(preg_replace('/'.$recherche.'/i', '<span class="red-text">$0</span>' , $u["nickname"])); ?></td>
      <td><?=$u["date_registered"]; ?></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
</div>

<?php
endif;

include_once "components/bottom.php";

 ?>
