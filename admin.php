<?php
session_start();
require 'Backend/connexion/conn.php';

if (!isset($_SESSION['user_id'])) {
    exit("Accès non autorisé");
}

$usersQuery = "
    SELECT u.id, u.nom, u.email, u.statut_compte, cb.solde
    FROM users u
    LEFT JOIN comptebancaire cb ON cb.clientId = u.id
    WHERE u.type != 'Admin'
";
$usersResult = $conn->query($usersQuery);
$usersQuery = "
    SELECT u.id, u.nom, u.email,u.type, u.statut_compte, cb.solde
    FROM users u
    LEFT JOIN comptebancaire cb ON cb.clientId = u.id
    WHERE u.type = 'Admin'
";
$AmisResult = $conn->query($usersQuery);

$activitiesQuery = "
    SELECT h.*, 
           u.nom AS client_nom, 
           u.prenom AS client_prenom,
           g.nom AS guichet_nom
    FROM historique h
    JOIN users u ON h.idClient = u.id
    LEFT JOIN guichetautomatique g ON h.idGuichet = g.idGuichet
    ORDER BY h.dateHeure DESC
    LIMIT 5
";
$activitiesResult = $conn->query($activitiesQuery);


// Fonction pour format relatif
function tempsRelatif($datetime) {
    $now = new DateTime();
    $date = new DateTime($datetime);
    $diff = $now->diff($date);

    if ($diff->y > 0) return "il y a " . $diff->y . " an" . ($diff->y > 1 ? "s" : "");
    if ($diff->m > 0) return "il y a " . $diff->m . " mois";
    if ($diff->d > 0) return "il y a " . $diff->d . " jour" . ($diff->d > 1 ? "s" : "");
    if ($diff->h > 0) return "il y a " . $diff->h . " heure" . ($diff->h > 1 ? "s" : "");
    if ($diff->i > 0) return "il y a " . $diff->i . " minute" . ($diff->i > 1 ? "s" : "");
    return "à l'instant";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>Admin Guichet Automatique</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <style>
    body { background-color: #f8f9fa; }
    .sidebar { min-height: 100vh; background-color: #212529; }
    .sidebar .nav-link { color: #adb5bd; }
    .sidebar .nav-link:hover { color: white; }
    .table td, .table th { vertical-align: middle; }
  </style>
</head>
<body>

<div class="d-flex">
  <!-- Sidebar -->
  <div class="sidebar p-3" style="width: 20%;">
    <h4 class="text-white mb-4">Admin</h4>
    <ul class="nav flex-column">
      <li class="nav-item"><a class="nav-link" href="admin.php"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
      <li class="nav-item"><a class="nav-link" href="add.php"><i class="bi bi-people-fill me-2"></i>Utilisateurs</a></li>
      <li class="nav-item"><a class="nav-link" href="pages/historique.php"><i class="bi bi-clock-history me-2"></i>Activités</a></li>
      <li class="nav-item"><a class="nav-link" href="Paramètres.php"><i class="bi bi-sliders me-2"></i>Paramètres</a></li>
      <li class="nav-item"><a class="nav-link" href="corbeiller.php"><i class="bi bi-trash me-2"></i>Consulter la corbeille</a></li>
      <li class="nav-item"><a class="nav-link" href="pages/logout.php"><i class="bi bi-box-arrow-right me-2"></i>Déconnexion</a></li>
    </ul>
  </div>

  <div class="container-fluid p-4">
   <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
  <h2>Clients du système</h2>

  <a class="nav-link" href="add.php">
    <button class="btn btn-primary">
      <i class="bi bi-person-plus-fill me-1"></i> Ajouter un utilisateur
    </button>
  </a>

  <a class="nav-link" href="corbeiller.php">
    <button class="btn btn-danger">
      <i class="bi bi-trash-fill me-1"></i> Consulter la corbeille
    </button>
  </a>

  <a class="nav-link" href="addGuichet.php">
    <button class="btn btn-success">
      <i class="bi bi-shop-window me-1"></i> Ajouter un guichet
    </button>
  </a>

  <a class="nav-link" href="add.php">
    <button class="btn btn-secondary">
      <i class="bi bi-wallet2 me-1"></i> Ajouter un compte
    </button>
  </a>
</div>

    
   <table class="table table-bordered">
    <h2 style="margin:10px"><i class="bi bi-people-fill me-2"></i>Historique du systeme</h2>
    <thead>
        <tr>
            <th>IdHistorique</th>
            <th>Date & Heure</th>
            <th>Type d'Événement</th>
            <th>Message</th>
            <th>Guichet</th>
            <th>Client</th>
        </tr>
    </thead>
    <tbody>
    <?php
    if ($activitiesResult->num_rows > 0) {
        while ($row = $activitiesResult->fetch_assoc()) {
            echo "<tr>";
            echo "<td data-label='IdHistorique'>" . htmlspecialchars($row['idHistorique']) . "</td>";
            echo "<td data-label='Date & Heure'>" . htmlspecialchars(tempsRelatif($row['dateHeure'])) . "</td>";
            echo "<td data-label='Type d\'Événement'>" . htmlspecialchars($row['typeEvenement']) . "</td>";
            echo "<td data-label='Message'>" . htmlspecialchars($row['message']) . "</td>";
            echo "<td data-label='Guichet'>" . htmlspecialchars($row['guichet_nom'] ?? 'Inconnu') . "</td>";
            echo "<td data-label='Client'>" . htmlspecialchars(trim($row['client_nom'] . ' ' . $row['client_prenom'])) . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='6' class='text-center'>Aucune donnée trouvée pour votre historique.</td></tr>";
    }
    ?>
    </tbody>
</table>
  
    <div class="card shadow-sm mb-5">
      <div class="card-body">
        <table class="table table-hover">
          <thead class="table-light">
            <tr>
              <th>ID</th>
              <th>Nom</th>
              <th>Email</th>
              <th>Solde (€)</th>
              <th>Statut</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="userTable">
           <?php while ($row = $usersResult->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['id']) ?></td>
              <td><?= htmlspecialchars($row['nom']) ?></td>
              <td><?= htmlspecialchars($row['email']) ?></td>
              <td><?= number_format($row['solde'], 2, ',', ' ') ?></td>
              <td>
                <?php if ($row['statut_compte'] == 'actif'): ?>
                  <span class="badge bg-success">Actif</span>
                <?php else: ?>
                  <span class="badge bg-secondary">Inactif</span>
                <?php endif; ?>
              </td>
              <td>
                <a href="modifier.php?id=<?= urlencode($row['id']) ?>" class="btn btn-sm btn-outline-warning me-1" title="Modifier">
                  <i class="bi bi-pencil-square"></i>
                </a>
                <a href="supprimer.php?id=<?= urlencode($row['id']) ?>" class="btn btn-sm btn-outline-danger" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                  <i class="bi bi-trash"></i>
                </a>
              </td>
            </tr>
              <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
    <div class="card shadow-sm mb-5">
      <h2 style="margin:10px"><i class="bi bi-people-fill me-2"></i>Tous les Admins du systeme</h2>
      <div class="card-body">
        <table class="table table-hover">
          <thead class="table-light">
            <tr>
              <th>ID</th>
              <th>Nom</th>
              <th>Email</th>
              <th>Profil</th>
              <th>Statut</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="userTable">
           <?php while ($row = $AmisResult->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['id']) ?></td>
              <td><?= htmlspecialchars($row['nom']) ?></td>
              <td><?= htmlspecialchars($row['email']) ?></td>
              <td><?= htmlspecialchars($row['type']) ?></td>
              <td>
                <?php if ($row['statut_compte'] == 'actif'): ?>
                  <span class="badge bg-success">Actif</span>
                <?php else: ?>
                  <span class="badge bg-secondary">Inactif</span>
                <?php endif; ?>
              </td>
              <td>
                <a href="modifier.php?id=<?= urlencode($row['id']) ?>" class="btn btn-sm btn-outline-warning me-1" title="Modifier">
                  <i class="bi bi-pencil-square"></i>
                </a>
                <a href="supprimer.php?id=<?= urlencode($row['id']) ?>" class="btn btn-sm btn-outline-danger" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                  <i class="bi bi-trash"></i>
                </a>
              </td>
            </tr>
              <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>

  </div>
</div>

<script>
  function ajouterUtilisateur() {
    alert("Formulaire d'ajout d'utilisateur ici...");
  }
</script>


</body>
</html>
