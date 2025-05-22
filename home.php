<?php
session_start();
require 'Backend/connexion/conn.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
$userId = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT type FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $type = $user['type'];

    if ($type === "Admin") {
        header("Location: admin.php");
        exit();
    } 
} else {
    session_destroy();
    header("Location: ../");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guichet automatique</title>
    <link rel="stylesheet" href="Fonts.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/typed.js/2.0.11/typed.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1/jquery.waypoints.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css"/>
</head>
<body>
    <nav class="navbar">
        <div class="max-width">
            <div class="logo kaushan-script"><a style="color:rgb(255, 255, 255)" href="#">Guichet Automatique</span></a></div>
            <ul class="menu">
                <li><a class="menu-btn"   href="pages/historique.php" role="tab">Historique</a></li>
                <li><a href="pages/retrait.php" class="menu-btn">Retrait</a></li>
                <li><a class="menu-btn"href="pages/depot.php" role="tab">Dépôt</a></li>
                <li><a class="menu-btn"href="Paramètres.php" role="tab">Paramètres</a></li>
                <li><a class="menu-btn"href="pages/logout.php" role="tab">Se Déconnecter</a></li>
            </ul>
            <div class="menu-btn">
                <i class="fas fa-bars"></i>
            </div>
        </div>
    </nav>
    <section class="home" id="home">
        <div class="max-width">
            <div class="home-content">
                <div class="text-2">Construisons notre pays avec le numérique</div>
                <div class="text-3"><span class="typing"></span></div>
                <a href="index.php">Se connecter comme Admin</a>
                <a href="pages/guichet.php" class="All-Guichet">Consulter les guichets</a>
            </div>
        </div>
        <p class="decription moon-dance-regular">Projet du cours de Web 2  Bujumbura International University</p>
        <p class="decription right moon-dance-regular">Christine Safi Kibasoba - Web Designer </p>
    </section>

    <section class="about" id="about">
        <div class="max-width">
            <h2 class="title viga-regular">Aimeriez-vous en savoir plus sur cette plateforme&nbsp;?</h2>
            <div class="about-content">
                <div class="column left"></div>
               <div class="column right">
    <div class="text">Une révolution à Bujumbura</div>
    <p>Cette plateforme de guichet automatique a été spécialement conçue pour faciliter l’accès à divers services à Bujumbura. Adaptée aux besoins des citoyens et des institutions, elle permet une gestion rapide, autonome et sécurisée des démarches. Un espace a été aménagé pour permettre la location et l’utilisation de cette plateforme dans différents quartiers de la ville.</p>
    <a href="addCompteBanque.php" style="background:#000;color: #fff;border:none">Créer un compte bancaire</a>
</div>

            </div>
        </div>
    </section>

   

    <section class="contact" id="contact">
    <div class="max-width">
        <h2 class="title viga-regular">Une fierté au sein de la communauté burundaise</h2>
        <div class="contact-content">
            <div class="column left">
                <div class="text">Merci de nous contacter</div>
                <p>Nous vous remercions chaleureusement de nous avoir contactés via notre plateforme. Votre initiative est grandement appréciée et nous sommes impatients de vous offrir notre meilleur service.</p>
                <div class="icons">
                    <div class="row">
                        <i class="fas fa-user"></i>
                        <div class="info">
                            <div class="head viga-regular">Plateforme de Guichet Automatique</div>
                            <div class="sub-title">Service numérique à Bujumbura</div>
                        </div>
                    </div>
                    <div class="row">
                        <i class="fas fa-map-marker-alt"></i>
                        <div class="info">
                            <div class="head">Adresse</div>
                            <div class="sub-title">Gihosha, 4ᵉ rue, numéro 5</div>
                        </div>
                    </div>
                    <div class="row">
                        <i class="fas fa-envelope"></i>
                        <div class="info">
                            <div class="head">Email</div>
                            <div class="sub-title">contact.guichet@gmail.com</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

    <footer>
        <P class=" moon-dance-regular">Christine Safi Kibasoba - Web Designer </P>
    </footer>
    <script src="script.js"></script>
</body>
</html>
