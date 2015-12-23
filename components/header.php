<?php

if(!isset($title)){
  $title = "Bienvenue";
}
if(!isset($searchval)){
  $searchval = "";
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title><?=$title ?></title>
      <!--Import Google Icon Font-->
      <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
      <!--Import materialize.css-->
      <link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>
      <link href="css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>

      <!--Let browser know website is optimized for mobile-->
      <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  </head>
  <body>
    <div class="navbar-fixed">
    <nav class="grey darken-4 lighten-1" role="navigation">
      <div class="nav-wrapper container">
        <a id="logo-container" href="index.php" class="brand-logo">
          Don't Repeat Yourself
        </a>
        <a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">menu</i></a>
        <ul class="right hide-on-med-and-down">
          <li>
            <form action="recherche.php">
              <div class="input-field">
                <input id="search" name="search" type="search" required value=<?=$searchval; ?>>
                <label for="search"><i class="material-icons">search</i></label>
                <i class="material-icons">close</i>
              </div>
            </form>
          </li>
          <li><a href="home.php">Home</a></li>
          <li><a href="article.php">Articles</a></li>
          <li><a href="listeutilisateur.php">Utilisateurs</a></li>
          <li><a href="publier.php">Publier un article</a></li>
        </ul>
        <ul class="side-nav" id="mobile-demo">
          <li><a href="home.php">Home</a></li>
          <li><a href="article.php">Articles</a></li>
          <li><a href="listeutilisateur.php">Utilisateurs</a></li>
          <li><a href="publier.php">Publier un article</a></li>
          <li class="grey lighten-1">
            <form action="recherche.php">
              <div class="input-field">
                <input id="search" name="search" type="search" required value=<?=$searchval; ?>>
                <label for="search"><i class="material-icons">search</i></label>
                <i class="material-icons">close</i>
              </div>
            </form>
          </li>
        </ul>
      </div>
    </nav>
    </div>
    <div class="container main">
