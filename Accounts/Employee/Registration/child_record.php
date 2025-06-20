
 <?php



require "../../../Others/Shared_Files/establishConnection.php";

function fetchPersonRecords() {
    $connection = establishConnection(); // assumes this function returns a valid mysqli connection

    $records = [];
    $query = "SELECT * FROM Person";
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



 
