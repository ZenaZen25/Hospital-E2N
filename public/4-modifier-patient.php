<?php
require_once "../utils/db_connect.php";
require_once "../partials/header.php";

// Recupero ID dalla URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

if (!$id) {
    die("<p style='color:red;'>ID paziente mancante. Torna alla lista e riprova.</p>");
}

// Recupero dati attuali del paziente per popolare il form
$stmt = $db->prepare("SELECT * FROM patients WHERE id = ?");
$stmt->execute([$id]);
$patient = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$patient) {
    die("<p style='color:red;'>Paziente non trovato nel database.</p>");
}
?>

<section class="container">
    <h2>Modifier le patient : <?= htmlspecialchars($patient['firstname'] . " " . $patient['lastname']) ?></h2>

    <?php if (isset($_GET['error'])): ?>
        <p style="color: white; background: red; padding: 10px;">⚠️ Errore durante l'aggiornamento. Controlla i dati.</p>
    <?php endif; ?>

    <form action="../process/4-modifier-patient.php" method="POST">
        <input type="hidden" name="id" value="<?= $patient['id'] ?>">

        <div style="margin-bottom: 10px;">
            <label>Nom :</label><br>
            <input type="text" name="lastname" value="<?= htmlspecialchars($patient['lastname']) ?>" required>
        </div>

        <div style="margin-bottom: 10px;">
            <label>Prénom :</label><br>
            <input type="text" name="firstname" value="<?= htmlspecialchars($patient['firstname']) ?>" required>
        </div>

        <div style="margin-bottom: 10px;">
            <label>Date de naissance :</label><br>
            <input type="date" name="birthdate" value="<?= $patient['birthdate'] ?>" required>
        </div>

        <div style="margin-bottom: 10px;">
            <label>Téléphone :</label><br>
            <input type="tel" name="phone" value="<?= htmlspecialchars($patient['phone']) ?>">
        </div>

        <div style="margin-bottom: 10px;">
            <label>Email :</label><br>
            <input type="email" name="mail" value="<?= htmlspecialchars($patient['mail']) ?>" required>
        </div>

        <button type="submit" style="padding: 10px 20px; cursor: pointer; background: #2ecc71; color: white; border: none; border-radius: 5px;">
            Enregistrer les modifications
        </button>
    </form>

    <br>
    <a href="2-liste-des-patient.php">← Retour à la liste</a>
</section>

<?php
require_once "../partials/footer.php";
?>