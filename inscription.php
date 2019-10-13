<?php
include_once('inc/init.inc.php');

  // restriction d'acces
  if(user_is_connected()) {
     // si l'utilisateur est connecté, on l'envoie vers profil.php
     header('location:profil.php');
     exit(); 
   }


$pseudo = '';
$mdp = '';
$nom = '';
$prenom = '';
$email = '';
$telephone = '';
$civilite = '';

    if(isset($_POST['pseudo']) && isset($_POST['mdp']) && isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['email']) && isset($_POST['telephone']) && isset($_POST['civilite'])) {
      
      foreach($_POST AS $indice => $valeur) {
        $_POST[$indice] = trim($_POST[$indice]);
      }
      
      $pseudo = $_POST['pseudo'];
      $mdp = $_POST['mdp'];
      $nom = $_POST['nom'];
      $prenom = $_POST['prenom'];
      $email = $_POST['email'];
      $telephone = $_POST['telephone'];
      $civilite = $_POST['civilite'];
      
      //fonction pour vérifier la conformité d'un numéro de tel Français
  	  if(!preg_match('#^0[1-68]([-. ]?[0-9]{2}){4}$#', $telephone)){
      $msg .= '<div class="alert alert-danger mt-2" role="alert">Attention le numéro de téléphone est incorrect, il doit comprendre 10 chiffres<br> Veuillez recommencer</div>';
      }

      // controle sur la taille du pseudo entre 4 et 14 caractères inclus
      if(iconv_strlen($pseudo) < 4 || iconv_strlen($pseudo) > 14) {
        // cas d'erreur
        $msg .= '<div class="alert alert-danger mt-2" role="alert">Attention, le pseudo doit avoir entre 4 et 14 caractères inclus<br>Veuillez recommencer</div>';
      }
      
      if(!preg_match('#^[a-zA-Z0-9_.-]+$#', $pseudo)) {
        $msg .= '<div class="alert alert-danger mt-2" role="alert">Attention, pseudo incorrect, caractères autorisés: a-z 0-9<br>Veuillez recommencer</div>';
      }
      
      // Vérification si le pseudo est disponible en BDD car UNIQUE
      $verif_pseudo = $pdo->prepare("SELECT * FROM membre WHERE pseudo = :pseudo");
      $verif_pseudo->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
      $verif_pseudo->execute();
      
      if($verif_pseudo->rowCount() > 0) {
        // s'il y a au moins une ligne, alors le pseudo existe en BDD
        $msg .= '<div class="alert alert-danger mt-2" role="alert">Attention, pseudo indisponible<br>Veuillez recommencer</div>';
      }
      
      // vérification du format de l'email
      if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg .= '<div class="alert alert-danger mt-2" role="alert">Attention, le format du mail n\'est pas valide<br>Veuillez recommencer</div>';
      }
      
      if(empty($msg)) {
        
        // hashage du mot de passe
        $mdp = password_hash($mdp, PASSWORD_DEFAULT);	
        
        
        // enregistrement dans la BDD
        $enregistrement = $pdo->prepare("INSERT INTO membre (pseudo, mdp, nom, prenom, telephone, email, civilite, statut, date_enregistrement) VALUES (:pseudo, :mdp,:nom, :prenom, :telephone, :email, :civilite, 1, NOW())");
        $enregistrement->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
        $enregistrement->bindParam(':mdp', $mdp, PDO::PARAM_STR);
        $enregistrement->bindParam(':nom', $nom, PDO::PARAM_STR);
        $enregistrement->bindParam(':prenom', $prenom, PDO::PARAM_STR);
        $enregistrement->bindParam(':telephone', $telephone, PDO::PARAM_STR);
        $enregistrement->bindParam(':email', $email, PDO::PARAM_STR);
        $enregistrement->bindParam(':civilite', $civilite, PDO::PARAM_STR);
        $enregistrement->execute();
        
        header('location:connexion.php');
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
                    <h1 class="h4 text-gray-900 mb-4">S'inscrire</h1>
                    <p class="lead"><?php echo $msg; ?></p>
                  </div>
                  <form class="user" method="post" action="">
                    <div class="form-group">
                      <input type="text" class="form-control" id="pseudo" name="pseudo" placeholder="Votre pseudo..."  value="<?php echo $pseudo; ?>">
                    </div>
                    <div class="form-group">
                      <input type="password" class="form-control form-control-user" name="mdp" id="mdp" placeholder="Votre nom de passe... " value="<?php echo $pseudo; ?>">
                    </div>
                    <div class="form-group">
                      <input type="text" class="form-control" id="nom" name="nom" placeholder="Votre nom..."  value="<?php echo $nom; ?>">
                    </div>
                    <div class="form-group">
                      <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Votre prénom..."  value="<?php echo $prenom; ?>">
                    </div>
                    <div class="form-group">
                    <input type="text" class="form-control" id="email" name="email" aria-describedby="emailHelp" placeholder="Votre email..." value="<?php echo $email; ?>">
                    </div>
                    <div class="form-group">
                      <input type="text" class="form-control" id="telephone" name="telephone" placeholder="Votre Téléphone..."  value="<?php echo $telephone; ?>">
                    </div>
                    <div class="form-group">
                      <select class="form-control" id="civilite" name="civilite">
                        <option value="m">Homme</option>
                        <option value="f" <?php if($civilite == 'f') echo 'selected'; ?> >Femme</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <div class="custom-control custom-checkbox small">
                        <input type="checkbox" class="custom-control-input" id="customCheck">
                        <label class="custom-control-label" for="customCheck">Remember Me</label>
                      </div>
                    </div>
		                <input type="submit" class="form-control btn btn-primary btn-user btn-block" id="inscription" name="inscription" value="Inscription">	
                    <hr>
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