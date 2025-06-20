<?php
require "../../../Others/Shared_Files/establishConnection.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $connection = establishConnection();

    $cnic = $_POST['CNIC_Number'];
    $fullName = $_POST['Full_Name'];
    $fatherCnic = $_POST['Father_CNIC'];
    $motherCnic = $_POST['Mother_CNIC'];
    $dob = $_POST['Date_Of_Birth'];
    $nationality = $_POST['Nationality'];
    $maritalStatus = $_POST['Marital_Status'];
    $religion = $_POST['Religion'];
    
    $value = CNICAlreadyExists_Umar($cnic);
    if ($value == false) {
        echo "cnic not present<br>";
        exit();
    }
    $id = insertDataIntoApplication_History($cnic, 'update_person_info');

    // Use the correct function to insert into Temporary_Person
    $result = insertIntoTemporary_Person($id, $cnic, $fullName, $fatherCnic, $motherCnic, $dob, $nationality, $maritalStatus, $religion, 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none');

    if ($result === false) {
        echo "Error: " . mysqli_error($connection);
        exit();
    }

    echo "Application sent successfully.";
}
?>