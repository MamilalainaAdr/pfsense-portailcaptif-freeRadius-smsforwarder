<?php
//logcheck-sms_log check-updating dbs
// Paramètres de connexion à la base de données
$host = 'ip_radius';
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

// Chemin vers le répertoire contenant les fichiers logs
$logDir = "/var/log/radacct/ip/";
$smsLogFile = "/usr/local/www/sms_log.txt"; //le chemin vers sms_log.txt

// Ouvre le répertoire et récupère tous les fichiers qui commencent par 'detail-'
$files = glob($logDir . 'detail-*');

// Vérifie s'il y a des fichiers dans le répertoire
if (empty($files)) {
    die("Aucun fichier log trouvé dans $logDir.");
}

foreach ($files as $logfile) {
    echo "Traitement du fichier : $logfile\n";

    // Ouvre chaque fichier en mode lecture
    $fp = fopen($logfile, 'r');
    
    if (!$fp) {
        echo "Impossible d'ouvrir le fichier : $logfile\n";
        continue; // Passe au fichier suivant s'il y a une erreur d'ouverture
    }

    $userName = null;

    // Parcourt le fichier ligne par ligne
    while ($line = fgets($fp)) {
        // Vérifie s'il y a un "User-Name"
        if (strpos($line, 'User-Name =') !== false) {
            preg_match('/User-Name = "(.*?)"/', $line, $matches);
            if (isset($matches[1])) {
                $userName = $matches[1]; // Stocke le User-Name
            }
        }

        // Vérifie si Acct-Status-Type est "Start" ou "Stop"
        if (strpos($line, 'Acct-Status-Type = Start') !== false) {
            // On ne fait rien pour "Start", on attend "Stop"
            continue;
        }

        if (strpos($line, 'Acct-Status-Type = Stop') !== false) {
            // Vérifie si le User-Name est encore dans la base de données
            if ($userName && userExists($pdo, $userName)) {
                // Récupération du temps de session total pour l'utilisateur
                $totalSessionTime = getTotalSessionTime($pdo, $userName);
                
                // Récupération de la valeur de Session-Timeout
                $sessionTimeout = getSessionTimeout($pdo, $userName);

                // Comparaison et suppression
                if ($totalSessionTime >= $sessionTimeout) {
                    deleteUser($pdo, $userName, $smsLogFile);
                }
            }

            // Réinitialise le User-Name après traitement
            $userName = null;
        }
    }

    // Ferme le fichier après lecture
    fclose($fp);
}

// Fonction pour vérifier si l'utilisateur existe dans radcheck ou radreply
function userExists($pdo, $userName) {
    // Vérifie dans radcheck
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM radcheck WHERE username = :username");
    $stmt->bindParam(':username', $userName);
    $stmt->execute();
    $count = $stmt->fetchColumn();

    // Si l'utilisateur n'est pas dans radcheck, vérifie radreply
    if ($count == 0) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM radreply WHERE username = :username");
        $stmt->bindParam(':username', $userName);
        $stmt->execute();
        $count = $stmt->fetchColumn();
    }

    return $count > 0;
}

// Fonction pour récupérer le temps total de session d'un utilisateur
function getTotalSessionTime($pdo, $userName) {
    $stmt = $pdo->prepare("SELECT SUM(acctsessiontime) AS total_time FROM radacct WHERE username = :username");
    $stmt->bindParam(':username', $userName);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return (int)($result['total_time'] ?? 0);
}

// Fonction pour récupérer la valeur de Session-Timeout
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
