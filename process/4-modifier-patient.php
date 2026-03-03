<?php
// 1. Controllo metodo POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../public/2-liste-des-patient.php?error=bad-method");
    exit();
}

// 2. Controllo presenza ID e campi obbligatori
if (empty($_POST["id"]) || empty($_POST["lastname"]) || empty($_POST["firstname"]) || empty($_POST["mail"])) {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : '';
    // Corretto il nome del file della lista
    header("Location: ../public/2-liste-des-patient.php?id=$id&error=missing-value");
    exit();
}

// 3. Recupero e pulizia dati (Sanitization)
$id        = (int)$_POST["id"];
$lastname  = trim($_POST["lastname"]);
$firstname = trim($_POST["firstname"]);
$birthdate = trim($_POST["birthdate"]);
$phone     = trim($_POST["phone"]);
$mail      = trim($_POST["mail"]);

/** * NOTA SULLA SICUREZZA: 
 * htmlspecialchars() si usa di solito quando STAMPI i dati nell'HTML, 
 * non quando li salvi nel database. Per il database basta il Prepared Statement.
 */

// 4. Connessione al database
require_once "../utils/db_connect.php";

try {
    $sql = "UPDATE patients 
            SET lastname = :lastname, 
                firstname = :firstname, 
                birthdate = :birthdate, 
                phone = :phone, 
                mail = :mail 
            WHERE id = :id";

    $stmt = $db->prepare($sql);
    $stmt->execute([
        ':lastname'  => $lastname,
        ':firstname' => $firstname,
        ':birthdate' => $birthdate,
        ':phone'     => $phone,
        ':mail'      => $mail,
        ':id'        => $id
    ]);

    // 5. Redirect alla lista (Assicurati che il nome del file sia esatto)
    header("Location: ../public/2-liste-des-patient.php?success=update-patient");
    exit();

} catch (PDOException $e) {
    header("Location: ../public/4-modifier-patient.php?id=$id&error=db-error");
    exit();
}