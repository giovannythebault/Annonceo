
<?php
include_once('../inc/init.inc.php');

if(!user_is_admin()) {
     //si l'utilisateur n'est pas connecté
     header("location:../index.php");
}


$mieuxnote = $pdo->query("SELECT membre.pseudo, ROUND(avg(note.note),1) AS B  , COUNT(note.avis) AS C FROM membre, note WHERE id_membre = membre_id2 GROUP BY id_membre ORDER BY avg(note.note) DESC LIMIT 5");

$plusactif = $pdo->query("SELECT membre.pseudo, COUNT(annonce.id_annonce) FROM membre, annonce WHERE id_membre = membre_id GROUP BY id_membre ORDER BY COUNT(annonce.id_annonce) DESC  LIMIT 5");//ok

$plusancienne = $pdo->query("SELECT titre, description_courte, date_enregistrement FROM annonce ORDER BY date_enregistrement ASC LIMIT 5");//ok

$plusannonce =$pdo->query("SELECT categorie.titre , COUNT(annonce.id_annonce) FROM categorie, annonce WHERE id_categorie = categorie_id GROUP BY id_categorie ORDER BY COUNT(annonce.id_annonce) DESC LIMIT 5"); //ok








include_once('back_head.inc.php');
include_once('back_nav.inc.php');



?>
     <div class="row">
     <div class="col4 m-4">
     <p style = "color: #4e73df;"> top 5 des membres les mieux notés</p>

     <?php

     while($lignemieuxnotes = $mieuxnote->fetch(PDO::FETCH_ASSOC)) {
          
          foreach($lignemieuxnotes AS $indice => $valeur) {
               if($indice == 'pseudo') {
                    echo '<span><b>pseudo: </b></span>'. $valeur . '<br>';
               } if($indice == 'B'){
          echo '<span><b>Note: </b></span>' . $valeur . '/5<br>';
          }elseif($indice == 'C'){
               echo '<span><b>Nombre d\'Avis: </b></span>' . $valeur . '<br><br>';}
          }
     }      
    ?>
    </div>
    <div class="col4 m-4">

    <p style = "color: #4e73df;"> top 5 des membres les plus actif</p>
    <?php

     while($lignePlusActif = $plusactif->fetch(PDO::FETCH_ASSOC)) {

       
foreach($lignePlusActif AS $indice => $valeur) {
     if($indice == 'pseudo') {
          echo '<span><b>pseudo: </b></span>'. $valeur . '<br>';
     } else{
echo '<span><b>Nombre d\'annonce: </b></span>' . $valeur . '<br><br>';
}

}
     }
    ?>  
          </div>
   
    <div class="col4 m-4">

    <p style = "color: #4e73df;"> top 5 des annonce les plus anciennes</p>
    <?php 



     while($infoAncienne = $plusancienne->fetch(PDO::FETCH_ASSOC)) {

       
foreach($infoAncienne AS $indice => $valeur) {
     if($indice == 'titre') {
          echo '<span><b>titre: </b></span>'. $valeur . '<br>';
     } elseif($indice == 'description_courte') {
echo '<span><b>decription courte: </b></span>' . substr($valeur, 0, 8)  . '...<br>';
}elseif($indice == 'date_enregistrement') {
     echo '<span><b>date_enregistrement: </b></span>' . $valeur . '<br><br>';

}
}
}
     
    ?>
    </div>
     <div class="col4 m-4">
     <p style = "color: #4e73df;"> top 5 des catégories contenant le plus d'annonces</p>
     <?php 


     while($infoPlusGrandecate = $plusannonce->fetch(PDO::FETCH_ASSOC)) {

       
foreach($infoPlusGrandecate AS $indice => $valeur) {
    
     if($indice == 'titre') {
          echo '<span><b>catégorie: </b></span>'. $valeur . '<br>';
     } else{
echo '<span><b>Nombre d\'annonce: </b></span>' . $valeur . '<br><br>';
}}
       
     } 
    ?>
     </div>
    </div>
<?php

include_once('back_footer.inc.php');

