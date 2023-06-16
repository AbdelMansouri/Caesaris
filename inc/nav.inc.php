<body>
  <header id="header">
    <div class="container-fluid bg-ps" id="promo">
      <p class="text-08 text-center"> Livraison gratuite à partir de 30€.</p>
    </div>
    <div class="container">
      <nav class="navbar navbar-expand-lg navbar-light bg-white p-0 m-0">
        <a class="navbar-brand p-0 m-0 me-5" href="index.php">
          <img src="<?= URL ?>assets/img/logo.png" alt="Logo Caesaris" class="logo-navbar p-0 m-0" />
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarScroll">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item">
              <a class="nav-link" href="<?= URL ?>index.php">Accueil</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?= URL ?>shop.php">Boutique</a>
            </li>
          </ul>

          <ul class="navbar-nav ms-auto">
            <li class="nav-item">
              <a class="nav-link" href="<?= URL ?>pages/user/cart.php">Mon Panier</a>
            </li>
            <?php if (!is_user() && !is_admin()) : ?>
              <li class="nav-item">
                <a class="nav-link" href="<?= URL ?>pages/form/sign_in.php">Connexion</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="<?= URL ?>pages/form/sign_up.php">Inscription</a>
              </li>
            <?php endif; ?>
            <?php if (is_admin()) : ?>
              <li class="nav-item">
                <a class="nav-link" href="<?= URL ?>pages/admin/gestion_article.php">Panneau de configuration</a>
              </li>
            <?php endif; ?>
            <?php if (is_user() || is_admin()) : ?>
              <li class="nav-item">
                <a class="nav-link" href="<?= URL ?>my_account.php">Mon Compte</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="<?= URL ?>pages/form/logout.php">Déconnexion</a>
              </li>
            <?php endif; ?>
          </ul>
        </div>
      </nav>
    </div>

  </header>