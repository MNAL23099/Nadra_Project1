<?php
require "../../../Others/Shared_Files/establishConnection.php"; // Include your database connection file

// Establish database connection
$conn = establishConnection();

// Attach function to check CNIC existence
function CNICAlreadyExists($targetCNIC) {
    global $conn;
    $query = "SELECT 1 FROM Person WHERE CNIC_Number = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $targetCNIC);
    $stmt->execute();
    $stmt->store_result();
    $exists = $stmt->num_rows > 0;
    $stmt->close();
    return $exists;
}

// Get form data safely using POST
$cnicNumber = $_POST['CNIC_Number'];
$fullName = $_POST['Full_Name'];
$dateOfDeath = $_POST['Date_Of_Death'];

// Check if CNIC exists in Person table
if (!CNICAlreadyExists($cnicNumber)) {
    echo "Error: CNIC not found in Person records. Please register the person first.";
    $conn->close();
    exit();
}

// Handle file upload
$targetDir = "../Uploaded_Files/Death_Certificates/";
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
}

$fileName = basename($_FILES["Death_Certificate"]["name"]);
$targetFilePath = $targetDir . $fileName;
$fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

$allowedTypes = array('pdf', 'jpg', 'jpeg', 'png');

if (in_array(strtolower($fileType), $allowedTypes)) {
    if (move_uploaded_file($_FILES["Death_Certificate"]["tmp_name"], $targetFilePath)) {

        // Insert into Death table
        $query = "INSERT INTO death (CNIC_Number, Full_Name, Date_Of_Death, Death_Certificate_Path)
                  VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssss", $cnicNumber, $fullName, $dateOfDeath, $targetFilePath);

        if ($stmt->execute()) {
            echo "Death Registration Successful!";
        } else {
            echo "Error inserting data: " . $stmt->error;
        }
        $stmt->close();

    } else {
        echo "Sorry, file upload failed.";
    }
} else {
    echo "Sorry, only PDF, JPG, JPEG & PNG files are allowed.";
}

$conn->close();
?>
