
<?php
include_once('../inc/init.inc.php');

if(!user_is_admin()) {
	//si l'utilisateur n'est pas connecté
	header("location:../index.php");
}
//Affichage


$liste_commentaire = $pdo->query("SELECT * FROM commentaire");



/********************************************************************************** */

		//suppression du commentaire

		if(isset($_GET['action']) && $_GET['action'] == 'suppression' && isset($_GET['id_commentaire']) && is_numeric($_GET['id_commentaire'])) {
			
		
			$suppression = $pdo->prepare("DELETE FROM commentaire WHERE id_commentaire = :id_commentaire");
			$suppression->bindParam(':id_commentaire', $_GET['id_commentaire'], PDO::PARAM_STR);
			$suppression->execute();
			header('location:gestion-des-commentaires.php');

}
/********************************************************************************** */

		//modif du commentaire

			// récup

			if(isset($_GET['action']) && $_GET['action'] == 'modification' ) {

				echo '<style>.disap { display:none;}</style>';

	
				$recup_comment = $pdo->prepare("SELECT * FROM commentaire WHERE id_commentaire = :id_commentaire");
				$recup_comment->bindParam(':id_commentaire', $_GET['id_commentaire'], PDO::PARAM_STR);
				$recup_comment->execute();
	
				
				
				if($recup_comment->rowCount() > 0) {
					$infos_comment = $recup_comment->fetch(PDO::FETCH_ASSOC);
					$id_commentaire = $infos_comment['id_commentaire'];
					$membre_id = $infos_comment['membre_id'];
					$annonce_id = $infos_comment['annonce_id'];
					$commentaire =  $infos_comment['commentaire']; 
					$date_enregistrement = $infos_comment['date_enregistrement'];
						
				}
		
					 
				//modif


				if(isset($_POST['commentaire'])) {
					$modifCom = $pdo->prepare("UPDATE commentaire SET commentaire = :commentaire WHERE id_commentaire = :id_commentaire");
					$modifCom->bindParam(':commentaire', $_POST['commentaire'], PDO::PARAM_STR);
					$modifCom->bindParam(':id_commentaire', $_GET['id_commentaire'], PDO::PARAM_STR);
					$modifCom->execute();
	
					header('location:gestion-des-commentaires.php');
	
			}
	}

include_once('back_head.inc.php');
include_once('back_nav.inc.php');



?>

<?php
echo '<div class="row disap">';

echo '<div class="col-10 disap">';
		echo '<table class="table table-bordered" style=" margin:10px;">';

		

		echo '<tr>
		<th>Id commentaire</th>
		<th>id membre</th>
		<th>id annonce</th>
		<th>commentaire</th>
		<th>date_enregistrement</th>
		<th>actions</th>
		</tr>';


		while($ligneComment = $liste_commentaire->fetch(PDO::FETCH_ASSOC)) {
			

			echo '<tr>';
			foreach($ligneComment AS $indice => $valeur) {
				
				echo '<td>' . $valeur . '</td>';
			}
				
			echo '<td><a href = "?action=modification&id_commentaire=' . $ligneComment['id_commentaire'] . '" class="btn btn-warning"><i class="fas fa-sync"></i></a>';
						echo '<a href = "?action=suppression&id_commentaire=' . $ligneComment['id_commentaire'] . '"  onclick="return(confirm(\'Etes vous sûr?\'))"  class="btn btn-danger"><i class="fas fa-trash-alt"></i></a>';
						echo '</tr>';
		}

		echo '</table>';
        echo '</div>';

				echo '</div>';
                
    ?>

<!--Formulaire pour la modification -->
<?php
if(isset($_GET['action']) && ($_GET['action'] == 'modification')) { ?>

	<div class="col-6">
	<p><b>Modification du commentaire<b></p>
	<div class="form-group">   
	
	<form class="user" method="post" action="" enctype="multipart/form-data">
	
									<label for="titre">id_commentaire</label>
									<input type="text" class="form-control" id="id_commentaire" name="id_commentaire"   value="<?php echo $id_commentaire?>" disabled>
	
									<label for="titre">membre_id</label>
									<input type="text" class="form-control" id="membre_id" name="membre_id"  value="<?php echo $membre_id?>" disabled>

									<label for="titre">annonce_id</label>
									<input type="text" class="form-control" id="annonce_id" name="annonce_id"  value="<?php echo $annonce_id?>" disabled>

									<label for="titre">commentaire</label>
									<input type="text" class="form-control" id="commentaire" name="commentaire"  value="<?php echo $commentaire?>">

									<label for="titre">date_enregistrement</label>
									<input type="text" class="form-control" id="date_enregistrement" name="date_enregistrement"  value="<?php echo $date_enregistrement?>" disabled>
	
									<div class="p-2">
									<input type="submit" class="form-control btn btn-primary btn-user btn-block" onclick="return(confirm('Modifier le commentaire?'))"  id="enregistrer" name="enregistrer" value="Enregistrer">	
									</div>
	
	</form>
	
	</div>
	
	</div>
	<?php } ?>

<?php

include_once('back_footer.inc.php');

