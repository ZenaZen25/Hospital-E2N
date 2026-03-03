<?php 

require_once "../utils/db_connect.php";
require_once "../partials/header.php";

?>

<main class="container">
    <h2>Ajouter un Patient et son Rendez-vous</h2>

    <form action="../process/add-patient-appointment.php" method="POST" class="appointment-form">
        
        <div class="form-section-title">Informations du Patient</div>
        
        <div class="form-group">
            <label for="lastname">Nom :</label>
            <input type="text" id="lastname" name="lastname" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="firstname">Prénom :</label>
            <input type="text" id="firstname" name="firstname" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="mail">Email :</label>
            <input type="email" id="mail" name="mail" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="phone">Téléphone :</label>
            <input type="text" id="phone" name="phone" class="form-control">
        </div>

        <div class="form-group">
            <label for="birthdate">Date de Naissance :</label>
            <input type="date" id="birthdate" name="birthdate" class="form-control" required>
        </div>

        <div class="form-section-title">Détails du Rendez-vous</div>
        
        <div class="form-group">
            <label for="dateHour">Date et Heure du RDV :</label>
            <input type="datetime-local" id="dateHour" name="dateHour" class="form-control" required>
        </div>

        <button type="submit" class="btn-submit">Enregistrer tout</button>
        
        <div class="cancel-box">
            <a href="2-liste-des-patient.php" class="btn-delete">Annuler</a>
        </div>
    </form>
</main>

<?php require_once "../partials/footer.php"; ?>