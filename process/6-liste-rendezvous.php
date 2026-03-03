<?php
require_once "../utils/db_connect.php";
// ... (Logica per recuperare i dati dal database) ...

require_once "../partials/header.php"; 
?>

<main class="container">
    <h2>Liste des Rendez-vous</h2>

    <?php 
    if (isset($_GET['success'])) {
        echo "<p style='background-color: #d4edda; color: #155724; padding: 10px; border: 1px solid #c3e6cb; border-radius: 5px;'>
                ✅ Rendez-vous aggiunto correttamente!
              </p>";
    }
    ?>

    <table>
        </table>
</main>

<?php require_once "../partials/footer.php"; ?>