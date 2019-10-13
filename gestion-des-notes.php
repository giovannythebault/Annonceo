
<?php
include_once('../inc/init.inc.php');

if(!user_is_admin()) {
  //si l'utilisateur n'est pas connecté
	header("location:../index.php");
}


$liste_note = $pdo->query("SELECT * FROM  note");



/********************************************************************************** */

		//suppression de la note

		if(isset($_GET['note']) && $_GET['note'] == 'suppression' && isset($_GET['id_note']) && is_numeric($_GET['id_note'])) {
			
		
			$suppression = $pdo->prepare("DELETE FROM note WHERE id_note = :id_note");
			$suppression->bindParam(':id_note', $_GET['id_note'], PDO::PARAM_STR);
			$suppression->execute();
		
			header('location:gestion-des-notes.php');

}
/************************************************************************************* */

		//modif du commentaire

			// récup

			if(isset($_GET['note']) && $_GET['note'] == 'modification' ) {

				echo '<style>.disap { display:none;}</style>';

	
				$recup_note = $pdo->prepare("SELECT * FROM note WHERE id_note = :id_note");
				$recup_note->bindParam(':id_note', $_GET['id_note'], PDO::PARAM_STR);
				$recup_note->execute();
	
				
				
				if($recup_note->rowCount() > 0) {
					$infos_note = $recup_note->fetch(PDO::FETCH_ASSOC);
					$id_note = $infos_note['id_note'];
					$note = $infos_note['note'];
					$avis = $infos_note['avis'];
					$date_enregistrement =  $infos_note['date_enregistrement']; 
					$membre_id1 = $infos_note['membre_id1'];
					$membre_id2 = $infos_note['membre_id2'];
						
				}
		
					 
				//modif


				if(isset($id_note, $_POST['note'], $_POST['avis'] , $membre_id1, $membre_id2)) {
					$modifnote = $pdo->prepare("UPDATE note SET note = :note, avis = :avis WHERE id_note = :id_note");
					$modifnote->bindParam(':note', $_POST['note'], PDO::PARAM_STR);
					$modifnote->bindParam(':avis', $_POST['avis'], PDO::PARAM_STR);
					$modifnote->bindParam(':id_note', $_GET['id_note'], PDO::PARAM_STR);
					$modifnote->execute();
	
					header('location:gestion-des-notes.php');
	
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
		<th>Id note</th>
		<th>note</th>
		<th>avis</th>
		<th>date_enregistrement</th>
		<th>membre_id1</th>
		<th>membre_id2</th>
		<th>actions</th>
		</tr>';

		while($lignenote = $liste_note->fetch(PDO::FETCH_ASSOC)) {

			echo '<tr>';
			foreach($lignenote AS $indice => $valeur) {
				
        echo '<td>' . $valeur . '</td>';
      }
				
			echo '<td><a href = "?note=modification&id_note=' . $lignenote['id_note'] . '" class="btn btn-warning"><i class="fas fa-sync"></i></a>';
          echo '<a href = "?note=suppression&id_note=' . $lignenote['id_note'] . '"  onclick="return(confirm(\'Etes vous sûr?\'))"  class="btn btn-danger"><i class="fas fa-trash-alt"></i></a>';
			echo '</tr>';
		}

		echo '</table>';
        echo '</div>';

				echo '</div>';
                
    ?>
<!--Formulaire pour la modification -->
<?php
if(isset($_GET['note']) && ($_GET['note'] == 'modification')) { ?>

	<div class="col-6">
	<p><b>Modification des note<b></p>
	<div class="form-group">   
	
	<form class="user" method="post" action="" enctype="multipart/form-data">
	
									<label for="titre">id_note</label>
									<input type="text" class="form-control" id="id_note" name="id_note"   value="<?php echo $id_note?>" disabled>
	
									<div class="form-group">
									<label for="note">Note (entre 1 et 5)</label>
                      <select class="form-control" id="note" name="note">  
                        <option value="1">1</option>
                        <option value="2" <?php if($note == '2') echo 'selected'; ?> >2</option>
                        <option value="3" <?php if($note == '3') echo 'selected'; ?> >3</option>
                        <option value="4" <?php if($note == '4') echo 'selected'; ?> >4</option>
                        <option value="5" <?php if($note == '5') echo 'selected'; ?> >5</option>
                      </select>
                      </div>

									<label for="titre">avis</label>
									<input type="text" class="form-control" id="avis" name="avis"  value="<?php echo $avis?>" >

									<label for="titre">membre_id1</label>
									<input type="text" class="form-control" id="membre_id1" name="membre_id1"  value="<?php echo $membre_id1?>"disabled>

									<label for="titre">membre_id2</label>
									<input type="text" class="form-control" id="membre_id2" name="membre_id2"  value="<?php echo $membre_id2?>" disabled>
	
									<div class="p-2">
									<input type="submit" class="form-control btn btn-primary btn-user btn-block" onclick="return(confirm('Modifier le commentaire?'))"  id="enregistrer" name="enregistrer" value="Enregistrer">	
									</div>
	
	</form>
	
	</div>
	
	</div>
	<?php } ?>


<?php

include_once('back_footer.inc.php');

