
<?php
include_once("inc/init.inc.php");

$tab = array();
$tab['resultat'] = '';


if(isset($_POST['lesRecherche']) && isset($_POST['categorie']) && isset($_POST['ville']) && isset($_POST['membre']) && isset($_POST['prix'])) {

  $pourLaRecherche = "%" . $_POST['lesRecherche'] . '%';


  if ($_POST['categorie'] != 'toutes'){
    $pourLaCategorie = $_POST['categorie'];
}else{
    $pourLaCategorie = '%';
}
if ($_POST['ville'] != 'toutes'){
  $pourLaville = $_POST['ville'];
}else{
  $pourLaville = '%';
}
if ($_POST['membre'] != 'tous'){
  $pourLemembre = $_POST['membre'];
}else{
  $pourLemembre = '%';
}
if ($_POST['prix'] != 'tous'){
  if ($_POST['prix'] == 'moinsDecict'){
  $pourLeprix = '< 500';
  }if ($_POST['prix'] == 'entrecinqEtmilleneuf'){
    $pourLeprix = 'BETWEEN 500 AND 2000';
    }if ($_POST['prix'] == 'entredeuxmEtquatrem'){
      $pourLeprix = 'BETWEEN 2000 AND 4000';
      }if ($_POST['prix'] == 'plusDequatrem'){
        $pourLeprix = '> 4000';
        }
}else{
  $pourLeprix = '<100000000';
}
      

      

        $liste_annonce = $pdo->prepare("SELECT a.id_annonce, a.titre, a.description_courte, a.prix, a.photo, m.pseudo
        FROM annonce a
        LEFT JOIN membre m ON m.id_membre = a.membre_id
        LEFT JOIN note n ON n.membre_id2 = m.id_membre
        WHERE (a.titre LIKE :lesRecherche
              OR a.description_courte LIKE :lesRecherche
              OR a.prix LIKE :lesRecherche
              OR a.ville LIKE :lesRecherche ) 
        AND a.categorie_id LIKE :categorie
        AND a.ville LIKE :ville  
        AND m.id_membre LIKE :membre
        AND a.prix " . $pourLeprix ."
        GROUP BY a.id_annonce
       LIMIT 30");

$liste_annonce->bindParam(':lesRecherche', $pourLaRecherche, PDO::PARAM_STR);
$liste_annonce->bindParam(':categorie', $pourLaCategorie, PDO::PARAM_STR);
$liste_annonce->bindParam(':ville', $pourLaville, PDO::PARAM_STR);
$liste_annonce->bindParam(':membre',  $pourLemembre, PDO::PARAM_STR);
$liste_annonce->execute();


if($liste_annonce->rowCount() > 0) { 

  while($annonceIndex = $liste_annonce->fetch(PDO::FETCH_ASSOC)) {

    $tab['resultat'] .= '<div class="col-lg-4 col-md-6 mb-4">
      <div class="card h-100">
      <a href="fiche_annonce.php?id_annonce=' . $annonceIndex['id_annonce'] . '" ><img class="card-img-top" src="' . URL . $annonceIndex['photo'] . '" alt="img produit"></a>
      <div class="card-body">
             <h4 class="card-title">
               <a href="fiche_annonce.php?id_annonce=' . $annonceIndex['id_annonce'] . '" >' . $annonceIndex['titre'] . '</a>
             </h4>
             <h5>' . $annonceIndex['prix'] . '€</h5>
             <p class="card-text">' . $annonceIndex['description_courte'] . '</p>
           </div>
           
         </div>
       </div> ';

  
  }
}else{
  $tab['resultat'] = '<div class="alert alert-warning mt-2" role="alert">Nous n\'avons rien trouvé pour votre recherche.';
}
	
}

echo json_encode($tab);


