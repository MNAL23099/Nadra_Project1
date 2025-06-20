<?php
require "../../../Others/Shared_Files/establishConnection.php";
$connection = establishConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $childName = $_POST['Child_Name'];
    $dob = $_POST['Date_Of_Birth'];
    $fatherCNIC = $_POST['Father_CNIC'];
    $motherCNIC = $_POST['Mother_CNIC'];
    $nationality = $_POST["Nationality"];
    $religion = $_POST["Religion"];
    $gender = $_POST["gender"];

    // Auto-assign relation
    $relation = ($gender == 'Male') ? 'Son' : (($gender == 'Female') ? 'Daughter' : 'Child');

    $maritalStatus = "Not Married"; //Marital Status is Not Married by default

    // Check both parents exist
    $fatherCheck = "SELECT * FROM Person WHERE CNIC_Number='$fatherCNIC'";
    $motherCheck = "SELECT * FROM Person WHERE CNIC_Number='$motherCNIC'";
    
    $fatherResult = mysqli_query($connection, $fatherCheck);
    $motherResult = mysqli_query($connection, $motherCheck);

    if (mysqli_num_rows($fatherResult) == 0) {
        echo "Error: Father's CNIC not found!". " <br>". PHP_EOL;
        exit();
    }
    if(mysqli_num_rows($motherResult) == 0){
        echo "Error: Mother's CNIC not found!". " <br>". PHP_EOL;
        exit();
    }

    // Generate child CNIC
    $childCNIC = generateRandomCNIC_Umar();

    insertChildInto_family_members_AND_Person($childCNIC, $childName, $dob, $fatherCNIC, $motherCNIC, $nationality, $religion, $relation);

}

function insertChildInto_family_members_AND_Person($child_cnic, $child_name, $dob, $father, $mother, $nationality, $religion, $relation){
    global $connection;
    $conn = $connection;

    $fidResult = $conn->query("SELECT Family_ID FROM family_members WHERE CNIC_Number = '$father'");
    if ($fidResult->num_rows > 0) {
        $fid = $fidResult->fetch_assoc()['Family_ID'];

        $conn->query("INSERT INTO Person (CNIC_Number, Full_Name, Date_Of_Birth, Father_CNIC, Mother_CNIC, Nationality, Religion, Marital_Status)
                      VALUES ('$child_cnic', '$child_name', '$dob', '$father', '$mother', '$nationality', '$religion', 'Single')");

        $conn->query("INSERT INTO family_members (Family_ID, CNIC_Number, Relation_To_Head) VALUES ('$fid', '$child_cnic', '$relation')");

        echo "<div class='success-message'>
                <h3>Child Registered Successfully</h3>
                <p><strong>Child CNIC:</strong> $child_cnic</p>
                <p><strong>Family ID:</strong> $fid</p>
                <p><strong>Relation:</strong> $relation</p>
              </div>";
    } else {
        echo "<div class='error-message'>Father not found in family records. Please register the father first.</div>";
    }
}

?>
