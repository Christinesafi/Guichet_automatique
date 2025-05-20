<?php
session_start();
require 'Backend/connexion/conn.php'; 
$error = "";
$success = "";

function generateUserId($length = 12) {
    return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $password = trim($_POST['mot_de_passe']);
    $type = trim($_POST['type']);
    $statut_compte = trim($_POST['statut_compte']);

    if (empty($nom) || empty($prenom) || empty($email)) {
        $error = "Veuillez remplir tous les champs.";
    } else {
        $user_id = generateUserId(); // Génère un ID unique aléatoire
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO users (user_id, email, mot_de_passe, nom, prenom, type, statut_compte) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssss", $user_id, $email, $hashed_password, $nom, $prenom, $type, $statut_compte);

        if ($stmt->execute()) {
            $stmt->close();

            $matricule = "MAT" . date("Y") . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
            $statut = "actif";

            $query = "INSERT INTO personnels (id, type, matricule, statut) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssss", $user_id, $type, $matricule, $statut_compte);

            if ($stmt->execute()) {
                $success = "Inscription réussie. Vous pouvez maintenant vous connecter.";
            } else {
                $error = "Erreur lors de l'enregistrement dans personnels.";
            }
        } else {
            $error = "Erreur lors de l'enregistrement dans users.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Ajouter un utilisateur - Guichet Automatique</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <style>
    body {
      background-color: #f4f6f9;
    }
    .container {
      max-width: 800px;
      margin-top: 50px;
    }
    .form-section {
      background: white;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }
  </style>
</head>
<body>

  <div class="container">
    <div class="form-section">
      <h4 class="mb-4 text-secondary">Nouvel utilisateur</h4>
      <form  method="POST">
        
        <!-- Nom et Prénom -->
        <div class="d-flex justify-content-start gap-3 m-auto">
          <div class="mb-3 w-100">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" class="form-control" id="nom" name="nom" required>
          </div>
          <div class="mb-3 w-100">
            <label for="prenom" class="form-label">Prénom</label>
            <input type="text" class="form-control" id="prenom" name="prenom" required>
          </div>
        </div>

        <!-- Email -->
        <div class="mb-3">
          <label for="email" class="form-label">Adresse email</label>
          <input type="email" class="form-control" id="email" name="email" required>
        </div>

        <!-- Mot de passe -->
        <div class="mb-3">
          <label for="mot_de_passe" class="form-label">Mot de passe</label>
          <div class="input-group">
            <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe" required>
            <span class="input-group-text">
              <i class="bi bi-eye-slash" id="togglePassword" style="cursor: pointer;"></i>
            </span>
          </div>
        </div>

        <!-- Rôle et Statut -->
        <div class="d-flex justify-content-start gap-3 m-auto">
          <div class="mb-3 w-100">
            <label for="role" class="form-label">Rôle</label>
            <select class="form-select" id="role" name="type" required>
              <option value="Client" value="Client">Client</option>
              <option name="Admin" value="Admin">Administrateur</option>
            </select>
          </div>
          <div class="mb-3 w-100">
            <label for="statut" class="form-label">Statut</label>
            <select class="form-select" id="statut_compte" name="statut_compte" required>
              <option>Activé</option>
              <option>Désactivé</option>
              <option>Bloqué</option>
            </select>
          </div>
        </div>

        <!-- Note informative -->
        <small class="text-muted text-center d-block fst-italic">
          Veuillez fournir des informations exactes. Les utilisateurs auront accès selon les rôles définis.
        </small>

        <!-- Boutons -->
        <div class="text-end">
          <div class="d-flex gap-2 justify-content-end mt-4">
            <a href="admin.php"><button type="button" class="btn btn-outline-dark">Retour</button></a>
            <button type="reset" class="btn btn-outline-dark">Réinitialiser</button>
            <button type="submit" class="btn btn-success">Ajouter l'utilisateur</button>
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
