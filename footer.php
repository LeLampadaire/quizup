<footer class="page-footer font-small bg-dark">
      <div class="container">
        <div class="container text-center text-md-left mt-5">
          <div class="row mt-3">
            <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mb-4">
              <h6 class="text-uppercase font-weight-bold">Mentions légales</h6>
              <hr class="bg-white accent-2 mb-4 mt-0 d-inline-block mx-auto" style="width: 75px;">
              <p>
                <a href="#!">Conditions d'utilisation</a>
              </p>
              <p>
                <a href="#!">Politique de confidentialité</a>
              </p>
        </div>
        <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mb-4">
          <h6 class="text-uppercase font-weight-bold">Liens utiles</h6>
          <hr class="bg-white accent-2 mb-4 mt-0 d-inline-block mx-auto" style="width: 75px;">
          <?php
          if(empty($_SESSION)){
          ?>
            <p>
            <a href="connexion.php">Connexion</a>
            </p>
            <p>
              <a href="inscription.php">Inscription</a>
            </p>
          <?php
          }
          ?>
         
        </div>
        <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mb-md-0 mb-4">
          <h6 class="text-uppercase font-weight-bold">Contact</h6>
          <hr class="bg-white accent-2 mb-4 mt-0 d-inline-block mx-auto" style="width: 75px;">
          <p>
            <i></i>Inpres, Rue Peetermans 80, 4100 Seraing, Belgique</p>
          <p>
            <i></i>Kouristoll@hotmail.be</p>
          <p>
            <i></i> + 32 04 330 75 00</p>
          <p>
            <i></i> + 32 04 988 17 24</p>
        </div>
      </div>
    </div>

    <!-- Copyright -->
    <div class="footer-copyright text-center py-3">© 2018 Copyright:
      <a href="index.php">QuizUp.com</a>
    </div>

  </footer>