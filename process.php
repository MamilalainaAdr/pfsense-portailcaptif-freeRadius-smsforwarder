<?php
// Activer l'affichage des erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Connexion à la base de données FreeRADIUS
$servername = "ip_serveur";
$username = "radius";
$password = "radpass";
$dbname = "radius";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Échec de la connexion à la base de données : " . $conn->connect_error);
}

// Récupération des données du formulaire
$input_reference = $_POST['ref'] ?? ''; // Match form field name
$input_cost = $_POST['hidden_cost'] ?? 0; // Match hidden input field
$input_delay = $_POST['hidden_delay'] ?? 0; // Match hidden input field

// Log input values for debugging
error_log("Input reference: " . $input_reference);
error_log("Input cost: " . $input_cost);
error_log("Input delay: " . $input_delay);

// Validation des entrées
if (empty($input_reference) || $input_cost < 400 || $input_delay < 5) {
    error_log("Validation failed: Reference is empty or cost/delay too low");
    header('Location: https://pfsense.localdomain.com/error.php');
    exit();
}

// Appel à l'API pour récupérer les SMS loggés (reading from file)
$sms_found = false;
$lines = file('sms_log.txt');

// Log the lines being read
foreach ($lines as $line) {
    error_log("Processing line: " . $line);

    // Vérification de la présence de la référence dans le SMS
    if (preg_match('/Ref:\s*(\d+)/', $line, $ref_matches)) {
        $sms_reference = $ref_matches[1];

        if ($sms_reference == $input_reference) {
            if (preg_match('/\d+\/\d+\s+([\d\s]+) Ar/', $line, $cost_matches)) {
                $sms_cost = (int)str_replace(' ', '', $cost_matches[1]);
                error_log("Found SMS with reference: $sms_reference and cost: $sms_cost");

                // Comparaison du coût du SMS avec le coût entré
                if ($sms_cost == $input_cost) {
                    $sms_found = true;
                    break;
                }
            }
        }
    }
}

// Si aucune correspondance trouvée, rediriger vers la page d'erreur
if (!$sms_found) {
    error_log("No matching SMS found for reference: $input_reference and cost: $input_cost");
    header('Location: https://pfsense.localdomain.com/error.php');
    exit();
}

// If the SMS was found, continue with adding the user to FreeRADIUS...
$auth_user = $_POST['auth_user'] ?? $input_reference;
if (empty($auth_user)) {
    die("Erreur : Le nom d'utilisateur ne peut pas être vide.");
}

// Génération du mot de passe
$password = $input_reference;

// Insertion dans radcheck et radreply
$radcheck_query = "INSERT INTO radcheck (username, attribute, op, value) VALUES (?, 'Cleartext-Password', ':=', ?)";
$radreply_query = "INSERT INTO radreply (username, attribute, op, value) 
                   VALUES (?, 'Session-Timeout', ':=', ?), 
                          (?, 'WISPr-Bandwidth-Max-Up', ':=', ?), 
                          (?, 'WISPr-Bandwidth-Max-Down', ':=', ?), 
                          (?, 'WISPr-Redirection-URL', ':=', ?)";

// Préparer et lier les requêtes pour radcheck
$stmt = $conn->prepare($radcheck_query);
$stmt->bind_param("ss", $auth_user, $password);
if (!$stmt->execute()) {
    die("Erreur lors de l'exécution de radcheck : " . $stmt->error);
}

// Préparer et lier les requêtes pour radreply
$stmt = $conn->prepare($radreply_query);
$session_timeout = $input_delay * 60 ; // Durée de session
$max_bandwidth = 1024000; // Bande passante maximale
$redirect_url = "https://www.google.com";

$stmt->bind_param("ssssssss", $auth_user, $session_timeout, $auth_user, $max_bandwidth, $auth_user, $max_bandwidth, $auth_user, $redirect_url);
if (!$stmt->execute()) {
    die("Erreur lors de l'exécution de radreply : " . $stmt->error);
}
//Creation du fichier d'historique
/*$log = fopen('log.txt','a');
if (file_exists('log.txt')){
    fwrite($log,"Nom:".$auth_user."; Duree:".$input_delay."; Cout:".$input_cost. PHP_EOL);
    fclose($log);
}*/

// Fermer la connexion
$stmt->close();
$conn->close();

// Rediriger vers la page de succès
header('Location: https://pfsense.localdomain.com/succes.php');
exit();
?>
