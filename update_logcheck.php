<?php
// Paramètres de connexion à la base de données
$host = '192.168.56.1';
$dbname = 'radius';
$user = 'radius';
$password = 'radpass';

try {
    // Connexion à la base de données MySQL
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données: " . $e->getMessage());
}

// Chemin vers le fichier sms_log.txt
$smsLogFile = "/usr/local/www/sms_log.txt"; // le chemin vers sms_log.txt

// Récupère tous les utilisateurs dans radcheck
$stmt = $pdo->query("SELECT username FROM radcheck");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Vérifie si des utilisateurs sont trouvés
if (empty($users)) {
    die("Aucun utilisateur trouvé dans radcheck.");
}

foreach ($users as $user) {
    $userName = $user['username'];

    // Vérifie si l'utilisateur a des entrées dans radacct
    $totalSessionTime = getTotalSessionTime($pdo, $userName);

    // Récupère la valeur de Session-Timeout pour l'utilisateur dans radreply
    $sessionTimeout = getSessionTimeout($pdo, $userName);

    // Si la somme des temps de session est supérieure ou égale au Session-Timeout
    if ($totalSessionTime >= $sessionTimeout) {
        // Supprimer l'utilisateur de radcheck, radreply et sms_log.txt
        deleteUser($pdo, $userName, $smsLogFile);
    }
}

// Fonction pour récupérer le temps total de session d'un utilisateur depuis radacct
function getTotalSessionTime($pdo, $userName) {
    $stmt = $pdo->prepare("SELECT SUM(acctsessiontime) AS total_time FROM radacct WHERE username = :username");
    $stmt->bindParam(':username', $userName);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return (int)($result['total_time'] ?? 0);
}

// Fonction pour récupérer la valeur de Session-Timeout pour un utilisateur dans radreply
function getSessionTimeout($pdo, $userName) {
    $stmt = $pdo->prepare("SELECT value FROM radreply WHERE username = :username AND attribute = 'Session-Timeout'");
    $stmt->bindParam(':username', $userName);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return (int)($result['value'] ?? 0);
}

// Fonction pour supprimer l'utilisateur de radcheck, radreply et sms_log.txt
function deleteUser($pdo, $userName, $smsLogFile) {
    try {
        // Supprimer l'utilisateur de radcheck
        $stmt = $pdo->prepare("DELETE FROM radcheck WHERE username = :username");
        $stmt->bindParam(':username', $userName);
        $stmt->execute();

        // Supprimer l'utilisateur de radreply
        $stmt = $pdo->prepare("DELETE FROM radreply WHERE username = :username");
        $stmt->bindParam(':username', $userName);
        $stmt->execute();

        // Supprimer la ligne correspondante du sms_log.txt
        deleteFromSmsLog($smsLogFile, $userName);

        echo "Utilisateur $userName supprimé de radcheck, radreply et sms_log.txt.\n";
    } catch (PDOException $e) {
        echo "Erreur lors de la suppression de l'utilisateur $userName : " . $e->getMessage();
    }
}

// Fonction pour supprimer la ligne correspondante dans sms_log.txt
function deleteFromSmsLog($smsLogFile, $userName) {
    // Lire le contenu du fichier
    $lines = file($smsLogFile, FILE_IGNORE_NEW_LINES);
    $updatedLines = [];

    // Parcourir les lignes pour ne garder que celles qui ne contiennent pas le User-Name
    foreach ($lines as $line) {
        if (strpos($line, $userName) === false) {
            $updatedLines[] = $line;
        }
    }

    // Écrire les lignes mises à jour dans le fichier
    file_put_contents($smsLogFile, implode(PHP_EOL, $updatedLines));
}
?>
