<?php
include_once('inc/init.inc.php');


if(!user_is_connected()) {
  //si l'utilisateur n'est pas connecté
  header("location:connexion.php");
  exit(); 
}
$pseudo ='';
$nom ='';
$prenom ='';
$civilite ='';
$telephone = '';
$email = '';
$mdp = '';
$mdpConfirm = '';

$liste_membre = $pdo->query("SELECT * FROM membre");
//***************************************
// RECUP DES INFOS PROFIL EN BDD
//***************************************

		$id_membre = $_SESSION['utilisateur']['id_membre'];
		$pseudo = $_SESSION['utilisateur']['pseudo'];
		$nom = $_SESSION['utilisateur']['nom'];
		$prenom= $_SESSION['utilisateur']['prenom'];
		$civilite = $_SESSION['utilisateur']['civilite'];
    $telephone = $_SESSION['utilisateur']['telephone'];
    $email = $_SESSION['utilisateur']['email'];
        

// MODIFICATION DES INFOS

if(empty($msg) && isset($_POST['enregistrement']) && isset($_POST['pseudo']) && isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['civilite']) && isset($_POST['telephone']) && isset($_POST['email']) && isset($_POST['mdp']) && isset($_POST['mdpConfirm'])) {

        $id_membre = $_SESSION['utilisateur']['id_membre'];
        $pseudo = $_POST['pseudo'];
        $nom = $_POST['nom'];
        $prenom= $_POST['prenom'];
        $civilite = $_POST['civilite'];
        $telephone = $_POST['telephone'];
        $email= $_POST['email'];
        $mdp= $_POST['mdp'];
        $mdpConfirm= $_POST['mdpConfirm'];

        // SI LE MDPCONFIRM EST LE MEME QUE MDP ALORS ON ENVOIE LES INFOS A LA BDD AU SINON TU AFFICHES ERREUR SUR LE MDP
        if($mdpConfirm == $mdp){
            $mdp = password_hash($mdp, PASSWORD_DEFAULT);
            $modif_profil = $pdo->prepare("UPDATE membre SET pseudo = :pseudo, nom = :nom, prenom = :prenom, civilite = :civilite, telephone = :telephone, email = :email, mdp = :mdp  WHERE id_membre = :id_membre");
		
            $modif_profil->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
            $modif_profil->bindParam(':nom', $nom, PDO::PARAM_STR);
            $modif_profil->bindParam(':prenom', $prenom, PDO::PARAM_STR);
            $modif_profil->bindParam(':civilite', $civilite, PDO::PARAM_STR);
            $modif_profil->bindParam(':id_membre', $id_membre, PDO::PARAM_STR);
            $modif_profil->bindParam(':telephone', $telephone, PDO::PARAM_STR);
            $modif_profil->bindParam(':email', $email, PDO::PARAM_STR);
            $modif_profil->bindParam(':mdp', $mdp, PDO::PARAM_STR);
            $modif_profil->execute();

        // ENVOIE DES NOUVELLES INFOS A L'UTILISATEUR
            $infos_membre = $pdo->query("SELECT * FROM membre WHERE id_membre = $id_membre");
            $membre = $infos_membre->fetch(PDO::FETCH_ASSOC);
            foreach($membre AS $indice => $valeur) {
                if($indice != 'mdp'){
                    $_SESSION['utilisateur'][$indice] = $valeur;
                }
            } 
        } else {
            $msg .= 'Erreur sur le mdp';
        }             
}
//***************************************
// fin modifier le profil 
//***************************************

 /***************************************************/
  /******* Moyenne des notes du profil ********/
  /**************************************************/
  $query6 = 'SELECT ROUND(avg(note),1) AS notation FROM note WHERE membre_id2 = ' . $_SESSION['utilisateur']['id_membre'];
  $stmt = $pdo ->query($query6);
  $moyenneNotes = $stmt -> fetch();

// Récupération de toutes les annonces en BDD
$liste_annonce = $pdo->query("SELECT * FROM annonce");

//*************************************
// RECUP INFO PRODUIT POUR MODIFICATION
//*************************************
if(isset($_GET['action']) && $_GET['action'] == 'modification' && isset($_SESSION['id_membre']) && is_numeric($_SESSION['id_membre'])) {
	
	$recup_membre = $pdo->prepare("SELECT * FROM membre WHERE id_membre = :id_membre");
	$recup_membre->bindParam(':id_membre', $_SESSION['utilisateur']['id_membre'], PDO::PARAM_STR);
	$recup_membre->execute();
	
	if($recup_membre->rowCount() > 0) {
    $infos_membre = $recup_membre->fetch(PDO::FETCH_ASSOC);
    $id_membre = $infos_membre['id_membre'];
    $_GET['action'] == 'modification';
    
		print_r($infos_membre);
	}
}
//*****************************************
// FIN RECUP INFO PRODUIT POUR MODIFICATION
//*****************************************

//***************************
// SUPPRESSION PRODUIT
//***************************
if(isset($_GET['action']) && $_GET['action'] == 'suppression' && isset($_GET['id_annonce']) && is_numeric($_GET['id_annonce'])) {
	// suppression du produit via l'id_article passé dans l'url quand l'utilisateur clic sur le bouton supprimer
	
	$suppression = $pdo->prepare("DELETE FROM annonce WHERE id_annonce = :id_annonce");
	$suppression->bindParam(':id_annonce', $_GET['id_annonce'], PDO::PARAM_STR);
	$suppression->execute();
	$_GET['action'] = 'afficher_annonce';
	
	// pour supprimer l'image du produit, il faut faire un select via l'id + un fetch et récupérer le chemin de la photo
	// passer le le chemin de la photo dans la fonction unlink() qui permet de supprimer un fichier du serveur.
	
}
//***************************
// FIN SUPPRESSION PRODUIT
//***************************
include_once('inc/front_header.inc.php');
include_once('inc/front_nav.inc.php');

?>
<div class="container">

  <!-- Outer Row -->
  <div class="row justify-content-center">
    <div class="col-xl-12 col-lg-12 col-md-9">
      <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0 row">
            <div class="col-lg-5 p-3">
                <div class="profile-img col-10 mx-auto">
                    <form method="post">
                      <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS52y5aInsxSm31CvHOFHWujqUx_wWTS9iM6s7BAm21oEN_RiGoog" alt=""/>
                          <div class="file btn btn-lg btn-primary">
                              Change Photo
                              <input type="file" name="file"/>
                          </div>        
                    </form>
                </div>
                <div>
                  <?php
                    echo '
                          <ul>
                            <li class="list-group-item bg-primary text-white">Vos informations</li>
                            <li class="list-group-item"><span class="infos_profil font-weight-bold">Pseudo: </span>' . $_SESSION['utilisateur']['pseudo'] . '</li>
                            <li class="list-group-item"><span class="infos_profil font-weight-bold">Nom: </span>' . $_SESSION['utilisateur']['nom'] . '</li>
                            <li class="list-group-item"><span class="infos_profil font-weight-bold">Prénom: </span>' . $_SESSION['utilisateur']['prenom'] . '</li>
                            <li class="list-group-item"><span class="infos_profil font-weight-bold">Email: </span>' . $_SESSION['utilisateur']['email'] . '</li>
                            <li class="list-group-item"><span class="infos_profil font-weight-bold">Sexe: </span>' . $_SESSION['utilisateur']['civilite'] . '</li>
                            <li class="list-group-item"><span class="infos_profil font-weight-bold">Telephone: </span>' . $_SESSION['utilisateur']['telephone'] . '</li>
                          </ul>';

                  ?>
              </div>
            </div>
            <div class="col-lg-7 text-center">
              <div class="col-12 p-3 row">
                <div class="col-8 text-left">
                  <h1 class=""><?php echo $_SESSION['utilisateur']['prenom'] . ' ' . $_SESSION['utilisateur']['nom'] ?></h1>
                  <p><?php echo $moyenneNotes['notation'] . ' / 5' ?></p>
                </div>
                <div class="col-3 text-right">
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                      Modifier Profil
                    </button>
                </div>
                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Modifier le profil</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                          <form method="post" action="" enctype="multipart/form-data" >
                          <!-- champs caché pour la récupération de l'id_article lors d'une modification-->
                          <input type="hidden" name="id_membre" id="id_membre" value="<?php echo $id_membre; ?>">

                            <div class="form-group">
                              <label for="pseudo">Pseudo</label>
                              <input type="text" class="form-control" id="pseudo" name="pseudo" value="<?php echo $pseudo; ?>">
                            </div>
                              <div class="form-group">
                              <label for="mdp">Modifier mot de passe</label>
                              <input type="password" class="form-control" id="mdp" name="mdp" value="<?php //echo $mdp; ?>">
                            </div>
                            <div class="form-group">
                              <label for="mdpConfirm">Confirmez le mot de passe</label>
                              <input type="password" class="form-control" id="mdpConfirm" name="mdpConfirm" value="<?php //echo $mdpConfirm; ?>">
                            </div>
                            <div class="form-group">
                              <label for="titre">Nom</label>
                              <input type="text" class="form-control" id="nom" name="nom" value="<?php echo $nom; ?>">
                            </div>
                            <div class="form-group">
                              <label for="titre">Prénom</label>
                              <input type="text" class="form-control" id="prenom" name="prenom" value="<?php echo $prenom; ?>">
                            </div>
                            <div class="form-group">
                              <label for="civilite">Civilité</label>
                              <select class="form-control" id="civilite" name="civilite">
                                <option value="m">masculin</option>
                                <option value="f" <?php if($civilite == 'f') echo 'selected'; ?> >féminin</option>
                              </select>
                            </div>
                            <div class="form-group">
                              <label for="telephone">Téléphone</label>
                              <input type="text" class="form-control" id="telephone" name="telephone" value="<?php echo $telephone; ?>">
                            </div>	
                            <div class="form-group">
                              <label for="email">Email</label>
                              <input type="text" class="form-control" id="email" name="email" value="<?php echo $email; ?>">
                            </div>
                            <hr>
                            <input type="submit" class="form-control btn btn-primary" id="enregistrement" name="enregistrement" value="Enregistrement">		
                          </form>

                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="profile-head">
                <ul class="nav nav-tabs">
                  <li class="nav-item">
                  <a href="?action=afficher_annonce" class="nav-link">Mes annonces</a>
                  </li>
                  <li class="nav-item">
                  <a href="?action=afficher_note"  class="nav-link">Mes notes</a>
                  </li>
                  <li class="nav-item">
                  <a href="?action=afficher_commentaire" class="nav-link">Mes commentaires</a>
                  </li>
                </ul>
              </div>
              <div class="p-2">
                  
                <?php
                    
                   
                  if(isset($_GET['action']) && $_GET['action'] == 'afficher_annonce') { 
                    $i = 0;
                    $recup_annonce = $pdo->prepare("SELECT * FROM annonce WHERE membre_id = :membre_id");
                    $recup_annonce->bindParam(':membre_id', $_SESSION['utilisateur']['id_membre'], PDO::PARAM_STR);
                    $recup_annonce->execute();
                    
                    $infos_annonce = $recup_annonce->fetchAll(PDO::FETCH_ASSOC);
                    echo '<h4 class="list-group-item bg-primary text-white">Vos annonces</h4>';
                    echo '<div class="col-lg-12 col-md-12 d-flex flex-row flex-wrap">';
                      while ($i < count($infos_annonce)) {
                        echo '<div class="col-lg-6 col-md-10 p-2">';
                        echo '<div class="card h-60">';
                        echo '<img src="' . URL . $infos_annonce[$i]['photo'] . '" alt="image produit" class="img-fluid">';
                        echo '<h4 class="card-title"><b>' . $infos_annonce[$i]['titre'] . '</b></h4>';
                        echo '<p class="card-text">' . $infos_annonce[$i]['description_courte'] . '</p>';
                        echo '<h5>' . $infos_annonce[$i]['prix'] . ' €</h5>';
                        echo '<a href="?action=suppression&id_annonce=' . $infos_annonce[$i]['id_annonce'] . '" onclick="return(confirm(\'Etes vous sûr ?\'));" class="btn btn-danger"><i class="fas fa-trash-alt"></i></a>';
                        echo '<a href="fiche_annonce.php?id_annonce=' . $infos_annonce[$i]['id_annonce'] . '" class="btn btn-primary"><i class="fas fa-search-plus"></i></a>';
                        echo '</div>';
                        echo '</div>';
                        $i++;
                      } 
                    echo '</div>';  
                  } 
                   
                  if(isset($_GET['action']) && $_GET['action'] == 'afficher_note') { 
                    $i = 0;
                    $recup_note = $pdo->prepare("SELECT n.*, pseudo FROM note n JOIN membre ON id_membre = n.membre_id1 WHERE membre_id2 = :membre_id2 ORDER BY date_enregistrement DESC");
                    $recup_note->bindParam(':membre_id2', $_SESSION['utilisateur']['id_membre'], PDO::PARAM_STR);
                    $recup_note->execute();

                    $infos_note = $recup_note->fetchAll(PDO::FETCH_ASSOC);
                    echo '<p><button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample2" aria-expanded="false" aria-controls="collapseExample">Notes envoyes</button> <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample3" aria-expanded="false" aria-controls="collapseExample">Notes reçus</button></p>';
                    echo '<div class="collapse" id="collapseExample3"><div class="card card-body">';
                    echo '<table class="table"><thead><tr><th scope="col">Pseudo</th><th scope="col">Avis</th><th scope="col">Date enregistrement</th><th scope="col">Note</th></tr></thead><tbody>';
                      while ($i < count($infos_note)) {
                        echo '<tr>';
                        echo '<td>' . $infos_note[$i]['pseudo'] . '</td>';
                        echo '<td>' . $infos_note[$i]['avis'] . '</td>';
                        echo '<td>' . date_format(date_create($infos_note[$i]['date_enregistrement']),"d-m-Y à H:i") . '</td>';
                        echo '<td>' . $infos_note[$i]['note'] . ' / 5</td>';
                        echo '</tr>';
                        $i++;
                      } 
                    echo '</tbody> </table>';
                    echo '</div>';
                    echo '</div>';

                    $recup_notes = $pdo->prepare("SELECT n.*, pseudo FROM note n JOIN membre ON id_membre = n.membre_id2 WHERE membre_id1 = :membre_id1 ORDER BY date_enregistrement DESC");
                    $recup_notes->bindParam(':membre_id1', $_SESSION['utilisateur']['id_membre'], PDO::PARAM_STR);
                    $recup_notes->execute();

                    $infos_notes = $recup_notes->fetchAll(PDO::FETCH_ASSOC);
                    echo '<div class="collapse" id="collapseExample2"><div class="card card-body">';
                    echo '<table class="table"><thead><tr><th scope="col">Pseudo</th><th scope="col">Avis</th><th scope="col">Date enregistrement</th><th scope="col">Note</th></tr></thead><tbody>';
                    $a = 0;
                      while ($a < count($infos_notes)) {
                        echo '<tr>';
                        echo '<td>' . $infos_notes[$a]['pseudo'] . '</td>';
                        echo '<td>' . $infos_notes[$a]['avis'] . '</td>';
                        echo '<td>' . date_format(date_create($infos_notes[$a]['date_enregistrement']),"d-m-Y à H:i") . '</td>';
                        echo '<td>' . $infos_notes[$a]['note'] . ' / 5</td>';
                        echo '</tr>';
                        $a++;
                      } 
                    echo '</tbody> </table>';
                    echo '</div>';
                    echo '</div>';
                  } 

                  if(isset($_GET['action']) && $_GET['action'] == 'afficher_commentaire') { 
                    $i = 0;  
                    $recup_commentaire = $pdo->prepare("SELECT c.commentaire, c.membre_id, c.date_enregistrement, c.annonce_id, a.id_annonce, a.titre FROM commentaire c JOIN annonce a ON c.annonce_id = a.id_annonce WHERE c.membre_id = :membre_id ORDER BY date_enregistrement DESC");
                    $recup_commentaire->bindParam(':membre_id', $_SESSION['utilisateur']['id_membre'], PDO::PARAM_STR);
                    $recup_commentaire->execute();

                    $infos_commentaire = $recup_commentaire->fetchAll(PDO::FETCH_ASSOC);
                    echo '<p><button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample"> Commentaires envoyes</button> <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample1" aria-expanded="false" aria-controls="collapseExample">Commentaires reçus </button></p>';
                    echo '<div class="collapse" id="collapseExample"><div class="card card-body">';
                    echo '<table class="table"><thead><tr><th scope="col">Annonce</th><th scope="col">Commentaire</th><th scope="col">Date enregistrement</th></tr></thead><tbody>';
                      while ($i < count($infos_commentaire)) {
                        echo '<tr>';
                        echo '<td>' . $infos_commentaire[$i]['titre'] . '</td>';
                        echo '<td>' . $infos_commentaire[$i]['commentaire'] . '</td>';
                        echo '<td>' . date_format(date_create($infos_commentaire[$i]['date_enregistrement']),"d-m-Y à H:i") . '</td>';
                        echo '</tr>';
                        $i++;
                      } 
                    echo '</tbody> </table>';
                    echo '</div>';
                    echo '</div>';


                    $recup_commentaires = $pdo->prepare("SELECT c.*, a.titre FROM commentaire c, annonce a WHERE annonce_id = id_annonce AND a.membre_id = :membre_id ORDER BY date_enregistrement DESC");
                    $recup_commentaires ->bindParam(':membre_id', $_SESSION['utilisateur']['id_membre'], PDO::PARAM_STR);
                    $recup_commentaires->execute();
                   
                    $a = 0;
                    $infos_commentaires = $recup_commentaires->fetchAll(PDO::FETCH_ASSOC);
                    echo '<div class="collapse" id="collapseExample1"><div class="card card-body">';
                    echo '<table class="table"><thead><tr><th scope="col">Annonce</th><th scope="col">Commentaire</th><th scope="col">Date enregistrement</th></tr></thead><tbody>';
                    while ($a < count($infos_commentaires)) {
                          echo '<tr>';
                          echo '<td>' . $infos_commentaires[$a]['titre']. '</td>';
                          echo '<td>' . $infos_commentaires[$a]['commentaire'] . '</td>';
                          echo '<td>' . date_format(date_create($infos_commentaires[$a]['date_enregistrement']),"d-m-Y à H:i") . '</td>';
                          echo '</tr>';
                      $a++;
                    } 
                    echo '</tbody> </table>';
                    echo '</div>';
                    echo '</div>';
                  } 
                ?>
                
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