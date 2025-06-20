<?php
require "../../../Others/Shared_Files/establishConnection.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $connection = establishConnection();

    // Permanent Address
    $cnic = $_POST['CNIC_Number'];
 

    $thn = $_POST['Temporary_House_No'];
    $tstreet = $_POST['Temporary_Street'];
    $tdistrict = $_POST['Temporary_District'] ;
    $tprovince = $_POST['Temporary_Province'] ;
    $tcity = $_POST['Temporary_City'] ;
    $tcountry = $_POST['Temporary_Country'] ;
    $tpostal = $_POST['Temporary_Postal_Code'] ;
   
    
         $id = insertDataIntoApplication_History($cnic,'updated_temporary_address');
  
    $query = "
        INSERT INTO T_TemporaryAddress 
        (Application_ID, CNIC_Number, House_No, Street, District, Province, City, Country, Postal_Code)
        VALUES 
        ('$id', '$cnic', '$thn', '$tstreet', '$tdistrict', '$tprovince', '$tcity', '$tcountry', '$tpostal')
        ON DUPLICATE KEY UPDATE 
            Application_ID = '$id',
            House_No = '$thn',
            Street = '$tstreet',
            District = '$tdistrict',
            Province = '$tprovince',
            City = '$tcity',
            Country = '$tcountry',
            Postal_Code = '$tpostal'
    ";

    $result = mysqli_query($connection, $query);

    if ($result === false) {
        echo "Error: " . mysqli_error($connection);
        exit();
    }

   
   echo "Application sent successfully.";
}


?>