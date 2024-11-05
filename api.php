<?php
// Enable CORS if needed (remove in production)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");

// Check if it's a POST request to receive SMS
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the raw POST data
    $data = json_decode(file_get_contents('php://input'), true);

    // Check if the data is valid
    if (isset($data['from']) && isset($data['text'])) {
        $from = $data['from'];
        $text = $data['text'];
        $sentStamp = $data['sentStamp'] ?? null;
        $receivedStamp = $data['receivedStamp'] ?? null;
        $sim = $data['sim'] ?? null;

        // Log the received data
        $logEntry = "From: $from, Text: $text, Sent: $sentStamp, Received: $receivedStamp, SIM: $sim\n";
        file_put_contents('sms_log.txt', $logEntry, FILE_APPEND);
        
        // Respond to the request
        http_response_code(200); // OK
        exit; // Stop further processing
    } else {
        http_response_code(400); // Bad Request
        file_put_contents('sms_log.txt', "Invalid data received\n", FILE_APPEND);
        exit; // Stop further processing
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMS Log</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .log-entry { margin: 10px 0; padding: 10px; border: 1px solid #ccc; }
    </style>
    <script>
        // Auto-refresh every 5 seconds
        setInterval(function() {
            window.location.reload();
        }, 5000);
    </script>
</head>
<body>
    <h1>Received SMS Log</h1>
    
    <div id="sms-log">
        <?php
        // Read the sms_log.txt file and display its contents
        if (file_exists('sms_log.txt')) {
            $logFile = fopen('sms_log.txt', 'r');
            while (($line = fgets($logFile)) !== false) {
                echo "<div class='log-entry'>" . htmlspecialchars($line) . "</div>";
            }
            fclose($logFile);
        } else {
            echo "<div class='log-entry'>No logs available.</div>";
        }
        ?>
    </div>
</body>
</html>

