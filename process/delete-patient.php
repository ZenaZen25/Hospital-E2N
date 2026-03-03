<?php
/**
 * ETAPE 1: Controllo del Metodo
 * Impediamo la cancellazione accidentale tramite link (GET). Accettiamo solo POST.
 */
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../public/2-liste-des-patient.php?error=bad-method");
    exit();
}

/**
 * ETAPE 2: Controllo Esistenza Campi
 * Verifichiamo che la chiave 'id_patient' sia stata inviata dal form.
 */
if (!isset($_POST["id_patient"])) {
    header("Location: ../public/2-liste-des-patient.php?error=missing-input");
    exit();
}

/**
 * ETAPE 3: Controllo Valori Vuoti
 * Verifichiamo che l'ID non sia una stringa vuota.
 */
if (empty($_POST["id_patient"])) {
    header("Location: ../public/2-liste-des-patient.php?error=missing-id");
    exit();
}

/**
 * ETAPE 4: Recupero e Sanitizzazione
 * Convertiamo l'ID in un intero (int) per eliminare qualsiasi carattere dannoso.
 */
$id_patient = (int)$_POST["id_patient"];

/**
 * ETAPE 5: Esecuzione Sicura con Transazione
 */
require_once "../utils/db_connect.php";

try {
    // Iniziamo una transazione: se una query fallisce, la seconda non viene eseguita
    $db->beginTransaction();

    // 1. Cancelliamo prima gli appuntamenti (usando la colonna 'patient_id' vista nel tuo DB)
    $sqlRdv = "DELETE FROM appointments WHERE patient_id = :id";
    $stmtRdv = $db->prepare($sqlRdv);
    $stmtRdv->execute([':id' => $id_patient]);

    // 2. Cancelliamo il paziente
    $sqlPatient = "DELETE FROM patients WHERE id = :id";
    $stmtPatient = $db->prepare($sqlPatient);
    $stmtPatient->execute([':id' => $id_patient]);

    // Se tutto è andato bene, salviamo le modifiche definitivamente
    $db->commit();

    // Redirezione finale con messaggio di successo
    header("Location: ../public/2-liste-des-patient.php?status=deleted");
    exit();

} catch (PDOException $e) {
    // Se qualcosa va storto (es. errore database), annulliamo tutto
    $db->rollBack();
    header("Location: ../public/2-liste-des-patient.php?error=db-error");
    exit();
}