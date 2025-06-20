
 <?php



require "../../../Others/Shared_Files/establishConnection.php";

function fetchTemporaryRecords() {
    $connection = establishConnection(); // assumes this function returns a valid mysqli connection

    $records = [];
    $query = "SELECT * FROM TemporaryAddress  ";
    $result = mysqli_query($connection, $query);

    if (!$result) {
        die("Query failed: " . mysqli_error($connection));
    }

    while ($row = mysqli_fetch_assoc($result)) {
        $records[] = $row;
    }

    return $records;
}

function fetchPermanentRecords() {
    $connection = establishConnection(); // assumes this function returns a valid mysqli connection

    $records = [];
    $query = "SELECT * FROM PermanentAddress ";
    $result = mysqli_query($connection, $query);

    if (!$result) {
        die("Query failed: " . mysqli_error($connection));
    }

    while ($row = mysqli_fetch_assoc($result)) {
        $records[] = $row;
    }

    return $records;
}

?>



 
