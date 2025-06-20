<?php
require "../../../Others/Shared_Files/establishConnection.php";
$connection = establishConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $groomCNIC = $_POST['groomCNIC'];
    $brideCNIC = $_POST['brideCNIC'];
    $marriageDate = $_POST['marriageDate'];

    $query = "INSERT INTO Application_History (CNIC_Number, Application_Type, Submission_Date, Status)
              VALUES ('$groomCNIC', 'Marriage', '$marriageDate', 'Pending')";

    if (mysqli_query($connection, $query)) {
        echo "Marriage Registered Successfully";
    } else {
        echo "Error: " . mysqli_error($connection);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $groomCNIC = $_POST['groomCNIC'];
    $brideCNIC = $_POST['brideCNIC'];
    $marriageDate = $_POST['marriageDate'];

    $groomCheck = mysqli_query($connection, "SELECT * FROM Person WHERE CNIC_Number='$groomCNIC'");
    $brideCheck = mysqli_query($connection, "SELECT * FROM Person WHERE CNIC_Number='$brideCNIC'");

    if (mysqli_num_rows($groomCheck) == 0 || mysqli_num_rows($brideCheck) == 0) {
        echo "Error: Groom or Bride CNIC not found!";
        exit();
    }

    $query = "INSERT INTO Application_History (CNIC_Number, Application_Type, Submission_Date, Status)
              VALUES ('$groomCNIC', 'Marriage', '$marriageDate', 'Pending')";

    if (mysqli_query($connection, $query)) {
        echo "Marriage Registered Successfully";
    } else {
        echo "Error: " . mysqli_error($connection);
    }
}
?>