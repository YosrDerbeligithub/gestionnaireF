<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interface Admin/Participant</title>
    
    <!-- Favicons -->
    <link href="../assets/img/logo cni.png" rel="icon">
    <link href="../assets/img/apple-touch-icon.png" rel="apple-touch-icon">
    
   
    <!-- Vendor CSS Files -->
    <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Main CSS File -->
    <link href="../assets/css/main.css" rel="stylesheet">
    <link href="../gestionnaireF/style.css" rel="stylesheet">
    
    <style>
        *{
            font-family: 'Rowdies', cursive;
        }
        body {
            background-color: #FFF9D0; /* Changement de la couleur de fond */
        }
        .logo-cni {
            width: 160px;
            height: auto;
            margin-left: 80px;
        }
        .btn-get-started {
            margin-right: 15px;
            font-size: 20px; /* Augmentation de la taille de police des boutons */
        }
        .btn-get-started:hover {
    background-color: #CAF4FF; /* Darker green for hover */
    transform: scale(1.05); /* Slightly enlarge on hover */
}
.text-highlight {
    color: #2a698f; /* Same color as "Bienvenue" */
}
        h1 {
            margin-bottom: 100px; /* Ajout d'espace sous le titre */
            color: #134B70;
        }
        .hero.section.dark-background {
  background: #FFF9D0; /* Example dark color */
}

    </style>
</head>
<body class="index-page">

<header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">
        <nav id="navmenu" class="navmenu">
            <ul>
            </ul>
            <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>
    </div>
</header>

<main class="main">
    <!-- Hero Section -->
    <section id="hero" class="hero section dark-background" style="padding: 150px 0;">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center" data-aos="zoom-out" style="padding-top: -30px;font-family: 'Rowdies', cursive;">
                    <h1 style="margin-bottom: 50px;">Bienvenue sur le portail de gestion des cycles de formation</h1>
                    <p style="color: #A0937D;">Choisissez votre rôle pour continuer : Administrateur ou Participant.</p>
                    <div class="d-flex mt-3">
                        <a href="admin_login.php" class="btn-get-started me-3">Admin: Login</a>
                        <a href="../Participant/inscription.php" class="btn-get-started">Participant: Inscription</a>
                    </div>
                </div>
                <div class="col-lg-6 order-1 order-lg-2 hero-img" data-aos="zoom-out" data-aos-delay="200">
                    <img src="../assets/img/hero-img.png" class="img-fluid animated" alt="">
                </div>
            </div>
        </div>
    </section>
</main>

<footer id="footer" class="footer">
    <div class="footer-newsletter">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-lg-6">
                    <h4 class="text-highlight">Rejoignez notre newsletter</h4>
                    <p class="newsletter-text">Abonnez-vous à notre newsletter et recevez les dernières nouvelles sur nos produits et services !</p>
                    <form action="forms/newsletter.php" method="post" class="php-email-form">
                        <div class="newsletter-form">
                            <input type="email" name="email">
                            <input type="submit" value="Subscribe">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="container footer-top">
        <div class="row gy-4">
            <div class="col-lg-4 col-md-6 footer-about">
                <a href="index.html" class="d-flex align-items-center text-highlight">
                    <span style="color: #2a698f" class="sitename">CNI</span>
                </a>
                <div class="footer-contact pt-3">
                    <p>Centre National de l’Informatique</p>
                    <p>17, rue Belhassen Ben Chaabane, 1005 El Omrane, Tunis Tunisie.</p>
                    <p class="mt-3"><strong>Tél:</strong> <span>+ 216 71 783 055</span></p>
                    <p class="mt-2"><strong>Fax:</strong> <span>+ 216 71 781 862</span></p>
                    <p><strong>Email:</strong> <span>webcni@cni.tn</span></p>
                </div>
            </div>

            <div class="col-lg-2 col-md-3 footer-links text-highlight"">
                <h4 style="color: #2a698f">Liens Utiles</h4>
                <ul>
                    <li><i class="bi bi-chevron-right"></i> <a href="http://www.cni.tn/index.php/fr/">Accueil</a></li>
                    <li><i class="bi bi-chevron-right"></i> <a href="http://www.cni.tn/index.php/fr/layout-3/presentation-du-cni-2">A propos de nous</a></li>
                    <li><i class="bi bi-chevron-right"></i> <a href="http://www.cni.tn/index.php/fr/socle-applicatif-2">Socle applicatif</a></li>
                </ul>
            </div>

            <div class="col-lg-2 col-md-3 footer-links">
                <ul class="mt-4">
                    <li><i class="bi bi-chevron-right"></i> <a href="http://www.cni.tn/index.php/fr/internationale">Internationale</a></li>
                    <li><i class="bi bi-chevron-right"></i> <a href="http://www.cni.tn/index.php/fr/appels-d-offres-2">Appels d'Offres</a></li>
                    <li><i class="bi bi-chevron-right"></i> <a href="http://www.cni.tn/index.php/fr/contact-suggestion-2">Contact</a></li>
                </ul>
            </div>

            <div class="col-lg-4 col-md-12">
                <img src="../assets/img/logo cni.png" class="logo-cni">
            </div>
        </div>
    </div>

    <div class="container copyright text-center mt-4">
        <p>© <span>Copyright</span> <strong class="px-1 sitename">CNI</strong> <span>All Rights Reserved</span></p>
    </div>
</footer>

<a href="#" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<!-- Vendor JS Files -->
<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../assets/vendor/aos/aos.js"></script>
<script src="../assets/vendor/glightbox/js/glightbox.min.js"></script>
<script src="../assets/vendor/swiper/swiper-bundle.min.js"></script>
<script src="../assets/vendor/php-email-form/validate.js"></script>

<!-- Main JS File -->
<script src="../assets/js/main.js"></script>

</body>
</html>
