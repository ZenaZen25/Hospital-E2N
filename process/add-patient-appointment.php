<?php
require_once "../utils/db_connect.php";

// 1. VERIFICA METODO (Sicurezza 1)
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../public/ajout-patient-rendez-vous.php?error=bad-method");
    exit();
}

// 2. VERIFICA ESISTENZA CAMPI (Sicurezza 2)
if (
    !isset($_POST["lastname"]) || !isset($_POST["firstname"]) || 
    !isset($_POST["mail"])     || !isset($_POST["birthdate"]) || 
    !isset($_POST["dateHour"])
) {
    header("Location: ../public/ajout-patient-rendez-vous.php?error=missing-input");
    exit();
}

// 3. VERIFICA VALORI VUOTI (Sicurezza 3)
if (
    empty(trim($_POST["lastname"])) || empty(trim($_POST["firstname"])) || 
    empty(trim($_POST["mail"]))     || empty(trim($_POST["dateHour"]))
) {
    header("Location: ../public/ajout-patient-rendez-vous.php?error=empty-fields");
    exit();
}

// 4. RECUPERO E PULIZIA (Sicurezza 4)
$lastname  = trim($_POST["lastname"]);
$firstname = trim($_POST["firstname"]);
$mail      = trim($_POST["mail"]);
$phone     = trim($_POST["phone"]) ?? null;
$birthdate = $_POST["birthdate"];
$dateHourInput = $_POST["dateHour"];

// 5. ESECUZIONE CON TRANSACTION (Sicurezza 5)
try {
    // A. Iniziamo la transazione
    $db->beginTransaction();

    // B. Inserimento Paziente
    $sqlPatient = "INSERT INTO patients (lastname, firstname, birthdate, phone, mail) 
                   VALUES (:lastname, :firstname, :birthdate, :phone, :mail)";
    $stmtPatient = $db->prepare($sqlPatient);
    $stmtPatient->execute([
        ':lastname'  => $lastname,
        ':firstname' => $firstname,
        ':birthdate' => $birthdate,
        ':phone'     => $phone,
        ':mail'      => $mail
    ]);

    // C. Recupero dell'ID (Fondamentale per legare le tabelle)
    $idPatient = $db->lastInsertId();

    // D. Inserimento Appuntamento
    // NOTA: Verifica se la colonna è 'patient_id' o 'idPatients'
    $sqlRdv = "INSERT INTO appointments (dateHour, patient_id) VALUES (:dateHour, :patient_id)";
    $stmtRdv = $db->prepare($sqlRdv);
    $stmtRdv->execute([
        ':dateHour'   => $dateHourInput,
        ':patient_id' => $idPatient
    ]);

    // E. VALIDAZIONE FINALE: Se tutto è OK, salva nel DB
    $db->commit();

    header("Location: ../public/2-liste-des-patient.php?success=1");
    exit();

} catch (Exception $e) {
    // F. Se qualcosa fallisce, annulla TUTTO (il paziente non viene creato)
    if ($db->inTransaction()) {
        $db->rollBack();
    }
    die("Erreur lors de l'enregistrement : " . $e->getMessage());
}