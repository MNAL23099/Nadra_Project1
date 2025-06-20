<?php

use Dom\Mysql;

require "../../../Others/Shared_Files/establishConnection.php";
echo "<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>";

//Display Navbar
// echo "
// <nav class='navbar navbar-expand-lg navbar-dark bg-dark px-3'>
//   <a class='navbar-brand' href='../../../nadra.html'>NADRA</a>
//   <div class='ms-auto d-flex'>
//     <a href='generateReports.html'> <button class='btn btn-outline-light' onclick='history.back()'>Back</button> </a>
//   </div>
// </nav>
// ";

echo "<h1>Reports Page</h1>"; //Display Heading

$connection = establishConnection();

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(dataValidation()){
        generateReport($_POST["startDate"], $_POST["endDate"], $_POST["requestedReport"]);
    }else echo "data validation issue!". " <br>". PHP_EOL;
}

function dataValidation(){ //Validate the Post data
    if(isset($_POST["startDate"]) && isset($_POST["endDate"]) && isset($_POST["requestedReport"])){
        return true;
    }else return false;
}

function generateReport($startDate, $endDate, $reqReport){ //This is the main function that echoes the different kinds of reports
    $result = 0;
    if($reqReport == "No. of registered CNICs"){ 
        $result = generateNoOfCNICsReport($startDate, $endDate);
        echo "The total number of new CNICs registered from '$startDate' to '$endDate' is ". $result. " <br>". PHP_EOL;
    }
    if($reqReport == "noOfBirths"){
        $result = generateNoOfBirths($startDate, $endDate);
        echo "The total number of births from '$startDate' to '$endDate' is ". $result. " <br>". PHP_EOL;
    }
    if($reqReport == "expiredCNIC"){
        $result = generateNoOfExpiredCNICs();
    }
    if($reqReport == "deathReport"){
        $result = generateDeathReport($startDate, $endDate);
    }
}

function generateNoOfCNICsReport($startDate, $endDate){ //This is the function which runs when number of cnic report is requested
    global $connection;

    $query_select_Cnic_Card = "SELECT COUNT(*) as total FROM CNIC_CARD WHERE Issue_Date BETWEEN '$startDate' AND '$endDate'";
    $rawData = mysqli_query($connection, $query_select_Cnic_Card);

    $data = mysqli_fetch_assoc($rawData);

    return $data["total"]; //return the stats of report
}

function generateNoOfBirths($startDate, $endDate){
    global $connection;

    $query_select_Person = "SELECT COUNT(*) as total FROM Person WHERE Date_Of_Birth BETWEEN '$startDate' AND '$endDate'";
    $rawData = mysqli_query($connection, $query_select_Person);

    $data = mysqli_fetch_assoc($rawData);

    return $data["total"]; //return the stats of report
}


function generateNoOfExpiredCNICs(){
    global $connection;

    $query_select_todayDate = "SELECT CURDATE() as todayDate";

    $rawData = mysqli_query($connection, $query_select_todayDate); //get today's date
    $data = mysqli_fetch_assoc($rawData);

    $todayDate = $data["todayDate"];

    $query_select_Cnic_Card = "SELECT COUNT(*) as expiredCards FROM CNIC_CARD WHERE '$todayDate' >= Expire_Date"; //select the number of cards whose expire date has passed
    $rawData = mysqli_query($connection, $query_select_Cnic_Card);
    
    $data = mysqli_fetch_assoc($rawData);

    echo "The total number of expired CNICs yet is ". $data["expiredCards"]. " <br>". PHP_EOL;

    $query_fetchDataOfExpiredCards = "SELECT Person.Full_Name as personName, Person.CNIC_Number as expiredCNIC 
    FROM Person
    JOIN CNIC_Card
    ON Person.CNIC_Number = CNIC_Card.Card_No
    WHERE '$todayDate' >= CNIC_Card.Expire_Date; 
    "; //Fetch the name and CNIC of those whose CNICs have expired

    $rawData = mysqli_query($connection, $query_fetchDataOfExpiredCards);

    while ($data1 = mysqli_fetch_assoc($rawData)){
        echo 
        "
        <table class='table table-bordered table-striped table-hover'>
        <thead class='table-dark'>
            <tr>
            <th>Name</th>
            <th>CNIC</th>
            </tr>
        </thead>
        <tbody>
            <tr>
            <td>{$data1["personName"]}</td> <td>{$data1["expiredCNIC"]}</td>
            </tr>
        </tbody>
        </table>
        "; //Display the name and CNIC card number of expired CNICs in a bootstrap table
    }

}

function generateDeathReport($startDate, $endDate){ //This function calculates and displays the death report

    global $connection;

    //First select the number of deaths within the given date range
    $query_selectCount_death = "SELECT COUNT(*) AS deathCount FROM death WHERE Date_Of_Death >= '$startDate' AND Date_Of_Death <= '$endDate'";

    $rawData = mysqli_query($connection, $query_selectCount_death);
    $data = mysqli_fetch_assoc($rawData);

    echo "Total Deaths Between '$startDate' and '$endDate' = {$data["deathCount"]}". " <br>". PHP_EOL;

    $query_selectCount_death = "SELECT CNIC_Number AS CNIC, Full_Name AS Name FROM death 
    WHERE Date_Of_Death >= '$startDate' AND Date_Of_Death <= '$endDate'";

    $rawData = mysqli_query($connection, $query_selectCount_death);

    while($data = mysqli_fetch_assoc($rawData)){
         echo 
        "
        <table class='table table-bordered table-striped table-hover'>
        <thead class='table-dark'>
            <tr>
            <th>Name</th>
            <th>CNIC</th>
            </tr>
        </thead>
        <tbody>
            <tr>
            <td>{$data["Name"]}</td> <td>{$data["CNIC"]}</td>
            </tr>
        </tbody>
        </table>
        "; //Display the name and CNIC card number of expired CNICs in a bootstrap table

    }   

}


?>