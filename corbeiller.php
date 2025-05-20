<?php
session_start();
$conn = new mysqli("localhost", "root", "", "guichet_automatique");

if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

if (!isset($_SESSION['user_id'])) {
    die("Accès refusé. Veuillez vous connecter.");
}

$user_id = $_SESSION['user_id'];
$type = $_SESSION['type']; 
if (isset($_GET['restaurer'])) {
    $id = intval($_GET['restaurer']);
    $result = $conn->query("SELECT * FROM corbeille WHERE id = $id");
    if ($row = $result->fetch_assoc()) {
        if ($type === 'admin' || $row['user_id'] == $user_id) {
            $table = $row['table_source'];
            $data = json_decode($row['donnees'], true);
            $columns = implode(", ", array_keys($data));
            $values = implode("', '", array_map([$conn, 'real_escape_string'], array_values($data)));
            $id_original = intval($row['id_original']);

            $conn->query("INSERT INTO `$table` (id, $columns) VALUES ($id_original, '$values')");
            $conn->query("DELETE FROM corbeille WHERE id = $id");
        }
    }
}

// Supprimer définitivement
if (isset($_GET['supprimer'])) {
    $id = intval($_GET['supprimer']);
    $result = $conn->query("SELECT * FROM corbeille WHERE id = $id");
    if ($row = $result->fetch_assoc()) {
        if ($type === 'admin' || $row['user_id'] == $user_id) {
            $conn->query("DELETE FROM corbeille WHERE id = $id");
        }
    }
}

if (isset($_GET['vider'])) {
    if ($type === 'admin') {
        $conn->query("TRUNCATE TABLE corbeille");
    } else {
        $conn->query("DELETE FROM corbeille WHERE user_id = $user_id");
    }
}

if ($type === 'admin') {
    $result = $conn->query("SELECT * FROM corbeille ORDER BY date_action DESC");
} else {
    $result = $conn->query("SELECT * FROM corbeille WHERE user_id = $user_id ORDER BY date_action DESC");
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Corbeille</title>
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .actions a { margin-right: 10px; text-decoration: none; }
        .vider { color: red; font-weight: bold; }
    </style>
</head>
<body>

<h1>Corbeille</h1>

<a href="corbeiller.php?vider=1" onclick="return confirm('Vider la corbeille ?');" class="vider">🧹 Vider la corbeille</a>

<?php if ($result && $result->num_rows > 0): ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <?php if ($type === 'admin'): ?>
                    <th>Utilisateur</th>
                <?php endif; ?>
                <th>Table</th>
                <th>ID Original</th>
                <th>Données</th>
                <th>Action</th>
                <th>Date</th>
                <th>Opérations</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <?php if ($type === 'admin'): ?>
                    <td><?= $row['user_id'] ?></td>
                <?php endif; ?>
                <td><?= htmlspecialchars($row['table_source']) ?></td>
                <td><?= $row['id_original'] ?></td>
                <td><pre><?= json_encode(json_decode($row['donnees']), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?></pre></td>
                <td><?= $row['type_action'] ?></td>
                <td><?= $row['date_action'] ?></td>
                <td class="actions">
                    <a href="corbeiller.php?restaurer=<?= $row['id'] ?>" onclick="return confirm('Restaurer cet élément ?')">♻️ Restaurer</a>
                    <a href="corbeiller.php?supprimer=<?= $row['id'] ?>" onclick="return confirm('Supprimer définitivement ?')">🗑️ Supprimer</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>La corbeille est vide.</p>
<?php endif; ?>

</body>
</html>
