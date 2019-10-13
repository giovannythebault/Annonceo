<?php


// fonction pour savoir si l'utilisateur est connecté
function user_is_connected() {
    if(isset ($_SESSION['utilisateur'])) {
        return true;
    }
    return false;
}

// fonction pour savoir si l'utilisateur est admin
function user_is_admin() {
    if(user_is_connected() && $_SESSION['utilisateur'] ['statut'] == 2) {
        return true;
    }
    return false;
}

// PANIER
//creation
function creation_panier() {
    if(!isset($_SESSION['panier'])) {
        $_SESSION['panier'] = array();
        $_SESSION['panier']['id_article'] = array();
        $_SESSION['panier']['prix'] = array();
        $_SESSION['panier']['quantite'] = array();
        $_SESSION['panier']['titre'] = array();
       
    }
}

//ajout d'un article
function ajout_article($id_article, $prix, $quantite, $titre) {
    //on vérifie si l'article est déjà présent, si c'est le cas on change la quantité.
    $position = array_search($id_article, $_SESSION['panier']['id_article']);
    //array_search() cherche si la valeur fournie en premier argument est présente ds le tableau array fourni en deuxième argument.
    //si c'est le cas on récupère l'indice (position) sinon FALSE
    
    if($position !== false) {
        $_SESSION['panier']['quantite'][$position] += $quantite;
    } else {
        // l'article n'est pas présent, on le rajoute
        $_SESSION['panier']['quantite'][] = $quantite;
        $_SESSION['panier']['id_article'][] = $id_article;
        $_SESSION['panier']['prix'][] = $prix;
        $_SESSION['panier']['titre'][] = $titre;
    }
  
}

// montant total

function montant_total() {
    $total = 0;
    //une boucle pour traiter chaques lignes du panier en multipliant le prix par la quantité
    
    
    for($i = 0; $i < count($_SESSION['panier']['titre']); $i++) {
        $total += $_SESSION['panier']['prix'][$i] * $_SESSION['panier']['quantite'][$i];
        
    }
    $total *= 1.2; // ajout de 20% de tva
    return round($total, 2);
}

//retirer un article au panier

function retirer_article($id_article) {
    // on récupère l'indice correspondant à un article à supprimer
    $position = array_search($id_article, $_SESSION['panier']['id_article']);
    
    if($position !== false) {
        // array_splice() permet de retirer un élément d'un tableau array mais surtout de réordonner les indices du tableau afin qu'il n'y ai pas de trou.
        array_splice($_SESSION['panier']['titre'], $position, 1);
        array_splice($_SESSION['panier']['id_article'], $position, 1);
        array_splice($_SESSION['panier']['quantite'], $position, 1);
        array_splice($_SESSION['panier']['prix'], $position, 1);
    }
}



// FONCTION VERIF EXTENSION PHOTO
function verif_extension($name) {
    $extension = strrchr($name, '.');
      //   // exemple: ma_photo.jpg => .jpg
        
      //   // on passe l'information en minuscule et on enlève le point
       $extension = strtolower(substr($extension, 1));
      //   // exemple: .PNG => png
      //   // exemple: .Jpeg => jpeg
        
      //   // on défini toutes les valeurs acceptées dans un tableau array
      $extension_valide = array('jpg', 'jpeg', 'png', 'gif');
        
      //   // in_array() permet de tester si une valeur fait partie d'un ensemble de valeur présentent dans un tableau array => true / false
      $verif_extension = in_array($extension, $extension_valide);
      return $verif_extension;
}


function verif_photo() {

    if 
}