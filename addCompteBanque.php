<?php
session_start();
require 'Backend/connexion/conn.php';

$error = "";
$success = "";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../");
    exit();
}

$clientId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'] ?? '';
    $solde = floatval($_POST['solde'] ?? 0);
    $devise = $_POST['devise'] ?? '';
    $limite_retrait = floatval($_POST['limite_retrait'] ?? 0);
    $statut_compte = $_POST['statut_compte'] ?? '';

    $verif = $conn->prepare("SELECT idCompte FROM comptebancaire WHERE clientId = ?");
    $verif->bind_param("i", $clientId);
    $verif->execute();
    $verif->store_result();

    if ($verif->num_rows > 0) {
        $error = "Vous avez déjà un compte bancaire. Un seul compte est autorisé par utilisateur.";
    } elseif (empty($type) || $solde <= 0 || empty($devise) || $limite_retrait <= 0 || empty($statut_compte)) {
        $error = "Tous les champs sont requis avec des valeurs valides.";
    } else {
        $insert = $conn->prepare("INSERT INTO comptebancaire (type, solde, devise, clientId, limite_retrait, statut_compte) VALUES (?, ?, ?, ?, ?, ?)");
        $insert->bind_param("sdsdis", $type, $solde, $devise, $clientId, $limite_retrait, $statut_compte);

        if ($insert->execute()) {
            $idCompte = $insert->insert_id;
            $dateHeure = date('Y-m-d H:i:s');
            $typeEvenement = "Création de compte";
            $message = "Création du compte n°$idCompte de type $type avec un solde initial de $solde $devise.";
            $idPersonnel = $clientId;

            $hist = $conn->prepare("INSERT INTO historique (dateHeure, typeEvenement, message, idGuichet, idPersonnel, idClient) VALUES (?, ?, ?, NULL, ?, ?)");
            $hist->bind_param("sssii", $dateHeure, $typeEvenement, $message, $idPersonnel, $clientId);
            $hist->execute();

            $success = "Compte bancaire créé avec succès.";
        } else {
            $error = "Erreur lors de la création du compte.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Créer un compte bancaire</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="styles/global.css" />
</head>
<body>
<main class="d-flex justify-content-center align-items-center vh-100">
    <article class="form-container parent-Basic p-4 rounded shadow bg-light text-center" style="min-width: 350px;">
        <form method="POST" action="">
            <h3 class="mb-4">Créer un compte bancaire</h3>

            <div class="mb-3 input-group">
                <select name="type" class="form-select" required>
                    <option value="">Type de compte</option>
                    <option value="Épargne">Épargne</option>
                    <option value="Courant">Courant</option>
                    <option value="Joint">Joint</option>
                    <option value="Professionnel">Professionnel</option>
                </select>
            </div>

            <div class="mb-3 input-group">
                <input type="number" name="solde" class="form-control" placeholder="Solde initial" step="0.01" min="1" required />
            </div>

            <div class="mb-3 input-group">
                <select name="devise" class="form-select" required>
                    <option value="">Devise</option>
                    <option value="CDF">CDF (Franc Congolais)</option>
                    <option value="BIF">BIF (Franc Burundais)</option>
                    <option value="USD">USD (Dollar US)</option>
                    <option value="EUR">EUR (Euro)</option>
                    <option value="UGX">UGX (Shilling Ougandais)</option>
                </select>
            </div>

            <div class="mb-3 input-group">
                <input type="number" name="limite_retrait" class="form-control" placeholder="Limite de retrait" step="0.01" min="1" required />
            </div>

            <div class="mb-3 input-group">
                <select name="statut_compte" class="form-select" required>
                    <option value="">Statut du compte</option>
                    <option value="Activé">Activé</option>
                    <option value="Désactivé">Désactivé</option>
                </select>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger" role="alert">
                    <?= htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success" role="alert">
                    <?= htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>

            <button type="submit" class="btn btn-success w-100 mb-2">Créer le compte</button>
            <a href="home.php" class="btn btn-secondary w-100">Retour</a>
        </form>
    </article>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
