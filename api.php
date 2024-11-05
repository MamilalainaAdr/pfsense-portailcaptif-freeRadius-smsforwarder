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