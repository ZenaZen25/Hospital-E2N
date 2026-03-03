<?php
require_once "../partials/header.php";
require_once "../utils/db_connect.php";

// 1. Recuperiamo l'ID dall'URL (Parametro GET)
$id = $_GET['id'] ?? null;

if (!$id) {
    echo "ID paziente mancante.";
    exit;
}

try {
    // 2. Query per ottenere i dati del paziente specifico
    // Ricorda: la tua colonna si chiama 'mail', non 'email'
    $sql = "SELECT * FROM patients WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->execute([':id' => $id]);
    $patient = $stmt->fetch();

    if (!$patient) {
        echo "Paziente non trovato.";
        exit;
    }
} catch (PDOException $e) {
    die("Errore: " . $e->getMessage());
}
?>

<main class="container">
    <h2>Détails du Patient</h2>

    <div class="card">
        <p><strong>Nom :</strong> <?= htmlspecialchars($patient['lastname']) ?></p>
        <p><strong>Prénom :</strong> <?= htmlspecialchars($patient['firstname']) ?></p>
        <p><strong>Date de naissance :</strong> <?= htmlspecialchars($patient['birthdate']) ?></p>
        <p><strong>Téléphone :</strong> <?= htmlspecialchars($patient['phone']) ?></p>
        <p><strong>Email :</strong> <?= htmlspecialchars($patient['mail']) ?></p>
    </div>

    <br>
    <a href="2-liste-des-patient.php" class="btn">Retour à la liste</a>
    <a href="4-modifier-patient.php?id=<?= $patient['id'] ?>" class="btn-modifier">Modifier</a>
    <hr>
    <td>

    </td>
</main>

<?php require_once "../partials/footer.php"; ?>