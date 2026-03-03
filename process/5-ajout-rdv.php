<?php
/**
 * ETAPE 1: Controllo del Metodo
 * Verifichiamo che i dati arrivino solo tramite POST
 */
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../public/5-ajout-rdv.php?error=bad-method");
    exit();
}

/**
 * ETAPE 2: Controllo Esistenza Campi
 * Verifichiamo che le chiavi dell'array $_POST esistano
 */
if (!isset($_POST["id_patient"]) || !isset($_POST["datehour"])) {
    header("Location: ../public/5-ajout-rdv.php?error=missing-input");
    exit();
}

/**
 * ETAPE 3: Controllo Valori Vuoti
 * Verifichiamo che l'utente non abbia inviato campi vuoti
 */
if (empty($_POST["id_patient"]) || empty($_POST["datehour"])) {
    header("Location: ../public/5-ajout-rdv.php?error=missing-value");
    exit();
}

/**
 * ETAPE 4: Recupero e Sanitizzazione
 * Puliamo i dati per evitare problemi di formattazione
 */
$id_patient = (int)$_POST["id_patient"];
$datehour   = trim($_POST["datehour"]);

/**
 * ETAPE 5: Esecuzione Sicura (Prepared Statements)
 * Protezione contro SQL Injection
 */
require_once "../utils/db_connect.php";

try {
    // Nota: Ho usato 'idPatients' perché è il nome standard del database dell'esercizio
    $sql = "INSERT INTO appointments (datehour, patient_id) 
            VALUES (:datehour, :idpatient)";
   

    $stmt = $db->prepare($sql);
    $stmt->execute([
        ':datehour'  => $datehour,
        ':idpatient' => $id_patient
    ]);
    

    // Se tutto va bene, torniamo alla lista
    header("Location: ../public/6-liste-rendezvous.php?success=add-appointment");
    exit();

} catch (PDOException $e) {
    // In caso di errore del database (es. colonna errata)
    header("Location: ../public/5-ajout-rdv.php?error=db-error");
    exit();
}