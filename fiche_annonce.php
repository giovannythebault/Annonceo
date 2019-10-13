<?php
include_once('inc/init.inc.php');

	if (!isset($_GET['id_annonce'])) {
    header('location:index.php'); // si l'id_annonce existe dans l'url => requete en BDD pour récupérer cet annonce
	}	else {
		// récupération des informations en BDD
    $fiche_annonce = $pdo->prepare("SELECT a.id_annonce, a.titre, a.description_longue, a.prix, a.photo, a.pays, a.adresse, a.cp, a.ville, a.membre_id, a.photo_id, a.categorie_id, a.date_enregistrement, id_membre, pseudo, telephone, email, p.* FROM annonce a INNER JOIN membre ON membre_id=id_membre INNER JOIN photo p ON p.id_photo = a.photo_id WHERE id_annonce = :id_annonce");
    $fiche_annonce->bindParam(':id_annonce', $_GET['id_annonce'], PDO::PARAM_STR);
    $fiche_annonce->execute();

    // vérifier si on a récupéré un article (rowCount()) sinon header (location)
    // un fetch et affichage dans la page des informations
		if($fiche_annonce->rowCount() > 1) {
			header('location:' . URL);
		}	else {
			$annonce = $fiche_annonce->fetch(PDO::FETCH_ASSOC);
    }
    
}		

/* 
1 - Modelisation de la BDD (dialogue / commentaire)
2 - Connexion à la BDD
3 - Récupération et affichage des données du formulaire dans la page (controle)
4 - Enregistrement des messages dans la BDD (privilégier prepare() plutot de query ())
5 - Récupération des commentaires dans la BDD pour les afficher dans notre page html
6 - Faire un sorte que la date du commentaire soit affichée en format fr
7 - Faire en sorte d'afficher les messages du plus recent vers le plus ancien
8 - Afficher le nombre de message total
9 - Améliorer le css

-- Sécurité sur les injections XSS (htlmspecialchars() / htmlentities() / strip_tags() )
*/
/**********************************************/
/****** Enregistrement  des notes *************/
/**********************************************/
$note = " ";
$avis = " ";
$contenu = ""; 

if(isset($_POST['note']) && isset($_POST['avis'])) {

  $note = $_POST['note'];
  $avis = $_POST['avis'];

      $enregistrement = $pdo->prepare("INSERT INTO note (note, avis, date_enregistrement, membre_id1, membre_id2) VALUES (:note, :avis, NOW(), :membre_id1, :membre_id2)");

      $enregistrement->bindParam(':note' , $note, PDO::PARAM_STR);
      $enregistrement->bindParam(':avis' , $avis, PDO::PARAM_STR);
      $enregistrement->bindParam(':membre_id1' , $_SESSION['utilisateur']['id_membre'], PDO::PARAM_STR);
      $enregistrement->bindParam(':membre_id2' , $annonce['membre_id'], PDO::PARAM_STR);
      $enregistrement->execute();
      echo'<div class="alert alert-warning alert-dismissible fade show" role="alert"><strong>Merci</strong> Votre avis est bien enregister.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
}


  /***************************************************/
  /***************** FIN Enregistrement **************/
  /**************************************************/

  /***************************************************/
  /******* Moyenne des notes de l'utilisateur ********/
  /**************************************************/
$query6 = 'SELECT ROUND(avg(note),1) AS notation FROM note WHERE membre_id2 = ' . $annonce['id_membre'];
$stmt = $pdo ->query($query6);
$moyenneNotes = $stmt -> fetch();

/******************************************************/
/****** Enregistrement / affichage des commentaires **/
/*****************************************************/
$commentaire = " ";
$contenu_com = ""; 

if(isset($_POST['commentaire'])) {

  $commentaire = $_POST['commentaire'];

    $enregistrement_com = $pdo->prepare("INSERT INTO commentaire (membre_id, annonce_id, commentaire, date_enregistrement) VALUES (:membre_id, :annonce_id, :commentaire, NOW())");

    
    $enregistrement_com->bindParam(':membre_id' , $_SESSION['utilisateur']['id_membre'], PDO::PARAM_STR);
    $enregistrement_com->bindParam(':annonce_id' , $annonce['id_annonce'], PDO::PARAM_STR);
    $enregistrement_com->bindParam(':commentaire' , $commentaire, PDO::PARAM_STR);
    $enregistrement_com->execute();
}

    //$liste_commentaire = $pdo->query("SELECT commentaire, DATE_FORMAT(date_enregistrement, '%d/%m/%Y à %H:%i:%s') AS date_fr, pseudo FROM commentaire JOIN membre on membre_id=id_membre WHERE annonce_id = $_GET['id_annonce'] ORDER BY date_enregistrement DESC");
    $query = 'SELECT c.*, m.pseudo FROM commentaire c JOIN membre m ON c.membre_id=id_membre WHERE annonce_id = ' . $_GET['id_annonce'] . ' ORDER BY date_enregistrement DESC';
    $liste_commentaire = $pdo->query($query);

    $contenu_com .= '<b>Nombre de commentaires : ' . $liste_commentaire->rowCount() . '</b><hr>';

    $commentaires = $liste_commentaire->fetchAll(); 

    foreach ($commentaires as $comments) {
      $contenu_com .= '<div style="padding: 20px;" class="card h-60 card-body">';
	    $contenu_com .= '<p style="margin: 0; padding: 20px 10px; background-color: rgb(78, 115, 223); color: white;">De : <b>' . $comments['pseudo'] . '</b> le ' . date_format(date_create($comments['date_enregistrement']),"d-m-Y à H:i") . '</p>';

      $contenu_com .= '<p style="margin: 0; padding: 20px 10px; border: 1px solid #333;">' . $comments['commentaire'] . '</p>';
	
	    $contenu_com .= '</div>';
    }    

  /*********************************************************/
  /****** FIN Enregistrement / affichage des commentaires **/
  /********************************************************/
    


include_once('inc/front_header.inc.php');
include_once('inc/front_nav.inc.php');

?>
<div class="container">

  <!-- Outer Row -->
  <div class="row justify-content-center">

    <div class="col-xl-12 col-lg-12 col-md-9">
      <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
          <div class="p-5 justify-content-between row">
            <h1 class="h1 text-gray-900 mb-4"><?php echo $annonce['titre']; ?></h1>
            <div class="col-2">
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ModalVendeur">
                Contacter : <?php echo $annonce['pseudo'] ?>
                </button>
            </div>
            <!-- Modal -->
            <div class="modal fade" id="ModalVendeur" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-body">
                  <?php
                      if(!user_is_connected()) {
                      //si l'utilisateur n'est pas connecté
                          echo '<div class=" col-12 p-2">';
                          echo '<p> Pour laisser un commentaire,<br> Veuillez vous <a href="connexion.php">connecter</a> ou vous <a href="inscription.php">inscrire</a>.</p>';
                          echo '</div>';
                      } else {
                        echo '<h5 class="modal-title" id="exampleModalLabel1">Coordonnées du vendeur</h5>';
                        echo '<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button><hr>';
                        echo '<div class="text-center">' . $annonce['pseudo'] . '<br> <strong>' . $annonce['telephone'] . '</strong>
                        </div>
                        <br><hr>
                        <div class="text-center">
                        <h5>Formulaire de contact</h5><br>
                        <form method="post" action="">
                        <div class="form-group">
                            <input class="form-control" name="sujet" id="sujet" placeholder="sujet" type="text"><br>
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" name="message" placeholder="message"></textarea><br>
                            <input value="Envoyer" type="submit" name="email" class="btn btn-primary">
                        </div>
                      </form>
                    </div>';
                      }
                  ?>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                  </div>
                </div>
              </div>
            </div>
            <!-- fin modal -->
            <div class="col-3 text-right">
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                  Donner votre avis !!!
                </button>
            </div>
            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Laisser votre avis</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                  <?php
                      if(!user_is_connected()) {
                      //si l'utilisateur n'est pas connecté
                        echo '<div class=" col-12 p-2">';
                        echo '<p> Pour laisser un commentaire,<br> Veuillez vous <a href="connexion.php">connecter</a> ou vous <a href="inscription.php">inscrire</a>.</p>';
                        echo '</div></div>';
                      } else {
                          echo '<form method="post" action="" enctype="multipart/form-data" >
                              <div class="affichage ">'
                                . $contenu .
                              '</div>
                              <div class="form-group">
                                <label for="note">Note (entre 1 et 5)</label>
                                <input type="text" class="form-control" id="note" name="note" placeholder="Donner une note" value="' . $note . '">
                              </div>
                              <div class="form-group">
                                <label for="avis">Avis</label>
                                <input type="text" class="form-control" id="avis" name="avis" placeholder="Laisser votre avis" value="' . $avis . '">
                              </div>
                              <hr>
                              <input type="submit" class="form-control btn btn-primary" id="enregister" name="enregister" value="Enregister">		
                            </form>
                          </div>';
                      }
                  ?>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="p-5 form-group row">
            <div id="carouselExampleIndicators" class="carousel slide my-2 col-md-6" data-ride="carousel">
              <ol class="carousel-indicators">
                <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                <?php if($annonce['photo1']) { ?>
                <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                <?php } ?>
                <?php if($annonce['photo2']) { ?>
                <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                <?php } ?>
                <?php if($annonce['photo3']) { ?>
                <li data-target="#carouselExampleIndicators" data-slide-to="3"></li>
                <?php } ?>
                <?php if($annonce['photo4']) { ?>
                <li data-target="#carouselExampleIndicators" data-slide-to="4"></li>
                <?php } ?>
                <?php if($annonce['photo5']) { ?>
                <li data-target="#carouselExampleIndicators" data-slide-to="5"></li>
                <?php } ?>
              </ol>
              <div class="carousel-inner" role="listbox">
                <div class="carousel-item active">
                  <img src="<?php echo URL . $annonce['photo']; ?>"  class="d-block img-fluid img-rounded" alt="First slide">
                </div>
                <?php if($annonce['photo1']) { ?>
                <div class="carousel-item">
                  <img class="d-block img-fluid img-rounded" src="<?php echo URL . $annonce['photo1']; ?>" alt="Second slide">
                </div>
                <?php } ?>
                <?php if($annonce['photo2']) { ?>
                <div class="carousel-item">
                  <img class="d-block img-fluid img-rounded" src="<?php echo URL . $annonce['photo2']; ?>" alt="Third slide">
                </div>
                <?php } ?>
                <?php if($annonce['photo3']) { ?>
                <div class="carousel-item">
                  <img class="d-block img-fluid img-rounded" src="<?php echo URL . $annonce['photo3']; ?>" alt="Four slide">
                </div>
                <?php } ?>
                <?php if($annonce['photo4']) { ?>
                <div class="carousel-item">
                  <img class="d-block img-fluid img-rounded" src="<?php echo URL . $annonce['photo4']; ?>" alt="Five slide">
                </div>
                <?php } ?>
                <?php if($annonce['photo5']) { ?>
                <div class="carousel-item">
                  <img class="d-block img-fluid img-rounded" src="<?php echo URL . $annonce['photo5']; ?>" alt="Six slide">
                </div>
                <?php } ?>
              </div>
              <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
              </a>
              <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
              </a>
            </div>
            <div class="col-md-6">
              <p class="col-10"><b>Description :</b><br><?php echo $annonce['description_longue']; ?></p>
            </div>
          </div>
          <div class="p-2 form-group d-flex row justify-content-around">
            <p class="p-3"><b>Date de publication :</b><br><?php echo date_format(date_create($annonce['date_enregistrement']),"d / m / Y") ; ?></p>
            <p class="p-3"><b><?php echo $annonce['pseudo'] . '</b><br>' . $moyenneNotes['notation']; ?> / 5.0</p>
            <p class="p-3"><b>Prix :</b><br><?php echo $annonce['prix'] . '€'; ?></p>
            <p class="p-3"><b>Adresse :</b><br><?php echo $annonce['adresse'] . ', ' . $annonce['cp'] . ' ' . $annonce['ville']; ?></p>
          </div>
          <div class="col-12 p-5 form-group row">
            <iframe src="https://maps.google.it/maps?q=<?php echo $annonce['adresse'] . $annonce['cp'] . $annonce['ville'];?>&output=embed" width="100%" height="200" frameborder="0" allowfullscreen></iframe>
        </div>
        <h5 class="col-12 p-5 form-group">Annonces similaires</h5>
        <div class=" col-xl-12 col-lg-12 col-md-9 d-flex justify-content-around p-2">
        <?php 
          //récupération de toutes les annonces en BDD
          $categorie_annonce = $pdo->prepare("SELECT id_annonce, photo, titre, prix, categorie_id FROM annonce WHERE categorie_id = :id_categorie AND id_annonce != :id_annonce");
          $categorie_annonce->bindParam(":id_categorie", $annonce['categorie_id'], PDO::PARAM_STR);
          $categorie_annonce->bindParam(":id_annonce", $_GET['id_annonce'], PDO::PARAM_STR);
          $categorie_annonce->execute();
          $categorie = $categorie_annonce->fetchAll(PDO::FETCH_ASSOC);
          
          $i = 0;
            while ($i < count($categorie)) {
              echo '<div class="col-3 d-flex"><div class="card h-100">';
              echo '<a href="fiche_annonce.php?id_annonce=' . $categorie[$i]['id_annonce'] . '"><img class="card-img-top" src="'. URL . $categorie[$i]['photo'] . '"></a>';
              echo '<div class="card-body d-flex justify-content-between"><h4 class="card-title">' . $categorie[$i]['titre'] . '<h4><span>' . $categorie[$i]['prix'] . '€</span></div>';
              echo '</div></div>';
              $i++;
          } 

        ?>
        </div>
        <div class=" col-xl-12 col-lg-12 col-md-9 p-2">
        <?php
            if(!user_is_connected()) {
            //si l'utilisateur n'est pas connecté
                echo '<div class=" col-12 p-2">';
                echo '<p> Pour laisser un commentaire, Veuillez vous <a href="connexion.php">connecter</a> ou vous <a href="inscription.php">inscrire</a>.</p>';
                echo '</div>';
            } else {
                echo '<form method="post" action="" enctype="multipart/form-data" class="card h-60 p-4 user" >
                  <div class="form-group">
                    <label for="commentaire">Commentaire</label>
                    <input type="text" class="form-control" id="commentaire" name="commentaire" placeholder="Votre pseudo..."  value="' . $commentaire . '">
                  </div>
                  <hr>
                  <input type="submit" class="form-control btn btn-primary" id="enregistrement" name="enregistrement" value="Enregistrement">		
                </form>
                <hr>';
            }
        ?>
          <div class="card overflow-auto square scrollbar-cyan bordered-cyan" style="height: 20vw;">
            <div class="card-body affichage ">
              <?php 
              echo $contenu_com;
              ?>
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