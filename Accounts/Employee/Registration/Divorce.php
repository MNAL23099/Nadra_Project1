<?php
require "../../../Others/Shared_Files/establishConnection.php";
$connection = establishConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $husbandCNIC = $_POST['Husband_CNIC'];
    $wifeCNIC = $_POST['Wife_CNIC'];
    $divorceDate = $_POST['Divorce_Date'];

    $husbandCheck = mysqli_query($connection, "SELECT * FROM Person WHERE CNIC_Number='$husbandCNIC'");
    $wifeCheck = mysqli_query($connection, "SELECT * FROM Person WHERE CNIC_Number='$wifeCNIC'");

    if (mysqli_num_rows($husbandCheck) == 0 || mysqli_num_rows($wifeCheck) == 0) {
        echo "Error: Husband or Wife CNIC not found!";
        exit();
    }

    $query = "INSERT INTO Application_History (CNIC_Number, Application_Type, Submission_Date, Status)
              VALUES ('$husbandCNIC', 'Divorce', '$divorceDate', 'Pending')";

    if (mysqli_query($connection, $query)) {
        echo "Divorce Registered Successfully";
    } else {
        echo "Error: " . mysqli_error($connection);
    }
}
?>
