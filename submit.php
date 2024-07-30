<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "database1";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$ip_address = $_SERVER['REMOTE_ADDR'];
$limit = 2; // Set the limit for submissions per IP address

// Check if the IP address already exists
$sql = "SELECT submission_count FROM ip_submissions WHERE ip_address = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $ip_address);
$stmt->execute();
$stmt->bind_result($submission_count);
$stmt->fetch();
$stmt->close();

if ($submission_count >= $limit) {
    echo "You have reached the limit of submissions.";
} else {
    if ($submission_count === null) {
        // IP address does not exist, insert new record
        $sql = "INSERT INTO ip_submissions (ip_address, submission_count) VALUES (?, 1)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $ip_address);
        $stmt->execute();
        $stmt->close();
    } else {
        // IP address exists, update submission count
        $sql = "UPDATE ip_submissions SET submission_count = submission_count + 1 WHERE ip_address = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $ip_address);
        $stmt->execute();
        $stmt->close();
    }
    
    // Process the form data
    $name = $_POST['name'];
    // (Save the form data to the database or perform other actions as needed)
    echo "Form submitted successfully. Thank you, " . htmlspecialchars($name) . "!";
}

$conn->close();
?>
