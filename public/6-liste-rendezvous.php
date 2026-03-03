<?php
require_once "../utils/db_connect.php";

try {
    $sql = "SELECT 
            a.id AS appointment_id,
            a.datehour,
            p.lastname,
            p.firstname
        FROM appointments a
        INNER JOIN patients p 
            ON a.patient_id = p.id
        ORDER BY a.datehour ASC";

    $stmt = $db->query($sql);
    $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $appointments = [];
}

require_once "../partials/header.php";
?>

<main class="container">
    <div class="header-flex">
        <h2>Liste des Rendez-vous</h2>
        <a href="5-ajout-rdv.php" class="btn-submit btn-inline">+ Nouveau RDV</a>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="message-success">✅Rendez-vous enregistré avec succès !</div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="message-error">⚠️ Erreur : opération échouée.</div>
    <?php endif; ?>

    <table class="patient-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Heure</th>
                <th>Patient</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($appointments) > 0): ?>
                <?php foreach ($appointments as $app):
                    $dt = new DateTime($app['datehour']);
                ?>
                    <tr>
                        <td><?= $dt->format('d/m/Y') ?></td>
                        <td><?= $dt->format('H:i') ?></td>
                        <td>
                            <span class="lastname"><?= htmlspecialchars(strtoupper($app['lastname'])) ?><span>
                                    <?= htmlspecialchars($app['firstname']) ?>
                        </td>
                        <td>
                            <a href="7-rendezvous.php?id=<?= $app['appointment_id'] ?>" class="btn-edit">Détails</a>
                            |
                            <form action="../process/delete-rdv.php"
                                method="POST"
                                onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ?')"
                                class="delete-form">

                                <input type="hidden" name="id_appointment" value="<?= $app['appointment_id'] ?>">

                                <button type="submit" class="delete-button">
                                    Supprimer
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center">Aucun rendez-vous prévu.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>

<?php require_once "../partials/footer.php"; ?>