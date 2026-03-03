<?php
// Attiviamo gli errori per non vedere più la pagina bianca se qualcosa non va
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once "../utils/db_connect.php";
require_once "../partials/header.php";

$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

if (!$id) {
    die("ID mancante nell'URL.");
}

try {
    // Query JOIN basata sullo screenshot: appointments.patient_id = patients.id
    $sql = "SELECT appointments.id AS appointment_id, 
                   appointments.datehour, 
                   patients.lastname, 
                   patients.firstname, 
                   patients.mail, 
                   patients.phone, 
                   patients.birthdate
            FROM appointments 
            INNER JOIN patients ON appointments.patient_id = patients.id 
            WHERE appointments.id = :id";

    $stmt = $db->prepare($sql);
    $stmt->execute([':id' => $id]);
    $rdv = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$rdv) {
        die("Nessun appuntamento trovato per l'ID " . $id);
    }

    $dt = new DateTime($rdv['datehour']);
} catch (PDOException $e) {
    die("Errore SQL: " . $e->getMessage());
}
?>

<main class="container">
    <div class="header-flex">
        <h2>Détails du Rendez-vous #<?= $id ?></h2>
        <a href="6-liste-rendezvous.php" class="btn-edit">← Retour à la liste</a>
    </div>

    <div class="details-card">
        <div class="details-section">
            <h3> Informations RDV</h3>
            <p><strong>Date :</strong> <?= $dt->format('d/m/Y') ?></p>
            <p><strong>Heure :</strong> <?= $dt->format('H:i') ?></p>
        </div>

        <div class="details-section">
            <h3> Patient</h3>
            <p><strong>Nom :</strong> <?= htmlspecialchars(strtoupper($rdv['lastname'])) ?> <?= htmlspecialchars($rdv['firstname']) ?></p>
            <p><strong>Email :</strong> <?= htmlspecialchars($rdv['mail']) ?></p>
            <p><strong>Tel :</strong> <?= htmlspecialchars($rdv['phone']) ?></p>
            <p><strong>Né(e) le :</strong> <?= date('d/m/Y', strtotime($rdv['birthdate'])) ?></p>
        </div>
    </div>

    <div class="navigation-links">
        <a href="8-modifier-rdv.php?id=<?= $id ?>" class="btn-submit"> Modifier</a>
    </div>
</main>

<?php require_once "../partials/footer.php"; ?>