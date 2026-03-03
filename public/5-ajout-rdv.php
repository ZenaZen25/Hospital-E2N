<?php
// 1. Connessione al database
require_once "../utils/db_connect.php";
if (isset($db) && !isset($pdo)) { $pdo = $db; }

$message = "";

// 2. Recupero dei pazienti per il menu a tendina
try {
    // Recuperiamo i pazienti per poterli selezionare nel form
    $stmtPatients = $pdo->query("SELECT id, lastname, firstname FROM patients ORDER BY lastname ASC");
    $patients = $stmtPatients->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $message = "<p style='color: red;'>Errore nel caricamento pazienti: " . $e->getMessage() . "</p>";
}

// 3. Elaborazione del form (Salvataggio nel DB)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $date = $_POST['date'];
    $time = $_POST['time'];
    $idPatient = $_POST['id_patient'];

    // Uniamo data e ora nel formato DATETIME (YYYY-MM-DD HH:MM:SS)
    $dateHourFormatted = $date . ' ' . $time;

    try {
        $sql = "INSERT INTO appointments (datehour, patient_id) VALUES (:dateHour, :idPatient)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':dateHour'  => $dateHourFormatted,
            ':idPatient' => $idPatient
        ]);
        
        $message = "<p style='color: green;'>Rendez-vous ajouter avec succes!</p>";
    } catch (PDOException $e) {
        $message = "<p style='color: red;'>Errore SQL: " . $e->getMessage() . "</p>";
    }
}

require_once "../partials/header.php";
?>

<section class="container">
    <h2>Ajouter un Rendez-vous (Exercice 5)</h2>
    <?= $message ?>

<form action="../process/5-ajout-rdv.php" method="POST" class="appointment-form">
    
    <div class="form-group">
        <label for="id_patient">Patient :</label>
        <select name="id_patient" id="id_patient" required class="form-control">
            <option value="">-- Selectionner un patient --</option>
            <?php foreach ($patients as $p): ?>
                <option value="<?= $p['id'] ?>">
                    <?= htmlspecialchars($p['lastname'] . " " . $p['firstname']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="datehour">Date et Heure :</label>
        <input type="datetime-local" name="datehour" id="datehour" required class="form-control">
    </div>

    <button type="submit" class="btn-submit">Créer le rendez-vous</button>
</form>

    <br>
    <a href="1-ajout-patient.php">⬅ Retour à l'accueil</a>
</section>

<?php require_once "../partials/footer.php"; ?>