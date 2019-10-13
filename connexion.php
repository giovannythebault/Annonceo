<?php
include_once('inc/init.inc.php');

// deconnexion
if(isset($_GET['action']) && $_GET['action'] == 'deconnexion') {
   session_destroy();
 }



// restriction d'accès
if(user_is_connected()) {
  //si l'utilisateur est connecté, on l'envoie vers profil.php
  header('location:profil.php');
}


$pseudo = '';
if(isset($_POST['pseudo']) && isset($_POST['mdp'])) {
  $pseudo = trim($_POST['pseudo']);
  $mdp = trim($_POST['mdp']);

  //on demande à la bdd de nous renvoyer les informations d'un utilisateur sur la base du pseudo saisie dans le champs

  $connexion = $pdo->prepare("SELECT * FROM membre WHERE pseudo = :pseudo");
  $connexion->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
  $connexion->execute();

  // si il y a une ligne le pseudo existe en BDD
  if($connexion->rowCount() > 0 ) {
      //si le pseudo est ok, on verifie le mot de passe
      $utilisateur = $connexion->fetch(PDO::FETCH_ASSOC);
      //echo '<pre>'; print_r($utilisateur); echo '</pre>';
      if(password_verify($mdp, $utilisateur['mdp'])) {
          // on place les informations utilisateur dans $_SESSION afin de les conserver tant que l'utilisateur est connecté
          $_SESSION['utilisateur'] = array();
          foreach($utilisateur AS $indice => $valeur) {
            if($indice != 'mdp') {
            $_SESSION['utilisateur'][$indice] = $valeur;
            }
          }
          header("location:profil.php");
      } else{
        $msg .= '<div class="alert alert-danger mt-2" role="alert">
        Erreur sur le pseudo ou le mot de passe.<br>Veuillez recommencer
        </div>';
      }
  } else {
    $msg .= '<div class="alert alert-danger mt-2" role="alert">
    Erreur sur le pseudo ou le mot de passe.<br>Veuillez recommencer
    </div>';
  }
}





include_once('inc/front_header.inc.php');
include_once('inc/front_nav.inc.php');


?>
<div class="container">
  <!-- Outer Row -->
  <div class="row justify-content-center">
    <div class="col-xl-10 col-lg-12 col-md-9">
      <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
          <!-- Nested Row within Card Body -->
          <div class="row">
            <div class="col-lg-12">
              <div class="p-5">
                <div class="text-center">
                  <h1 class="h4 text-gray-900 mb-4">Se connecter</h1>
                  <p class="lead"><?php echo $msg; ?></p>
                </div>
                <form method="post" action="">
                  <div class="form-group">
                      <label for="pseudo">Pseudo</label>
                      <input type="text" class="form-control" id="pseudo" name="pseudo" value="<?php echo $pseudo; ?>">
                  </div>
                  <div class="form-group">
                      <label for="mdp">Mot de passe</label>
                      <input type="password" class="form-control" id="mdp" name="mdp" value="">
                  </div>
                  <div>
                      <input type="submit" class="form-control btn btn-primary" id="connexion" name="connexion" value="connexion">

                  </div>
                </form>
                <hr>
                <div class="text-center">
                  <a class="small" href="forgot-password.html">Mot de passe oublié ?</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php

include_once('inc/front_footer.inc.php');


?>