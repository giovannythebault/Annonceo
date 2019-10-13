
  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container">
      <a class="navbar-brand" href="index.php">Annonceo</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>                
      <!-- Topbar -->

          <!-- Topbar Search -->
          <form class="d-none p-5 d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search" method="post">
            <div class="input-group">
              <input type="text" class="form-control bg-light border-0
               small selectIndex" placeholder="Recherche..." id="recherche" name="recherche">
              <div class="input-group-append">
                <button class="btn btn-primary" type="button">
                  <i class="fas fa-search fa-sm"></i>
                </button>
              </div>
            </div>
          </form>

        <!-- End of Topbar -->
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">

          <?php if(!user_is_connected()) { ?>

          <li class="nav-item">
            <a class="nav-link" href="inscription.php">Inscription</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white" href="connexion.php">Connexion</a>
          </li>

          <?php } else  { ?>

          
          <li class="nav-item">
            <a class="nav-link" href="deposer_annonce.php">Déposer une annonce</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="connexion.php?action=deconnexion">Deconnexion</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white" href="profil.php">Profil</a>
          </li>


          <?php if(user_is_admin()) {?>


            <li class="nav-item">
            <a class="nav-link text-white" href="back_office\accueil-back.php">Gérer le site</a>
          </li>
          
          <?php   } ?>
          <?php   } // fermeture du else ?>
        </ul>
      </div>
    </div>
  </nav>
  <script>
               	$(document).ready(function() { 

                  
                  $("#recherche").keyup( function() {
                    var recherche = $('#recherche').val();
                    var param = {
                      laRecherche:recherche
                    } 
                    $.post('traitementautocomplete.php', param, function (rep) {

                      var listeAutocompletion = rep.listeAutocompletion;
                      $('#recherche').autocomplete({
        source: listeAutocompletion
        
    });

			}, 'json');
                  });
                  

                });


  </script>