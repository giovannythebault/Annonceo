
<?php
include_once('../inc/init.inc.php');

if(!user_is_admin()) {
  //si l'utilisateur n'est pas connecté
  header("location:../index.php");
}

$liste_membre = $pdo->query("SELECT id_membre, pseudo, nom, prenom, email, telephone, civilite, statut, date_enregistrement FROM  membre");


$pseudo = '';
$mdp = '';
$nom = '';
$prenom = '';
$email = '';
$telephone = '';
$civilite = '';
$statut = '';


	
/********************************************************************************** */

		//ajout d'un membre
  

    if(isset($_POST['pseudo'], $_POST['mdp'], $_POST['nom'], $_POST['prenom'], $_POST['email'], $_POST['telephone'], $_POST['civilite'], $_POST['statut'])) {

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


      $enregistrement = $pdo->prepare("INSERT INTO membre (pseudo, mdp, nom, prenom, telephone, email, civilite, statut, date_enregistrement) VALUES (:pseudo, :mdp,:nom, :prenom, :telephone, :email, :civilite, 1, NOW())");
        $enregistrement->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
        $enregistrement->bindParam(':mdp', $mdp, PDO::PARAM_STR);
        $enregistrement->bindParam(':nom', $nom, PDO::PARAM_STR);
        $enregistrement->bindParam(':prenom', $prenom, PDO::PARAM_STR);
        $enregistrement->bindParam(':telephone', $telephone, PDO::PARAM_STR);
        $enregistrement->bindParam(':email', $email, PDO::PARAM_STR);
        $enregistrement->bindParam(':civilite', $civilite, PDO::PARAM_STR);
        $enregistrement->execute();
    
      header('location:gestion-des-membres.php');}
    }
     
    
    

/********************************************************************************** */

		//modification du membre

			// récup

			if(isset($_GET['action']) && $_GET['action'] == 'modification' ) {

        echo '<style>.disap { display:none;}</style>';
        
        

        
	
				$recup_membre = $pdo->prepare("SELECT * FROM membre WHERE id_membre = :id_membre");
				$recup_membre->bindParam(':id_membre', $_GET['id_membre'], PDO::PARAM_STR);
				$recup_membre->execute();
	
			
				
				
				if($recup_membre->rowCount() > 0) {
          $infos_membre = $recup_membre->fetch(PDO::FETCH_ASSOC);
          $pseudo = $infos_membre['pseudo'];
					$nom = $infos_membre['nom'];
					$prenom = $infos_membre['prenom'];
	        $email = $infos_membre['email'];
					$telephone = $infos_membre['telephone'];
					$civilite = $infos_membre['civilite'];
					$statut = $infos_membre['statut'];
				  }
		
            
			//changement

      if(isset($_POST['pseudo'], $_POST['nom'], $_POST['prenom'], $_POST['email'], $_POST['telephone'], $_POST['civilite'], $_POST['statut'])) {

          
        foreach($_POST AS $indice => $valeur) {
          $_POST[$indice] = trim($_POST[$indice]);
        }
  
        $pseudo = $_POST['pseudo'];
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



// vérification du format de l'email
if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  $msg .= '<div class="alert alert-danger mt-2" role="alert">Attention, le format du mail n\'est pas valide<br>Veuillez recommencer</div>';
}

                
        if(empty($msg)) {


$modificationMembre = $pdo->prepare("UPDATE membre SET pseudo =:pseudo ,nom =:nom, prenom = :prenom, email = :email, telephone = :telephone, civilite = :civilite, statut = :statut WHERE id_membre = :id_membre");
$modificationMembre->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
$modificationMembre->bindParam(':nom',$nom, PDO::PARAM_STR);
$modificationMembre->bindParam(':prenom', $prenom, PDO::PARAM_STR);
$modificationMembre->bindParam(':email', $email, PDO::PARAM_STR);
$modificationMembre->bindParam(':telephone', $telephone, PDO::PARAM_STR);
$modificationMembre->bindParam(':civilite', $civilite, PDO::PARAM_STR);
$modificationMembre->bindParam(':statut', $statut, PDO::PARAM_STR);
$modificationMembre->bindParam(':id_membre', $_GET['id_membre'], PDO::PARAM_STR);
$modificationMembre->execute();

        header('location:gestion-des-membres.php');

        }

		}

	}


/********************************************************************************** */

		//suppression du membre

		if(isset($_GET['action']) && $_GET['action'] == 'suppression' && isset($_GET['id_membre']) && is_numeric($_GET['id_membre'])) {
			
		
			$suppression = $pdo->prepare("DELETE FROM membre WHERE id_membre = :id_membre");
			$suppression->bindParam(':id_membre', $_GET['id_membre'], PDO::PARAM_STR);
			$suppression->execute();
			header('location:gestion-des-membres.php');

}
/************************************************************************************* */

include_once('back_head.inc.php');
include_once('back_nav.inc.php');



?>

<?php
echo '<div class="row disap">';

echo '<div class="col-8 disap">';
		echo '<table class="table table-bordered" style=" margin:10px;">';

		

		echo '<tr>
		<th>Id membre</th>
		<th>pseudo</th>
		<th>nom</th>
        <th>prenom</th>
        <th>email</th>
        <th>telephone</th>
        <th>civilite</th>
        <th>statut</th>
        <th>date_enregistrement</th>
        <th>actions</th>
		</tr>';

		while($ligneMembre = $liste_membre->fetch(PDO::FETCH_ASSOC)) {

			echo '<tr>';

      foreach($ligneMembre AS $indice => $valeur) {
				
        echo '<td>' . $valeur . '</td>';
      }
				
			echo '<td><a href = "?action=modification&id_membre=' . $ligneMembre['id_membre'] . '" class="btn btn-warning"><i class="fas fa-sync"></i></a>';
            echo '<a href = "?action=suppression&id_membre=' . $ligneMembre['id_membre'] . '"  onclick="return(confirm(\'Etes vous sûr?\'))"  class="btn btn-danger"><i class="fas fa-trash-alt"></i></a></td>';
			echo '</tr>';
		}

		echo '</table>';
        echo '</div>';

				echo '</div>';
                
    ?>
<div class="container ">
<div class="col-8 m-5 disap">
<p><b>Ajouter un membre<b></p>

<p class="lead"><?php echo $msg; ?></p>

<form class="user" method="post" action="">
                    <div class="form-group">
                      <input type="text" class="form-control" id="pseudo" name="pseudo" placeholder="pseudo..."  value="<?php echo $pseudo; ?>">
                    </div>
                    <div class="form-group">
                      <input type="password" class="form-control form-control-user" name="mdp" id="mdp" placeholder="mot de passe... " value="<?php echo $mdp; ?>">
                    </div>
                    <div class="form-group">
                      <input type="text" class="form-control" id="nom" name="nom" placeholder="nom..."  value="<?php echo $nom; ?>">
                    </div>
                    <div class="form-group">
                      <input type="text" class="form-control" id="prenom" name="prenom" placeholder="prénom..."  value="<?php echo $prenom; ?>">
                    </div>
                    <div class="form-group">
                    <input type="text" class="form-control" id="email" name="email" aria-describedby="emailHelp" placeholder="email..." value="<?php echo $email; ?>">
                    </div>
                    <div class="form-group">
                      <input type="text" class="form-control" id="telephone" name="telephone" placeholder="Téléphone..."  value="<?php echo $telephone; ?>">
                    </div>
                    <div class="form-group">
                      <select class="form-control" id="civilite" name="civilite">
                        <option value="m">Homme</option>
                        <option value="f" <?php if($civilite == 'f') echo 'selected'; ?> >Femme</option>
                      </select>
                    </div>
                    <div class="form-group">
                    <label for="statut">statut</label>
                    <select class="form-control" id="statut" name="statut">
                        <option value="1">1</option>
                        <option value="2" <?php if($statut == '2') echo 'selected'; ?> >2</option>
                      </select>
                    </div>
                    <input type="submit" class="form-control btn btn-primary btn-user btn-block" id="enregistrer" name="enregistrer" value="enregistrer">	


</form>

</div>

</div>
     </div>

<!--Formulaire pour la modification -->

 <?php
if(isset($_GET['action']) && ($_GET['action'] == 'modification')) { ?>

	<div class="col-6">
	<p><b>Modification du Membre<b></p>
  <p class="lead"><?php echo $msg; ?></p>

	<div class="form-group">   
	
	<form class="user" method="post" action="" enctype="multipart/form-data">

                    <div class="form-group">
                      <input type="text" class="form-control" id="pseudo" name="pseudo" placeholder="pseudo..."  value="<?php echo $pseudo; ?>">
                    </div>
                    <div class="form-group">
                      <input type="text" class="form-control" id="nom" name="nom" placeholder="nom..."  value="<?php echo $nom; ?>">
                    </div>
                    <div class="form-group">
                      <input type="text" class="form-control" id="prenom" name="prenom" placeholder="prénom..."  value="<?php echo $prenom; ?>">
                    </div>
                    <div class="form-group">
                    <input type="text" class="form-control" id="email" name="email" aria-describedby="emailHelp" placeholder="email..." value="<?php echo $email; ?>">
                    </div>
                    <div class="form-group">
                      <input type="text" class="form-control" id="telephone" name="telephone" placeholder="Téléphone..."  value="<?php echo $telephone; ?>">
                    </div>
                    <div class="form-group">
                      <select class="form-control" id="civilite" name="civilite">
                        <option value="m">Homme</option>
                        <option value="f" <?php if($civilite == 'f') echo 'selected'; ?> >Femme</option>
                      </select>
                    </div>
                    <div class="form-group">
                    <label for="statut">statut</label>
                    <select class="form-control" id="statut" name="statut">
                        <option value="1">1</option>
                        <option value="2" <?php if($statut == '2') echo 'selected'; ?> >2</option>
                      </select>
                    </div>
                    <div class="p-2">
                    <input type="submit" class="form-control btn btn-primary btn-user btn-block" onclick="return(confirm('Modifier le membre?'))" id="enregistrer" name="enregistrer" value="enregistrer">
	                  </div>
    
	
	</form>
	
	</div>
	
	</div>
	<?php } ?>

<?php

include_once('back_footer.inc.php');

