<?php
require "../../../Others/Shared_Files/establishConnection.php";

// Connect to DB
$conn = establishConnection();

// Get form values
$cnic = $_POST['CNIC'];

// Permanent Address
$phn = $_POST['Permanent_House_No'];
$pstreet = $_POST['Permanent_Street'];
$pdistrict = $_POST['Permanent_District'];
$pprovince = $_POST['Permanent_Province'];
$pcity = $_POST['Permanent_City'];
$pcountry = $_POST['Permanent_Country'];
$ppostal = $_POST['Permanent_Postal_Code'];

// Insert Permanent Address
$check = mysqli_query($conn, "SELECT CNIC_Number FROM PermanentAddress WHERE CNIC_Number = '$cnic'");
if (mysqli_num_rows($check) == 0) {
    $permanentSQL = "INSERT INTO PermanentAddress 
    (CNIC_Number, House_No, Street, District, Province, City, Country, Postal_Code)
    VALUES ('$cnic', '$phn', '$pstreet', '$pdistrict', '$pprovince', '$pcity', '$pcountry', '$ppostal')";
    mysqli_query($conn, $permanentSQL);
} else {
    echo "Permanent address already exists for CNIC $cnic. Skipping insert.<br>";
}

// Temporary Address
$thn = $_POST['Temporary_House_No'];
$tstreet = $_POST['Temporary_Street'];
$tdistrict = $_POST['Temporary_District'];
$tprovince = $_POST['Temporary_Province'];
$tcity = $_POST['Temporary_City'];
$tcountry = $_POST['Temporary_Country'];
$tpostal = $_POST['Temporary_Postal_Code'];

$check = mysqli_query($conn, "SELECT CNIC_Number FROM TemporaryAddress WHERE CNIC_Number = '$cnic'");
if (mysqli_num_rows($check) == 0) {
    $permanentSQL = "INSERT INTO TemporaryAddress 
    (CNIC_Number, House_No, Street, District, Province, City, Country, Postal_Code)
    VALUES ('$cnic', '$phn', '$pstreet', '$pdistrict', '$pprovince', '$pcity', '$pcountry', '$ppostal')";
    mysqli_query($conn, $permanentSQL);
} else {
    echo "Temporaray address already exists for CNIC $cnic. Skipping insert.<br>";
}

// Application History
$appSQL = "INSERT INTO Application_History (CNIC_Number, Application_Type, Submission_Date, Status, Remarks)
VALUES ('$cnic', 'CNIC Application', CURDATE(), 'Pending', '')";
mysqli_query($conn, $appSQL);

echo "Application submitted successfully!";
$conn->close();
?>


