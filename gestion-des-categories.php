
<?php
include_once('../inc/init.inc.php');

if(!user_is_admin()) {
  //si l'utilisateur n'est pas connecté
  header("location:../index.php");
}


$liste_cate = $pdo->query("SELECT * FROM categorie");


/********************************************************************************** */

		//ajout d'une categorie
  

if(isset($_POST['titre'], $_POST['motsCles'])) {
	$ajoutCat = $pdo->prepare("INSERT INTO categorie(titre, motscles) VALUES (:titre, :motsCles)");
	$ajoutCat->bindParam(':titre', $_POST['titre'], PDO::PARAM_STR);
	$ajoutCat->bindParam(':motsCles', $_POST['motsCles'], PDO::PARAM_STR);
	$ajoutCat->execute();

	header('location:gestion-des-categories.php');
}
 


/********************************************************************************** */

		//modification de la categorie

			// récup

			if(isset($_GET['action']) && $_GET['action'] == 'modification' ) {

				echo '<style>.disap { display:none;}</style>';

	
				$recup_cate = $pdo->prepare("SELECT * FROM categorie WHERE id_categorie = :id_categorie");
				$recup_cate->bindParam(':id_categorie', $_GET['id_categorie'], PDO::PARAM_STR);
				$recup_cate->execute();
	
			
				
				
				if($recup_cate->rowCount() > 0) {
					$infos_cate = $recup_cate->fetch(PDO::FETCH_ASSOC);
					$titreCate = $infos_cate['titre'];
					$motsCles = $infos_cate['motscles'];

				  }
			

			//changement

			if(isset($_POST['Titre'], $_POST['mots_cles'])) {
				$modifCat = $pdo->prepare("UPDATE categorie SET titre = :titre, motscles = :motsCles WHERE id_categorie = :id_categorie");
				$modifCat->bindParam(':titre', $_POST['Titre'], PDO::PARAM_STR);
				$modifCat->bindParam(':motsCles', $_POST['mots_cles'], PDO::PARAM_STR);
				$modifCat->bindParam(':id_categorie', $_GET['id_categorie'], PDO::PARAM_STR);
				$modifCat->execute();

				header('location:gestion-des-categories.php');

		}

	}
/********************************************************************************** */

		//suppression de la categorie

		if(isset($_GET['categorie']) && $_GET['categorie'] == 'suppression' && isset($_GET['id_categorie']) && is_numeric($_GET['id_categorie'])) {
			
		
			$suppression = $pdo->prepare("DELETE FROM categorie WHERE id_categorie = :id_categorie");
			$suppression->bindParam(':id_categorie', $_GET['id_categorie'], PDO::PARAM_STR);
			$suppression->execute();
			header('location:gestion-des-categories.php');

}
/************************************************************************************* */



include_once('back_head.inc.php');
include_once('back_nav.inc.php');


?>

<?php
 
echo '<div class="row disap">';

echo '<div class="col-10 disap">';
		echo '<table class="table table-bordered" style=" margin:10px;">';

		

		echo '<tr>
		<th>Id categorie</th>
		<th>titre</th>
		<th>mots cles</th>
		<th>actions</th>
		</tr>';

		while($ligneCate = $liste_cate->fetch(PDO::FETCH_ASSOC)) {

			echo '<tr>';
			foreach($ligneCate AS $indice => $valeur) {
				
			echo '<td>' . $valeur . '</td>';
		}

				
			echo '<td><a href = "?action=modification&id_categorie=' . $ligneCate['id_categorie'] . '" class="btn btn-warning"><i class="fas fa-sync"></i></a>';
            echo '<a href = "?categorie=suppression&id_categorie=' . $ligneCate['id_categorie'] . '"  onclick="return(confirm(\'Etes vous sûr?\'))"  class="btn btn-danger"><i class="fas fa-trash-alt"></i></a>';
			echo '</tr>';
		}

		echo '</table>';
        echo '</div>';

				echo '</div>';
                
    ?>

<div class="col-6 m-5 disap">
    <p><b>Ajouter une catégorie<b></p>
<div class="form-group">   

<form class="user" method="post" action="" enctype="multipart/form-data">

                <label for="titre">Titre</label>
                <input type="text" class="form-control" id="titreCategorie" name="titre" placeholder="Titre de l'annonce..."  value="">

                <label for="titre">mots cles</label>
                <input type="text" class="form-control" id="motsCles" name="motsCles" placeholder="mots cles..."  value="">

                <div class="p-2">
                <input type="submit" class="form-control btn btn-primary btn-user btn-block" onclick="return(confirm('Ajouter une catégorie?'))" id="enregistrer" name="enregistrer" value="Enregistrer">	
                </div>

</form>

</div>

</div>
<!--Formulaire pour la modification -->
<?php
if(isset($_GET['action']) && ($_GET['action'] == 'modification')) { ?>

	<div class="col-6">
	<p><b>Modification de la catégorie<b></p>
	<div class="form-group">   
	
	<form class="user" method="post" action="" enctype="multipart/form-data">
	
									<label for="titre">Titre</label>
									<input type="text" class="form-control" id="titreCategorie" name="Titre" placeholder="Titre de l'annonce..."  value="<?php echo $titreCate?>">
	
									<label for="titre">mots cles</label>
									<input type="text" class="form-control" id="motsCles" name="mots_cles" placeholder="mots cles..."  value="<?php echo $motsCles?>">
	
									<div class="p-2">
									<input type="submit" class="form-control btn btn-primary btn-user btn-block" onclick="return(confirm('Modifier la catégorie?'))"  id="enregistrer" name="enregistrer" value="Enregistrer">	
									</div>
	
	</form>
	
	</div>
	
	</div>
	<?php } ?>

<?php

include_once('back_footer.inc.php');

