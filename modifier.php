<?php
require 'Backend/connexion/conn.php';  
if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    die("ID utilisateur invalide.");
}

$userId = (int) $_GET['id'];

// Récupérer les infos de l'utilisateur
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Utilisateur non trouvé.");
}

$user = $result->fetch_assoc();

// Ici tu peux ajuster les valeurs des selects en fonction des données récupérées

// Pour la sélection du rôle et du statut_compte, on prépare des variables pour marquer "selected"
function selected($value, $current) {
    return $value === $current ? "selected" : "";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Modifier un utilisateur - Guichet Automatique</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <style>
    body { background-color: #f4f6f9; }
    .container { max-width: 800px; margin-top: 50px; }
    .form-section { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.05); }
  </style>
</head>
<body>

  <div class="container">
    <div class="form-section">
      <h4 class="mb-4 text-secondary">Modifier l'utilisateur</h4>
      <form action="modifier_utilisateur.php" method="POST">
        
        <!-- ID utilisateur caché -->
        <input type="hidden" name="user_id" value="<?= htmlspecialchars($user['id']) ?>">

        <!-- Nom et Prénom -->
        <div class="d-flex justify-content-start gap-3 m-auto">
          <div class="mb-3 w-100">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" class="form-control" id="nom" name="nom" value="<?= htmlspecialchars($user['nom']) ?>" required>
          </div>
          <div class="mb-3 w-100">
            <label for="prenom" class="form-label">Prénom</label>
            <input type="text" class="form-control" id="prenom" name="prenom" value="<?= htmlspecialchars($user['prenom']) ?>" required>
          </div>
        </div>

        <!-- Email -->
        <div class="mb-3">
          <label for="email" class="form-label">Adresse email</label>
          <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
        </div>

        <!-- Mot de passe -->
        <div class="mb-3">
          <label for="mot_de_passe" class="form-label">Mot de passe (laisser vide pour ne pas changer)</label>
          <div class="input-group">
            <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe">
            <span class="input-group-text">
              <i class="bi bi-eye-slash" id="togglePassword" style="cursor: pointer;"></i>
            </span>
          </div>
        </div>

        <!-- Rôle et statut_compte -->
        <div class="d-flex justify-content-start gap-3 m-auto">
          <div class="mb-3 w-100">
            <label for="type" class="form-label">Rôle</label>
            <select class="form-select" id="type" name="type" required>
              <option value="">-- Sélectionner --</option>
              <option value="client" <?= selected("client", $user['type']) ?>>client</option>
              <option value="Admin" <?= selected("Admin", $user['type']) ?>>Admin</option>
            </select>
          </div>
          <div class="mb-3 w-100">
            <label for="statut_compte" class="form-label">statut_compte</label>
            <select class="form-select" id="statut_compte" name="statut_compte" required>
              <option value="Activé" <?= selected("Activé", $user['statut_compte']) ?>>Activé</option>
              <option value="Désactivé" <?= selected("Désactivé", $user['statut_compte']) ?>>Désactivé</option>
              <option value="Bloqué" <?= selected("Bloqué", $user['statut_compte']) ?>>Bloqué</option>
            </select>
          </div>
        </div>

        <!-- Note informative -->
        <small class="text-muted text-center d-block fst-italic">
          Vous pouvez modifier les informations nécessaires puis enregistrer les changements.
        </small>

        <!-- Boutons -->
        <div class="text-end">
          <div class="d-flex gap-2 justify-content-end mt-4">
            <a href="admin.php" class="btn btn-outline-dark">Annuler</a>
            <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
          </div>
        </div>

      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const togglePassword = document.getElementById("togglePassword");
    const passwordField = document.getElementById("mot_de_passe");

    togglePassword.addEventListener("click", function () {
      const type = passwordField.getAttribute("type") === "password" ? "text" : "password";
      passwordField.setAttribute("type", type);
      this.classList.toggle("bi-eye");
      this.classList.toggle("bi-eye-slash");
    });
  </script>

</body>
</html>
