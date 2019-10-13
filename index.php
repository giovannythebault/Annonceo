<?php
include_once('inc/init.inc.php');


$liste_annonce = $pdo->query("SELECT * FROM annonce");







if(isset($_GET['action'] ) && $_GET['action'] == 'moincheraupluscher') {

$liste_annonce = $pdo->prepare("SELECT * FROM annonce ORDER BY prix ASC ");

$liste_annonce->execute();


}
if(isset($_GET['action'] ) && $_GET['action'] == 'pluscheraumoinscher') {

$liste_annonce = $pdo->prepare("SELECT * FROM annonce ORDER BY prix DESC ");
$liste_annonce->execute();

}
if(isset($_GET['action'] ) && $_GET['action'] == 'triparanciennerecente') {

$liste_annonce = $pdo->prepare("SELECT * FROM annonce ORDER BY date_enregistrement ASC ");
$liste_annonce->execute();

}if(isset($_GET['action'] ) && $_GET['action'] == 'triparrecenteancienne') {
$liste_annonce =  $pdo->prepare("SELECT * FROM annonce ORDER BY date_enregistrement DESC ");
$liste_annonce->execute();

  
}if(isset($_GET['action'] ) && $_GET['action'] == 'triparmeilleursvendeur') {

$liste_annonce = $pdo->prepare("SELECT a.id_annonce, a.photo, a.titre, a.prix, n.note
FROM annonce a,  note n 
WHERE a.membre_id = n.membre_id2 GROUP BY a.id_annonce ORDER BY avg(n.note) DESC");
  $liste_annonce->execute();

}

include_once('inc/front_header.inc.php');
include_once('inc/front_nav.inc.php');



?>
<!-- Page Content -->
<div class="container">

  <div class="row">

    <div class="col-lg-3">

      <h1 class="my-4">Rechercher Par</h1>
      <div class="list-group">
        <form  method="post" action="#">
        <div class="form-group">
            <label for="categorie">Catégorie</label>
            <select class="form-control selectIndex" id="categorie" name="categorie">
            <option  value="toutes">Toutes les catégories</option>
            <?php
                        $categorie = $pdo->query("SELECT id_categorie, titre  FROM categorie ORDER BY titre"); 
                        while($cat = $categorie->fetch(PDO::FETCH_ASSOC)) {
                            echo '<option value="'. $cat['id_categorie'] .'">'.$cat['titre'].'</option>';
                        }   


                        ?>
            </select>
        </div>
        <hr class="sidebar-divider">
        <div class="form-group">
            <label for="Ville">Ville</label>
            <select class="form-control selectIndex" id="Ville" name="Ville">
            <option  value="toutes">Toutes les Villes</option>
            <?php
                        $ville = $pdo->query("SELECT id_annonce, ville FROM annonce GROUP BY ville"); 
                        while($vil = $ville->fetch(PDO::FETCH_ASSOC)) {
                            echo '<option value="'. $vil['ville'] .'">'.$vil['ville'].'</option>';
                        }                           
                        ?>
            </select>
        </div>
        <hr class="sidebar-divider">
        <div class="form-group">
            <label for="membre">Membre</label>
            <select class="form-control selectIndex" id="membre" name="membre">
            <option  value="tous">Tous les membres</option>
            <?php
                        $membre = $pdo->query("SELECT id_membre, pseudo  FROM membre ORDER BY pseudo"); 
                        while($mem = $membre->fetch(PDO::FETCH_ASSOC)) {
                            echo '<option value="'. $mem['id_membre'] .'">'.$mem['pseudo'].'</option>';
                        }                           
                        ?>
            </select>
        </div>
        <hr class="sidebar-divider">
        <div class="form-group">
		        <label for="prix">Prix</label>
		        <select class="form-control selectIndex" id="prix" name="prix">
		          <option value="tous">Tous les prix</option>
                                <option value="moinsDecict">Moins de 500€</option>
                                <option value="entrecinqEtmilleneuf">Entre 500€ et 2000€</option>
                                <option value="entredeuxmEtquatrem">Entre 2000€ et 4000€</option>
                                <option value="plusDequatrem">Plus de 4000€</option>
              		        
        </select>
        </div>

        </form>
        
      </div>

    </div><div class="col-lg-9">
        
      

       <div class="dropdown">
								<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    						    trier par
  								</button>

                  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                  
        <a href="index.php">Toutes les annonces</a><br>         	
        <a href="?action=moincheraupluscher">Trier du moins cher au plus cher</a><br>
        <a href="?action=pluscheraumoinscher">Trier du plus cher au moins cher </a><br>
        <a href="?action=triparanciennerecente">Trier par date de la plus ancienne à la plus récente</a><br>
        <a href="?action=triparrecenteancienne">Trier par date de la plus récente à la plus ancienne</a><br>
        <a href="?action=triparmeilleursvendeur">Trier par meilleurs vendeurs</a><br>
								
									
							
									
								</div></div>

      <div class="col-lg-9">
              <br><br>

              <div class="row">
              <div id="affichageAcceuil"  class="row">
    <?php
       while($annonceIndex = $liste_annonce->fetch(PDO::FETCH_ASSOC)) {
      
       echo  '<div class="col-lg-4 col-md-6 mb-4">';
       echo  '<div class="card h-100">
              <a href="fiche_annonce.php?id_annonce=' . $annonceIndex['id_annonce'] . '" ><img class="card-img-top" src="' . URL . $annonceIndex['photo'] . '" alt="img produit"></a>
              <div class="card-body">
                <h4 class="card-title">
                  <a href="fiche_annonce.php?id_annonce=' . $annonceIndex['id_annonce'] . '" >' . $annonceIndex['titre'] . '</a>
                </h4>
                <h5>' . $annonceIndex['prix'] . '€</h5>
                <p class="card-text">' . $annonceIndex['description_courte'] . '</p>
                </div>
              
            </div>
          </div> '
      ; }
          
?></div></div>
        </div>
        <!-- /.row -->

      </div>
      <!-- /.col-lg-9 -->

    </div>
</div>
  </div>
  <!-- /.container -->
<?php

include_once('inc/front_footer.inc.php');


?>



  <script>
        	$(document).ready(function() { 

            $('.selectIndex').on('change', function() { 
              var choixRech = $('#recherche').val();
              var choixCat = $('#categorie').val();
              var choixville = $('#Ville').val();
              var choixmembre = $('#membre').val();
              var choixprix = $('#prix').val();

             var param = {lesRecherche:choixRech,
               categorie:choixCat,
            ville:choixville,
           membre:choixmembre,
           prix:choixprix}

             $.post('traitement.php', param, function (reponse) {
            $('#affichageAcceuil').html(reponse.resultat);
            console.log(reponse);

			}, 'json');

            });



          });

  </script>