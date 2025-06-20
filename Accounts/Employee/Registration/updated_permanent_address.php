<?php
require "../../../Others/Shared_Files/establishConnection.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $connection = establishConnection();

    // Permanent Address
    $cnic = $_POST['CNIC_Number'];
    $phn = $_POST['Permanent_House_No'];
    $pstreet = $_POST['Permanent_Street'];
    $pdistrict = $_POST['Permanent_District'];
    $pprovince = $_POST['Permanent_Province'];
    $pcity = $_POST['Permanent_City'];
    $pcountry = $_POST['Permanent_Country'];
    $ppostal = $_POST['Permanent_Postal_Code'];
     

         $id = insertDataIntoApplication_History($cnic,'updated_permanent_address');
     $query = "
        INSERT INTO T_PermanentAddress 
        (Application_ID, CNIC_Number, House_No, Street, District, Province, City, Country, Postal_Code)
        VALUES 
        ('$id', '$cnic', '$phn', '$pstreet', '$pdistrict', '$pprovince', '$pcity', '$pcountry', '$ppostal')
        ON DUPLICATE KEY UPDATE 
            Application_ID = '$id',
            House_No = '$phn',
            Street = '$pstreet',
            District = '$pdistrict',
            Province = '$pprovince',
            City = '$pcity',
            Country = '$pcountry',
            Postal_Code = '$ppostal'
    ";

    $result = mysqli_query($connection, $query);

    if ($result === false) {
        echo "Error: " . mysqli_error($connection);
        exit();
    }

   echo "Application sent successfully.";
}



?>