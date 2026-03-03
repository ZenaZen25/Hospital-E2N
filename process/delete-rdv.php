<?php
/**
 * ETAPE 1: Controllo del Metodo
 * Verifichiamo che la richiesta arrivi tramite POST (più sicuro per eliminare dati)
 */
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../public/6-liste-rendezvous.php?error=bad-method");
    exit();
}

/**
 * ETAPE 2: Controllo Esistenza Campi
 * Verifichiamo che l'ID dell'appuntamento da eliminare sia stato inviato
 */
if (!isset($_POST["id_appointment"])) {
    header("Location: ../public/6-liste-rendezvous.php?error=missing-input");
    exit();
}

/**
 * ETAPE 3: Controllo Valori Vuoti
 * Verifichiamo che l'ID non sia vuoto
 */
if (empty($_POST["id_appointment"])) {
    header("Location: ../public/6-liste-rendezvous.php?error=missing-value");
    exit();
}

/**
 * ETAPE 4: Recupero e Sanitizzazione
 * Convertiamo l'ID in un intero per sicurezza extra
 */
$id_rdv = (int)$_POST["id_appointment"];

/**
 * ETAPE 5: Esecuzione Sicura (Prepared Statements)
 * Protezione contro SQL Injection per la cancellazione
 */
require_once "../utils/db_connect.php";

try {
    // La query DELETE corretta  database
    $sql = "DELETE FROM appointments WHERE id = :id";
    
    $stmt = $db->prepare($sql);
    $stmt->execute([
        ':id' => $id_rdv
    ]);

    // Se l'eliminazione ha successo, torniamo alla lista con un messaggio
    header("Location: ../public/6-liste-rendezvous.php?status=deleted");
    exit();

} catch (PDOException $e) {
    // Gestione errore database (es. vincoli di integrità)
    header("Location: ../public/6-liste-rendezvous.php?error=db-error");
    exit();
}