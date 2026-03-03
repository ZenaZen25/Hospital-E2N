<?php
require_once "../utils/db_connect.php";
require_once "../partials/header.php";

$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
if (!$id) { header("Location: 6-liste-rendezvous.php"); exit(); }

try {
    // Dati appuntamento
    $stmtRdv = $db->prepare("SELECT * FROM appointments WHERE id = :id");
    $stmtRdv->execute([':id' => $id]);
    $rdv = $stmtRdv->fetch(PDO::FETCH_ASSOC);

    // Lista pazienti per la select
    $patients = $db->query("SELECT id, lastname, firstname FROM patients ORDER BY lastname ASC")->fetchAll();

    if (!$rdv) { die("RDV non trovato."); }
    
} catch (PDOException $e) { die("Errore: " . $e->getMessage()); }
?>

<main class="container">
    <h2>Modifier le Rendez-vous</h2>

    <form action="../process/8-process-modifier-rdv.php" method="POST">
        <input type="hidden" name="id" value="<?= $id ?>">

        <div class="form-group">
            <label>Patient :</label>
            <select name="patient_id" class="form-control" required>
                <?php foreach ($patients as $p): ?>
                    <option value="<?= $p['id'] ?>" <?= ($p['id'] == $rdv['patient_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars(strtoupper($p['lastname']) . " " . $p['firstname']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Date et Heure :</label>
            <?php $dateValue = date('Y-m-d\TH:i', strtotime($rdv['datehour'])); ?>
            <input type="datetime-local" name="datehour" value="<?= $dateValue ?>" class="form-control" required>
        </div>

        <button type="submit" class="btn-submit">Mettre à jour</button>
    </form>
</main>

<?php require_once "../partials/footer.php"; ?>