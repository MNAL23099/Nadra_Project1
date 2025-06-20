<?php

use Dom\Mysql;

require "../../../Others/Shared_Files/establishConnection.php";

$connection = establishConnection();

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(dataValidation()){ //Make sure that post data exists and also that CNIC exists inside Person table
        if(CNICAlreadyExists_Umar($_POST["CNIC"])){
            handleRequest();
        }else echo "CNIC not found in records!". " <br>". PHP_EOL;

    }else echo "Data validation issue in regestering application from user!". " <br>". PHP_EOL;
}


function dataValidation(){ //Check that POST has all the required data
    if (isset($_POST["CNIC"]) && isset($_POST["typeOfCard"])){
        return true;
    }else return false;
    
}

function userIsEighteenYearsOld($userDob){
    global $connection;

    $query_select_todayDate = "SELECT CURDATE() AS todayDate";
    $rawData = mysqli_query($connection, $query_select_todayDate);

    $data = mysqli_fetch_assoc($rawData);
    $todayDate = $data["todayDate"];

    $userDateOfBirth = new DateTime($userDob);
    $today = new DateTime($todayDate);

    $userAge = $userDateOfBirth->diff($today)->y;

    if($userAge >= 18){
        return true;
    }else return false;

}

function handleRequest(){

    if($_POST["typeOfCard"] == "CNIC"){

        $userDOB = getDateOfBirth($_POST["CNIC"]);

        if(userIsEighteenYearsOld($userDOB)){
            if(cardAlreadyExists($_POST["CNIC"]) == false ){
                sendRequestForCNICCard($_POST["CNIC"], "CNIC");
            }
            if(cardAlreadyExists($_POST["CNIC"]) == true ){
                if(cnicCardIsExpired($_POST["CNIC"]) == true){
                    sendRequestForCNICCard($_POST["CNIC"], "CNIC");
                }else echo "Your already existing CNIC Card is not expired yet!". " <br>". PHP_EOL;
            }
        }else echo "Applicant is not 18 years old!". " <br>". PHP_EOL;
    }
    if($_POST["typeOfCard"] == "BayForm"){

        if(bayFormAlreadyExists($_POST["CNIC"]) == false){
            sendRequestForBayForm($_POST["CNIC"]);
        }else echo "Applicant already has a bayform!". " <br>". PHP_EOL;
    }


}

function getDateOfBirth($userCNIC){

    global $connection;

    $query_select_Person = "SELECT Date_Of_Birth FROM Person WHERE CNIC_Number = '$userCNIC'";
    $rawData = mysqli_query($connection, $query_select_Person);
    $data = mysqli_fetch_assoc($rawData);
    return $data["Date_Of_Birth"];
}

function cardAlreadyExists($userCNIC){

    global $connection;

    $query_select_Cnic_Card = "SELECT COUNT(*) as noOfCards FROM Cnic_Card WHERE Card_No = '$userCNIC' AND Card_Type = 'CNIC'";
    $rawData = mysqli_query($connection, $query_select_Cnic_Card);
    $data = mysqli_fetch_assoc($rawData);

    if ($data["noOfCards"] > 0){
        return true;
    }
    if ($data["noOfCards"] == 0){
        return false;
    }

}

function cnicCardIsExpired($userCNIC){

    global $connection;
    $query_select_Cnic_Card = "SELECT CURDATE() AS todayDate";
    $rawData = mysqli_query($connection, $query_select_Cnic_Card);
    $data = mysqli_fetch_assoc($rawData);
    $todayDate = $data["todayDate"];

    $query_select_Cnic_Card = "SELECT COUNT(*) AS noOfExpiredCards FROM Cnic_Card WHERE Card_No = '$userCNIC' AND Card_Type = 'CNIC' AND Expire_Date <= '$todayDate'"; //The value will be 1 if card is expired and 0 if card is not expired
    $rawData = mysqli_query($connection, $query_select_Cnic_Card);
    $data = mysqli_fetch_assoc($rawData);

    if ($data["noOfExpiredCards"] == 0){
        return false;
    }else return true;

}

function sendRequestForCNICCard($userCNIC){

    global $connection;

    $applicationID = insertDataIntoApplication_History($_POST["CNIC"], "new_cnic_card");

    $query_insert_temporary_Cnic_Card = "INSERT INTO Temporary_CNIC_Card(Application_ID, Card_No, Card_Type)
    VALUES('$applicationID', '$userCNIC', 'CNIC')";
    mysqli_query($connection, $query_insert_temporary_Cnic_Card);

    echo "An application for CNIC card has been sent!". " <br>". PHP_EOL;

}

function bayFormAlreadyExists($userCNIC){

    global $connection;

    $query_select_Cnic_Card = "SELECT COUNT(*) AS noOfBayForms FROM CNIC_Card WHERE Card_No = '$userCNIC' AND Card_Type = 'Bayform'";
    $rawData = mysqli_query($connection, $query_select_Cnic_Card);
    $data = mysqli_fetch_assoc($rawData);

    if($data["noOfBayForms"] == 0){
        return false;
    }else return true;
}

function sendRequestForBayForm($userCNIC){

    global $connection;

    $applicationID = insertDataIntoApplication_History($userCNIC, "new_bayform");

    $query_insert_temporary_Cnic_Card = "INSERT INTO Temporary_CNIC_Card(Application_ID, Card_No, Card_Type)
    VALUES('$applicationID', '$userCNIC', 'Bayform')";
    mysqli_query($connection, $query_insert_temporary_Cnic_Card);

    echo "An application for Bayform has been sent!". " <br>". PHP_EOL;

}






?>