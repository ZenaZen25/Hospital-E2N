<?php
// 1. VERIFICA DEL METODO (Sicurezza d'accesso)
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../public/6-liste-rendezvous.php");
    exit();
}

require_once "../utils/db_connect.php";

// 2. VERIFICA ESISTENZA CAMPI (isset)
if (
    !isset($_POST["id"]) || 
    !isset($_POST["patient_id"]) || 
    !isset($_POST["datehour"])
) {
    header("Location: ../public/6-liste-rendezvous.php?error=missing-input");
    exit();
}

// 3. VERIFICA VALORI VUOTI (empty + trim)
if (
    empty(trim($_POST["id"])) || 
    empty(trim($_POST["patient_id"])) || 
    empty(trim($_POST["datehour"]))
) {
    $id = (int)$_POST["id"]; // Recuperiamo l'ID per tornare alla pagina corretta
    header("Location: ../public/8-modifier-rdv.php?id=$id&error=empty-values");
    exit();
}

// 4. RECUPERO E TIPIZZAZIONE (Casting e Sanificazione)
$id         = (int)$_POST["id"];
$patient_id = (int)$_POST["patient_id"];
$datehour   = trim($_POST["datehour"]);

// 5. ESECUZIONE SICURA (Prepared Statements)
try {
    // Utilizziamo i segnaposti (:name) per prevenire SQL Injection
    $sql = "UPDATE appointments 
            SET datehour = :datehour, patient_id = :patient_id 
            WHERE id = :id";
            
    $stmt = $db->prepare($sql);
    
    // Eseguiamo passando l'array dei parametri puliti
    $stmt->execute([
        ':datehour'   => $datehour, 
        ':patient_id' => $patient_id, 
        ':id'         => $id
    ]);

    // Redirezione in caso di successo
    header("Location: ../public/rendezvous.php?id=$id&success=1");
    exit();

} catch (PDOException $e) {
    // Gestione dell'errore database senza esporre dati sensibili
    header("Location: ../public/8-modifier-rdv.php?id=$id&error=db-fail");
    exit();
}