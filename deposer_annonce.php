<?php
include_once('inc/init.inc.php');

if(!user_is_connected()) {
  //si l'utilisateur n'est pas connecté
  header("location:connexion.php");
  exit(); 
}

$liste_categorie = $pdo->query("SELECT * FROM categorie");



  $titre = '';
  $description_courte = '';
  $description_longue = '';
  $prix = '';
  $photo = '';
  $pays = '';
  $ville = '';
  $adresse = '';
  $cp = '';
  $membre_id = '';
  $photo_id = '';
  $categorie_id = '';
  
  
    if(isset($_POST['titre']) && isset($_POST['description_courte']) && isset($_POST['description_longue']) && isset($_POST['prix']) && isset($_FILES['photo']) && isset($_POST['pays']) && isset($_POST['ville']) && isset($_POST['adresse']) && isset($_POST['cp']) ) {
        
        foreach($_POST AS $indice1 => $valeur1) {
           $_POST[$indice1] = trim($_POST[$indice1]);
        }
        
        $titre = $_POST['titre'];
        $description_courte = $_POST['description_courte'];
        $description_longue =$_POST['description_longue'];
        $prix = $_POST['prix'];
        $photo = $_FILES['photo'];
        $pays = $_POST['pays'];
        $ville = $_POST['ville'];
        $adresse = $_POST['adresse'];
        $cp = $_POST['cp'];
        $membre_id = $_SESSION['utilisateur']['id_membre']; 
        $categorie_id = $_POST['categorie_id'];


        $photo_bdd1 = '';
        $photo_bdd2 = '';
        $photo_bdd3 = '';
        $photo_bdd4 = '';
        $photo_bdd5 = '';
        

        // vérification de l'extension photo
      if(empty($msg) && !empty($_FILES['photo1']['name'])) {        
        if(verif_extension($_FILES['photo1']['name'])) {
           $nom_photo = time() . '-' . $_FILES['photo1']['name'];
           $photo_bdd1 = 'photo/' . $nom_photo; // src que l'on va enregistrer dans la BDD
           $photo_dossier = RACINE_SERVEUR . $photo_bdd1; // l'emplacement où on va copier la photo
           copy($_FILES['photo1']['tmp_name'], $photo_dossier);          
         } else {
           $msg .= '<div class="alert alert-danger mt-2" role="alert">Attention, l\'extension de la photo1 n\'est pas valide, extensions acceptées: png / jpg / jpeg / gif.<br>Veuillez recommencer</div>';
         }
      }      
      if(empty($msg) && !empty($_FILES['photo2']['name'])) {        
        if(verif_extension($_FILES['photo2']['name'])) {
           $nom_photo = time() . '-' . $_FILES['photo2']['name'];
           $photo_bdd2 = 'photo/' . $nom_photo; // src que l'on va enregistrer dans la BDD
           $photo_dossier = RACINE_SERVEUR . $photo_bdd2; // l'emplacement où on va copier la photo
           copy($_FILES['photo2']['tmp_name'], $photo_dossier);          
         } else {
           $msg .= '<div class="alert alert-danger mt-2" role="alert">Attention, l\'extension de la photo2 n\'est pas valide, extensions acceptées: png / jpg / jpeg / gif.<br>Veuillez recommencer</div>';
         }
      }
      if(empty($msg) && !empty($_FILES['photo3']['name'])) {        
        if(verif_extension($_FILES['photo3']['name'])) {
           $nom_photo = time() . '-' . $_FILES['photo3']['name'];
           $photo_bdd3 = 'photo/' . $nom_photo; // src que l'on va enregistrer dans la BDD
           $photo_dossier = RACINE_SERVEUR . $photo_bdd3; // l'emplacement où on va copier la photo
           copy($_FILES['photo3']['tmp_name'], $photo_dossier);          
         } else {
           $msg .= '<div class="alert alert-danger mt-2" role="alert">Attention, l\'extension de la photo3 n\'est pas valide, extensions acceptées: png / jpg / jpeg / gif.<br>Veuillez recommencer</div>';
         }
      }
      if(empty($msg) && !empty($_FILES['photo4']['name'])) {        
        if(verif_extension($_FILES['photo4']['name'])) {
           $nom_photo = time() . '-' . $_FILES['photo4']['name'];
           $photo_bdd4 = 'photo/' . $nom_photo; // src que l'on va enregistrer dans la BDD
           $photo_dossier = RACINE_SERVEUR . $photo_bdd4; // l'emplacement où on va copier la photo
           copy($_FILES['photo4']['tmp_name'], $photo_dossier);          
         } else {
           $msg .= '<div class="alert alert-danger mt-2" role="alert">Attention, l\'extension de la photo4 n\'est pas valide, extensions acceptées: png / jpg / jpeg / gif.<br>Veuillez recommencer</div>';
         }
      }
      if(empty($msg) && !empty($_FILES['photo5']['name'])) {        
        if(verif_extension($_FILES['photo5']['name'])) {
           $nom_photo = time() . '-' . $_FILES['photo5']['name'];
           $photo_bdd5 = 'photo/' . $nom_photo; // src que l'on va enregistrer dans la BDD
           $photo_dossier = RACINE_SERVEUR . $photo_bdd5; // l'emplacement où on va copier la photo
           copy($_FILES['photo5']['tmp_name'], $photo_dossier);          
         } else {
           $msg .= '<div class="alert alert-danger mt-2" role="alert">Attention, l\'extension de la photo5 n\'est pas valide, extensions acceptées: png / jpg / jpeg / gif.<br>Veuillez recommencer</div>';
         }
      }


      if(empty($msg) && !empty($_FILES['photo1']['name'])) {
          $photo_annexe = $pdo->prepare("INSERT INTO photo (photo1, photo2, photo3, photo4, photo5) VALUES (:photo1, :photo2, :photo3, :photo4, :photo5)");
          $photo_annexe->bindParam(':photo1', $photo_bdd1, PDO::PARAM_STR);
          $photo_annexe->bindParam(':photo2', $photo_bdd2, PDO::PARAM_STR);
          $photo_annexe->bindParam(':photo3', $photo_bdd3, PDO::PARAM_STR);
          $photo_annexe->bindParam(':photo4', $photo_bdd4, PDO::PARAM_STR);
          $photo_annexe->bindParam(':photo5', $photo_bdd5, PDO::PARAM_STR);
          $photo_annexe->execute();

          $photo_id = $pdo->lastInsertId();
      }   else {
          $msg .= '<div class="alert alert-danger mt-2" role="alert">Merci d\'ajouter au moins une photo complementaire</div>';
      }

      if(!empty($_FILES['photo']['name'])) {        
        if(verif_extension($_FILES['photo']['name'])) {
            $nom_photo = time() . '-' . $_FILES['photo']['name'];
            $photo_bdd = 'photo/' . $nom_photo; // src que l'on va enregistrer dans la BDD
            $photo_dossier1 = RACINE_SERVEUR . $photo_bdd; // l'emplacement où on va copier la photo
            copy($_FILES['photo']['tmp_name'], $photo_dossier1);          
          } else {
            $msg .= '<div class="alert alert-danger mt-2" role="alert">Attention, l\'extension de la photo principal n\'est pas valide, extensions acceptées: png / jpg / jpeg / gif.<br>Veuillez recommencer</div>';
          }  
      }  else {
        $msg .= '<div class="alert alert-danger mt-2" role="alert">Merci d\'ajouter la photo principal<br>Veuillez recommencer</div>';
      }
    
      
        if (empty($msg)) {

          // enregistrement dans la BDD
          $enregistrement1 = $pdo->prepare("INSERT INTO annonce (membre_id, titre, description_courte, description_longue, prix, photo, pays, ville, adresse, cp, photo_id, categorie_id, date_enregistrement) VALUES ($membre_id, :titre, :description_courte, :description_longue, :prix, :photo, :pays, :ville, :adresse, :cp, $photo_id, :categorie_id, NOW())");
          $enregistrement1->bindParam(':titre', $titre, PDO::PARAM_STR);
          $enregistrement1->bindParam(':description_courte', $description_courte, PDO::PARAM_STR);
          $enregistrement1->bindParam(':description_longue', $description_longue, PDO::PARAM_STR);
          $enregistrement1->bindParam(':prix', $prix, PDO::PARAM_STR);
          $enregistrement1->bindParam(':photo', $photo_bdd, PDO::PARAM_STR);
          $enregistrement1->bindParam(':pays', $pays, PDO::PARAM_STR);
          $enregistrement1->bindParam(':ville', $ville, PDO::PARAM_STR);
          $enregistrement1->bindParam(':adresse', $adresse, PDO::PARAM_STR);
          $enregistrement1->bindParam(':cp', $cp, PDO::PARAM_STR);
          $enregistrement1->bindParam(':categorie_id', $categorie_id, PDO::PARAM_STR);
          $enregistrement1->execute();
          $msg .= '<div class="alert alert-primary mt-2" role="alert">Votre annonce est bien enregister Merci</div>';
        }  
    }
 // print_r($_SESSION['utilisateur']['id_membre']);  
include_once('inc/front_header.inc.php');
include_once('inc/front_nav.inc.php');

//echo '<pre>'; print_r($_SESSION); echo '</pre>';
?>
  <div class="container">

<!-- Outer Row -->
<div class="row justify-content-center">

  <div class="col-xl-12 col-lg-12 col-md-9">
    <div class="card o-hidden border-0 shadow-lg my-5">
      <div class="card-body p-0">
      <!-- -->
      <div class="row">
            
        <div class="col-lg-12 text-center">
          <div class="p-2 ">
            <h1 class="h1 text-gray-900 mb-4">Déposer une annonce</h1>
            <p class="lead"><?php echo $msg; ?></p>
          </div>
        </div>
          <div class="col-lg-6">
            <div class="p-5">
              <div class="form-group">   

              <form class="user" method="post" action="" enctype="multipart/form-data">

                <input type="hidden" id="membre_id" value="<?php echo $membre_id; ?>">

                <label for="titre">Titre</label>
                <input type="text" class="form-control" id="titre" name="titre" placeholder="Titre de l'annonce..."  value="<?php echo $titre; ?>">
              </div>
              <div class="form-group">
                <label for="description_courte">Description courte</label>
                <textarea class="form-control" name="description_courte" id="description_courte"  placeholder="Description courte de votre annonce..." value="<?php echo $description_courte; ?>"></textarea>
              </div>
              <div class="form-group">
                <label for="description_longue">Description longue</label>
                <textarea class="form-control" name="description_longue" id="description_longue"  placeholder="Description longue de votre annonce..." value="<?php echo $description_longue; ?>"></textarea>
              </div>
              <div class="form-group">
                <label for="prix">Prix</label>
                <input type="text" class="form-control" id="prix" name="prix" placeholder="Prix figurant dans l'annonce..."  value="<?php echo $prix; ?>">
              </div>
              <div class="form-group">
                <label for="categorie_id">Catégorie</label>
                <select class="form-control" id="categorie_id" name="categorie_id">

                <?php 
                while($ligne = $liste_categorie->fetch(PDO::FETCH_ASSOC)) {

                  $selected = '';
                  if($categorie_id == $ligne['id_categorie']) {
                    $selected = 'selected';
                  }

                  echo '<option value="' . $ligne['id_categorie'] . '" ' . $selected . '  >' . $ligne['titre'] . '</option>';
                }
                ?>
                  </select>
              </div>
              <div class="from-group">
                <label for="photo">Photo Principal</label>
                <input type="file" name="photo" id="photo">
              </div>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="p-5">
              <div class="form-group">
               <label for="photo">Photo Suplémentaire</label>
                <input type="file" name="photo1" id="photo1" >
                <input type="file" name="photo2" id="photo2" >
                <input type="file" name="photo3" id="photo3" >
                <input type="file" name="photo4" id="photo4" >
                <input type="file" name="photo5" id="photo5" >
              </div>
              <div class="form-group">
                <label for="pays">Pays</label>
                <select class="form-control" id="pays" name="pays">
                  <option value="france" >France</option>
                  <option value="italie" <?php if($pays == 'italie') echo 'selected'; ?> >Italie</option>
                  <option value="espagne" <?php if($pays == 'espagne') echo 'selected'; ?> >Espagne</option>
                </select>
              </div>
              <div class="form-group">
                <label for="ville">Ville</label>
                <select class="form-control" id="ville" name="ville">
                  <option value="">Toutes les catégories</option>
                  <option value="paris" <?php if($ville == 'paris') echo 'selected'; ?>>Paris</option>
                  <option value="orleans" <?php if($ville == 'orleans') echo 'selected'; ?>>Orléans</option>
                  <option value="marseille" <?php if($ville == 'marseille') echo 'selected'; ?>>Marseille</option>
                  <option value="bordeaux" <?php if($ville == 'bordeaux') echo 'selected'; ?>>Bordeaux</option>
                  <option value="nantes" <?php if($ville == 'nantes') echo 'selected'; ?>>Nantes</option>
                </select>
              </div>
              <div class="form-group">
                <label for="adresse">Adresse</label>
                <textarea class="form-control" name="adresse" id="adresse"  placeholder="Adresse figurant dans l'annonce..." value="<?php echo $adresse; ?>"></textarea>
              </div>
              <div class="form-group">
                <label for="cp">Code postal</label>
                <input type="text" class="form-control" id="cp" name="cp" placeholder="Code postal figurant dans l'annonce..."  value="<?php echo $cp; ?>">
              </div>
            </div>
          </div>
          <div class="col-lg-12">
            <div class="p-2">
            <input type="submit" class="form-control btn btn-primary btn-user btn-block" id="enregistrer" name="enregistrer" value="Enregistrer">	
            </div>
          </div>
        </form>

        </div>
      </div>
    </div>
  </div>

</div>

</div>

<?php

include_once('inc/front_footer.inc.php');


?>