<?php
require_once "../utils/db_connect.php";

if (isset($db) && !isset($pdo)) {
    $pdo = $db;
}

/* --------------------------------------------------------------------------
  1. configurazione della impaginazione (EX 13)
  -------------------------------------------------------------------------- */
$patientsParPage = 5;
$pageActuelle = isset($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1;
$offset = ($pageActuelle - 1) * $patientsParPage;

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

try {
    /* --------------------------------------------------------------------------
      2. COMPTAGE TOTAL
      -------------------------------------------------------------------------- */

    if (!empty($search)) {
        $sqlCount = "SELECT COUNT(*) FROM patients WHERE lastname LIKE :q OR firstname LIKE :q";
        $queryCount = $pdo->prepare($sqlCount);
        $queryCount->execute([':q' => "%$search%"]);
    } else {
        $sqlCount = "SELECT COUNT(*) FROM patients";
        $queryCount = $pdo->query($sqlCount);
    }

    $totalPatients = $queryCount->fetchColumn();
    $totalPages = ceil($totalPatients / $patientsParPage);

    /* --------------------------------------------------------------------------
      3. REQUÊTE FINALE (corretta)
      -------------------------------------------------------------------------- */

    if (!empty($search)) {
        // Usa i segnaposti :limit e :offset per mantenere la paginazione funzionante!
        $sql = "SELECT * FROM patients 
                WHERE lastname LIKE :q  
                OR firstname LIKE :q
                ORDER BY lastname ASC 
                LIMIT 5 OFFSET 0;";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':q', "%$search%", PDO::PARAM_STR);
    } else {
        $sql = "SELECT * FROM patients ORDER BY lastname ASC LIMIT :limit OFFSET :offset";
        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':limit', $patientsParPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    }

    $stmt->execute();

    // Questi bindValue sono fondamentali per gestire i 2 pazienti per pagina




    $patients = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur SQL : " . $e->getMessage());
}

require_once "../partials/header.php";
?>

<main class="container">
    <h2>Liste des Patients</h2>

    <form action="" method="GET" class="search-container" style="display:flex; gap:10px; margin-bottom:20px;">
        <input type="text" name="search" placeholder="Rechercher un patient..." value="<?= htmlspecialchars($search) ?>" class="form-control">
        <button type="submit" class="btn-submit" style="width:auto;">Rechercher</button>

        <?php if (!empty($search)): ?>
            <a href="2-liste-des-patient.php" class="btn-view" style="text-decoration:none;">Effacer</a>
        <?php endif; ?>
    </form>

    <table class="patient-table">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($patients) > 0): ?>
                <?php foreach ($patients as $patient): ?>
                    <tr>
                        <td><?= htmlspecialchars(strtoupper($patient['lastname'])) ?></td>
                        <td><?= htmlspecialchars($patient['firstname']) ?></td>
                        <td class="flex gap">
                            <a href="4-modifier-patient.php?id=<?= $patient['id'] ?>" class="btn-edit">Modifier</a>
                            <a href="3-profil-patient.php?id=<?= $patient['id'] ?>" class="btn-view">Voir</a>

                            <form action="../process/delete-patient.php" method="POST" onsubmit="return confirm('Supprimer ce patient ?')" style="display:inline;">
                                <input type="hidden" name="id_patient" value="<?= $patient['id'] ?>">
                                <button type="submit" class="btn-delete">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3" style="text-align:center; padding:20px;">Aucun patient trouvé.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="pagination" style="display:flex; justify-content:center; gap:15px; margin-top:20px;">
        <?php if ($pageActuelle > 1): ?>
            <a href="?page=<?= $pageActuelle - 1 ?>&search=<?= urlencode($search) ?>" class="btn-view">Précédent</a>
        <?php endif; ?>

        <span style="font-weight:bold; align-self:center;">Page <?= $pageActuelle ?> sur <?= $totalPages ?></span>

        <?php if ($pageActuelle < $totalPages): ?>
            <a href="?page=<?= $pageActuelle + 1 ?>&search=<?= urlencode($search) ?>" class="btn-view">Suivant</a>
        <?php endif; ?>
    </div>
</main>

<?php require_once "../partials/footer.php"; ?>