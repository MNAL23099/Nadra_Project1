<?php
require "../../../Others/Shared_Files/establishConnection.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $connection = establishConnection();

    $cnic = $_POST['CNIC_Number'];
    $fullName = $_POST['Full_Name'];
    $dateOfDeath = $_POST['Date_Of_Death'];
    $certPath = $_POST['Death_Certificate_Path'];
      $value=CNICAlreadyExists_Umar($cnic);
     if($value == false){
        echo "cnic not present"."<br>";
        exit();
    }
    $id = insertDataIntoApplication_History($cnic,'update_death');
  
   
    $query = "INSERT INTO Temporary_Death (Application_ID, CNIC_Number, Full_Name, Date_Of_Death, Death_Certificate_Path)
              VALUES ('$id', '$cnic', '$fullName', '$dateOfDeath', '$certPath')";

    $result = mysqli_query($connection, $query);

    if ($result) {
       
     echo "Application sent successfully."; 
    } else {
        echo " Error: " . mysqli_error($connection);
    }
}
?>
