
<?php
include_once('../inc/init.inc.php');

if(!user_is_admin()) {
  //si l'utilisateur n'est pas connecté
  header("location:../index.php");
}


// tableau
if(isset($_GET['categorie'])) {
	/*
	$liste_annonce = $pdo->prepare("
		SELECT a.id_annonce, a.titre, a.description_courte, a.description_longue, a.prix, a.photo, a.ville, a.pays, a.adresse, a.cp, a.date_enregistrement, c.titre, c.id_categorie,  m.prenom
		FROM annonce a		
		LEFT JOIN membre m
		ON a.membre_id = m.id_membre
		LEFT JOIN categorie c
		ON a.categorie_id = c.id_categorie
		WHERE a.categorie_id = :categorie");	
	*/
	$liste_annonce = $pdo->prepare("SELECT id_annonce, annonce.titre, description_courte, description_longue, prix, photo, pays, ville, adresse, cp, membre.pseudo, categorie.titre AS categorie_id, annonce.date_enregistrement  FROM annonce, categorie, membre WHERE id_categorie = categorie_id AND membre_id = id_membre AND annonce.categorie_id = :categorie");

	$liste_annonce->bindParam(':categorie', $_GET['categorie'], PDO::PARAM_STR);
	$liste_annonce->execute();		
} else {

		$liste_annonce = $pdo->query("SELECT id_annonce, annonce.titre, description_courte, description_longue, prix, photo, pays, ville, adresse, cp, membre.pseudo, categorie.titre AS categorie_id, annonce.date_enregistrement  FROM annonce, categorie, membre WHERE id_categorie = categorie_id AND membre_id = id_membre");
}
		

		$liste_categorie = $pdo->query("SELECT * FROM categorie");
		$liste_categorieDeux = $pdo->query("SELECT * FROM categorie");
		

	/*	if(isset($_GET['categorie'])) {
			$liste_produit = $pdo->prepare("SELECT * FROM annonce WHERE categorie_id = :categorie_id");
			$liste_produit->bindParam(":categorie_id", $_GET['categorie'], PDO::PARAM_STR);
			$liste_produit->execute();
		} else {
			$liste_produit = $pdo->query("SELECT * FROM annonce");
		}*/


/********************************************************************************** */

		//suppression de l'annonce

		if(isset($_GET['categorie']) && $_GET['categorie'] == 'suppression' && isset($_GET['id_annonce']) && is_numeric($_GET['id_annonce'])) {
			
		
			$suppression = $pdo->prepare("DELETE FROM annonce WHERE id_annonce = :id_annonce");
			$suppression->bindParam(':id_annonce', $_GET['id_annonce'], PDO::PARAM_STR);
			$suppression->execute();
			header('location:gestion-des-annonces.php');

}
/************************************************************************************* */
	  //modification de l'annonce

	  		

	  $titre = '';
  $description_courte = '';
  $description_longue = '';
  $prix = '';
  $photo = '';
  $pays = '';
  $ville = '';
  $adresse = '';
  $cp = '';
  $photo_id = '';
  $categorie_id = '';

  $photo_bdd1 = '';
  $photo_bdd2 = '';
  $photo_bdd3 = '';
  $photo_bdd4 = '';
  $photo_bdd5 = '';


		// récup

		if(isset($_GET['action']) && $_GET['action'] == 'modification' ) {
      echo '<style>.disap { display:none;}</style>';

			$recup_annonce = $pdo->prepare("SELECT * FROM annonce, photo WHERE id_annonce = :id_annonce AND photo_id = id_photo");
			$recup_annonce->bindParam(':id_annonce', $_GET['id_annonce'], PDO::PARAM_STR);
			$recup_annonce->execute();

			
			
			if($recup_annonce->rowCount() > 0) {
				$infos_annonce = $recup_annonce->fetch(PDO::FETCH_ASSOC);
				$id_annonce = $infos_annonce['id_annonce'];
				$titre = $infos_annonce['titre'];
				$description_courte = $infos_annonce['description_courte'];
				$description_longue =  $infos_annonce['description_longue']; 
				$prix = $infos_annonce['prix'];
				$photo = $infos_annonce['photo'];
				$pays = $infos_annonce['pays'];
				$ville = $infos_annonce['ville'];
				$adresse = $infos_annonce['adresse'];
				$cp = $infos_annonce['cp'];
				$photo_id = $infos_annonce['photo_id'];
				$categorie_id = $infos_annonce['categorie_id'];

				$photo1 = $infos_annonce['photo1'];
				$photo2 = $infos_annonce['photo2'];
				$photo3 = $infos_annonce['photo3'];
				$photo4 = $infos_annonce['photo4'];
				$photo5 = $infos_annonce['photo5'];
				
			}
		}
				 
      //modif

      
      if(isset($_POST['titre']) && isset($_POST['description_courte']) && isset($_POST['description_longue']) && isset($_POST['prix']) && isset($_FILES['photo']) && isset($_POST['pays']) && isset($_POST['ville']) && isset($_POST['adresse']) && isset($_POST['cp']) ) {

        // vérification de l'extension photo
        if(!empty($_POST['photoactuelle1'])) {
          $photo_bdd1 = $_POST['photoactuelle1'];
        }
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
        if(!empty($_POST['photoactuelle2'])) {
          $photo_bdd2 = $_POST['photoactuelle2'];
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
        if(!empty($_POST['photoactuelle3'])) {
          $photo_bdd3 = $_POST['photoactuelle3'];
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
        if(!empty($_POST['photoactuelle4'])) {
          $photo_bdd4 = $_POST['photoactuelle4'];
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
        if(!empty($_POST['photoactuelle5'])) {
          $photo_bdd5 = $_POST['photoactuelle5'];
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
        if(!empty($_POST['photoactuelle'])) {
          $photo_bdd = $_POST['photoactuelle'];
        }
        if(!empty($_FILES['photo']['name'])) {        
          if(verif_extension($_FILES['photo']['name'])) {
              $nom_photo = time() . '-' . $_FILES['photo']['name'];
              $photo_bdd = 'photo/' . $nom_photo; // src que l'on va enregistrer dans la BDD
              $photo_dossier = RACINE_SERVEUR . $photo_bdd; // l'emplacement où on va copier la photo
              copy($_FILES['photo']['tmp_name'], $photo_dossier);          
          } else {
              $msg .= '<div class="alert alert-danger mt-2" role="alert">Attention, l\'extension de la photo principal n\'est pas valide, extensions acceptées: png / jpg / jpeg / gif.<br>Veuillez recommencer</div>';
            }  
        }  else {
          $msg .= '<div class="alert alert-danger mt-2" role="alert">Merci d\'ajouter la photo principal<br>Veuillez recommencer</div>';}
  
          //empecher d"écraseeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeerrrrrrrrrrrrrrrrrrrrrrrrrrrr
        
          $modifPhoto = $pdo->prepare("UPDATE photo SET photo1=:photo1, photo2=:photo2, photo3=:photo3, photo4=:photo4, photo5=:photo5 WHERE id_photo = :id_photo");
          $modifPhoto->bindValue(':photo1', $photo_bdd1, PDO::PARAM_STR);
          $modifPhoto->bindValue(':photo2', $photo_bdd2, PDO::PARAM_STR);
          $modifPhoto->bindValue(':photo3', $photo_bdd3 , PDO::PARAM_STR);
          $modifPhoto->bindValue(':photo4', $photo_bdd4, PDO::PARAM_STR);
          $modifPhoto->bindValue(':photo5', $photo_bdd5, PDO::PARAM_STR);
          $modifPhoto->bindValue(':id_photo', $photo_id, PDO::PARAM_STR);
          $modifPhoto->execute();
  
            $photo_id = $pdo->lastInsertId();
      
  
     
        




       // if(empty($msg)) {
        $modifAnnonce = $pdo->prepare("UPDATE annonce  SET  titre= :titre, description_courte=:description_courte, description_longue=:description_longue, prix=:prix, photo = :photo, pays=:pays,ville=:ville, cp=:cp, adresse=:adresse  WHERE id_annonce=:id_annonce ");
    
        $modifAnnonce->bindValue(':titre', $_POST['titre'], PDO::PARAM_STR);
        $modifAnnonce->bindValue(':description_courte', $_POST['description_courte'], PDO::PARAM_STR);
        $modifAnnonce->bindValue(':description_longue', $_POST['description_longue'], PDO::PARAM_STR);
        $modifAnnonce->bindValue(':prix', $_POST['prix'], PDO::PARAM_INT);
        $modifAnnonce->bindParam(':photo', $photo_bdd, PDO::PARAM_STR);
        $modifAnnonce->bindValue(':pays', $_POST['pays'], PDO::PARAM_STR);
        $modifAnnonce->bindValue(':ville', $_POST['ville'], PDO::PARAM_STR);
        $modifAnnonce->bindValue(':cp', $_POST['cp'], PDO::PARAM_INT);
        $modifAnnonce->bindValue(':adresse', $_POST['adresse'], PDO::PARAM_STR);
        $modifAnnonce->bindValue(':id_annonce', $_GET['id_annonce'], PDO::PARAM_INT);
        $modifAnnonce->execute();
        
        header('location:gestion-des-annonces.php');

       // }
    
    }


		 
		
	



include_once('back_head.inc.php');
include_once('back_nav.inc.php');


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
 <div class="container m-2">
		<div class="row disap">
				<div class="col-6 disap">
									<?php	 

									
									echo '<div class="dropdown">';
									echo '<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    							trier par categorie
  								</button>';

                  echo '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';
                  
                  echo '<a href="gestion-des-annonces.php">Toutes les catégories</a>' . '<br>'; 	

									while($ligne = $liste_categorie->fetch(PDO::FETCH_ASSOC)) {
									
									echo '<a href="?categorie=' . $ligne['id_categorie'] . '">' . $ligne['titre'] . '</a>' . '<br>'; }	
									
									echo '</div></div>';
									
									?>
														
          </div>   
  	</div> 	
	</div>	       
</body>
</html>



<?php
echo ' <div class="container-fluid m-0 p-0">
<div class="row p-0">';

echo '<div class="col-6 disap">';
		echo '<table class="table table-bordered">';

		

		echo '<tr>
		<th>Id annonce</th>
		<th>titre</th>
		<th>description courte</th>
		<th>description longue</th>
		<th>prix</th>
		<th>photo</th>
		<th>pays</th>
		<th>ville</th>
		<th>adresse</th>
		<th>cp</th>
		<th>membre</th>
		<th>categorie</th>
        <th>date d\'enregistrement</th>
        <th>actions</th>
		</tr>';

		while($ligneGestion = $liste_annonce->fetch(PDO::FETCH_ASSOC)) {
			//chaque tour de boucle permet d'afficher un produit dans le tableau html

			echo '<tr>';

				foreach($ligneGestion AS $indice => $valeur) {
						if($indice == 'photo') {
							echo '<td><img src = "' . URL .  $valeur . '"alt="img produit" class= "img-fluid" style= "max-width: 80px;">	
							</td>';
						} elseif($indice == 'description_longue') {
              echo '<td>' . substr($valeur, 0, 8) . '<a href="">...</a></td>';
             } elseif($indice == 'description_courte') {
                echo '<td>' . substr($valeur, 0, 3) . '<a href="">...</a></td>';
						} elseif($indice == 'photo_id') {
							echo '<style> display: none </style>';
						} 
						else{
					echo '<td>' . $valeur . '</td>';}
				}
			echo '<td><a href = "?action=modification&id_annonce=' . $ligneGestion['id_annonce'] . '" class="btn btn-warning"><i class="fas fa-sync"></i></a>';
            echo '<a href = "?categorie=suppression&id_annonce=' . $ligneGestion['id_annonce'] . '"  onclick="return(confirm(\'Etes vous sûr?\'))"  class="btn btn-danger"><i class="fas fa-trash-alt"></i></a>';
            echo '<a href = "../fiche_annonce.php?id_annonce=' . $ligneGestion['id_annonce'] . '"    class="btn btn-success"><i class="fas fa-search"></i></a></td>';	
			echo '</tr>';
		}

		echo '</table>';
        echo '</div>';

				echo '</div>';
				echo '</div>';
				
			
        


 
if(isset($_GET['action']) && ($_GET['action'] == 'modification')) { 
?>

	<div class="container">

<!-- Outer Row -->
<div class="row justify-content-center">

  <div class="col-xl-10 col-lg-12 col-md-9">
    <div class="card o-hidden border-0 shadow-lg my-5">
      <div class="card-body p-0">
      <!-- -->
      <div class="row">
            
        <div class="col-lg-12 text-center">
          <div class="p-2 ">
            <h1 class="h1 text-gray-900 mb-4">modification de l'annonce</h1>
            <p class="lead"><?php echo $msg; ?></p>
          </div>
        </div>
          <div class="col-lg-6">
            <div class="p-5">
              <div class="form-group">   

              <form class="user" method="post" action="" enctype="multipart/form-data">
               
                <input type="hidden" id="photo_id" name="photo_id" value="<?php echo $photo_id; ?>">

                <input type="hidden" id="membre_id" name="membre_id" value="<?php echo $membre_id; ?>">

                <label for="titre">Titre</label>
                <input type="text" class="form-control" id="titre" name="titre" placeholder="Titre de l'annonce..."  value="<?php echo $titre; ?>">
              </div>
              <div class="form-group">
                <label for="description_courte">Description courte</label>
                <textarea class="form-control" name="description_courte" id="description_courte"  placeholder="Description courte de votre annonce..." ><?php echo $description_courte; ?></textarea>
              </div>
              <div class="form-group">
                <label for="description_longue">Description courte</label>
                <textarea class="form-control" name="description_longue" id="description_longue"  placeholder="Description longue de votre annonce..." ><?php echo $description_longue; ?></textarea>
              </div>
              <div class="form-group">
                <label for="prix">Prix</label>
                <input type="text" class="form-control" id="prix" name="prix" placeholder="Prix figurant dans l'annonce..."  value="<?php echo $prix; ?>">
              </div>
              <div class="form-group">
                <label for="categorie_id">Catégorie</label>
                <select class="form-control" id="categorie_id" name="categorie_id">

                <?php 
                while($ligne = $liste_categorieDeux->fetch(PDO::FETCH_ASSOC)) {

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
                <input type="hidden" name="photoactuelle" value="<?php echo $photo ?>">
              </div>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="p-5">
              <div class="form-group">
               <label for="photo">Photo Suplémentaire</label>

                <input type="file" name="photo1" id="photo1" >
                <input type="hidden" name="photoactuelle1" value="<?php echo $photo1 ?>">

                <input type="file" name="photo2" id="photo2" >
                <input type="hidden" name="photoactuelle2" value="<?php echo $photo2 ?>">

                <input type="file" name="photo3" id="photo3" >
                <input type="hidden" name="photoactuelle3" value="<?php echo $photo3 ?>">

                <input type="file" name="photo4" id="photo4" >
                <input type="hidden" name="photoactuelle4" value="<?php echo $photo4 ?>">

                <input type="file" name="photo5" id="photo5" >
                <input type="hidden" name="photoactuelle5" value="<?php echo $photo5 ?>">

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
                <textarea class="form-control" name="adresse" id="adresse"  placeholder="Adresse figurant dans l'annonce..."><?php echo $adresse; ?></textarea>
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

			<?php } ?>
 












<?php

include_once('back_footer.inc.php');

