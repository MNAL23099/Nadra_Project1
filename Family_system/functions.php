<?php
// FILE: functions.php
function logAction($conn, $action, $description) {
    $sql = "INSERT INTO history_log (action, description) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $action, $description);
    $stmt->execute();
}

// Add other utility functions here as needed
?>