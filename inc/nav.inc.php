<header id="header">
  <div class="container">
    <nav class="navbar navbar-expand-lg navbar-light bg-white">
      <a class="navbar-brand" href="accueil.php">
        <img src="<?= URL ?>assets/img/logo.png" alt="Logo Caesaris" class="logo-navbar" />
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarScroll">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item">
            <a class="nav-link" href="<?= URL ?>accueil.php">Accueil</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="">Boutique</a>
          </li>
        </ul>

        <ul class="navbar-nav ms-auto">
          <?php if (!is_user() && !is_admin()) : ?>
            <li class="nav-item">
              <a class="nav-link" href="<?= URL ?>pages/form/sign_in.php">Connexion</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?= URL ?>pages/form/sign_up.php">Inscription</a>
            </li>
          <?php endif; ?>
          <?php if (is_user()) : ?>
            <li class="nav-item">
              <a class="nav-link" href="<?= URL ?>my_account.php">Mon Compte</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?= URL ?>pages/form/logout.php">Déconnexion</a>
            </li>
          <?php endif; ?>
          <?php if (is_admin()) : ?>
            <li class="nav-item">
              <a class="nav-link" href="">Panneau de configuration</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?= URL ?>pages/form/logout.php">Déconnexion</a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </nav>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</header>