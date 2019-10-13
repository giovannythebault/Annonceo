<?php

include_once('inc/init.inc.php');
$tab = array();
$tab['recherche'] = '';

$tab['listeAutocompletion'] = [];

if (isset($_POST['laRecherche']) && !empty($_POST['laRecherche'])){
    $tab['recherche'] .= $_POST['laRecherche'];
    $recherche = '%' . $_POST['laRecherche'] . '%';

    $requeteTitre = $pdo->prepare(" SELECT
    a.titre
FROM
    annonce a
WHERE
    a.titre LIKE :recherche
    limit 5");
$requeteTitre->bindParam(':recherche', $recherche, PDO::PARAM_STR);
$requeteTitre->execute();

if($requeteTitre->rowCount() > 0) {
    $requeteTitre = $requeteTitre -> fetchAll(PDO::FETCH_ASSOC);
    foreach($requeteTitre as $laLigne){
        array_push($tab['listeAutocompletion'], $laLigne['titre']);
    }
}
        $nombreLimit = 10 - count($tab['listeAutocompletion']);
    array_push($tab['listeAutocompletion'], $nombreLimit);

}
echo json_encode($tab);
