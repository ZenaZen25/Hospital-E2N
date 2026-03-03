<?php
// Includiamo l'header 
require_once "../partials/header.php";
?>

<main class="container">
    <h2>Exercice 1: Ajouter un patient</h2>

    <form action="../process/1-ajout-patient.php" method="POST">
        <div class="form-group">
            <label for="lastname">Nom :</label>
            <input type="text" name="lastname" id="lastname" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="firstname">Prénom :</label>
            <input type="text" name="firstname" id="firstname" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="birthdate">Date de naissance :</label>
            <input type="date" name="birthdate" id="birthdate" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="phone">Téléphone :</label>
            <input type="tel" name="phone" id="phone" class="form-control">
        </div>

        <div class="form-group">
            <label for="mail">Email :</label>
            <input type="email" name="email" id="mail" class="form-control" required>
        </div>

        <button type="submit" class="btn-submit">
            Enregistrer le patient
        </button>
    </form>



</main>

<?php
require_once "../partials/footer.php";
?>