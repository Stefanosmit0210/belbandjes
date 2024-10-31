<?php
// Establish connection to the database
require_once 'conn.php';

// Function to fetch log messages from the database
function fetchLogMessages(PDO $conn)
{
    $logMessages = [];

    try {
        // SQL query to retrieve log messages
        $query = "SELECT id, logging_level_name AS message FROM log_levels";
        $stmt = $conn->query($query);

        // Fetch and store log messages in an associative array (an associative array is an array that uses keys to access values)
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $logMessages[$row['id']] = $row['message'];
        }
    } catch (PDOException $e) {
        // Handle any errors that occur during fetching
        echo "Error fetching log messages: " . $e->getMessage();
    }

    // Return the log messages
    return $logMessages;
}

try {
    // Get the visitor's IP address and current datetime
    $visitor_ip = $_SERVER['REMOTE_ADDR'];
    $datetime = date('Y-m-d H:i:s');

    // Fetch log messages from the database
    $log_messages = fetchLogMessages($conn);

    if (isset($_POST['action'])) {
        $log_level = $_POST['action'];

        if (isset($log_messages[$log_level])) {
            $log_message = $log_messages[$log_level];

            // Prepare and execute an SQL query to insert the log entry
            $sql = "INSERT INTO logs (log_ip, log_timestamp, log_level, log_message) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$visitor_ip, $datetime, $log_level, $log_message]);
        } else {
            // Handle the case where the specified log level doesn't exist
        }
    } else {
        // Handle the case where 'action' is not set in the POST request
    }
} catch (PDOException $e) {
    // Handle any database-related errors
    echo "Error: " . $e->getMessage();
}
