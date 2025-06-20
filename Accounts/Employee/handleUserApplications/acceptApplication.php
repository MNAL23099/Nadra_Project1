<?php

require "../../../Others/Shared_Files/establishConnection.php";
$connection = establishConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    if(dataValidation()){
        acceptApplication($_POST["applicationID"], $_POST["applicationType"]);
    }
}



function dataValidation(){
    if (isset($_POST["applicationID"]) && isset($_POST["applicationType"])){
        return true;
    }else return false;
}


function acceptApplication($applicationID, $applicationType){

    global $connection;

    if($applicationType == "update_death"){
        $query_select_Temporary_Death = "SELECT * FROM Temporary_Death WHERE Application_ID = '$applicationID'";

        $rawData = mysqli_query($connection, $query_select_Temporary_Death);
        $data = mysqli_fetch_assoc($rawData);

        $CNICNumber = $data["CNIC_Number"];
        $fullName = $data["Full_Name"];
        $dob = $data["Date_Of_Death"];
        $createdAt = $data["Created_At"];

        $query_update_Death = "UPDATE death SET CNIC_Number = '$CNICNumber', Full_Name = '$fullName', Date_Of_Death = '$dob', Created_At = '$createdAt' WHERE CNIC_Number = '$CNICNumber'";
        mysqli_query($connection, $query_update_Death);

        echo "Hello". " <br>". PHP_EOL;

        setApplicationStatus($applicationID, "Done");
        setApplicationRemarks($applicationID, "Accepted");
        deleteRowFromTemporaryTable($applicationID, "Temporary_Death");

        header("Location: handleApplications.php");

    }
    if($applicationType == "update_person_info"){
        $query_select_Temporary_Person = "SELECT * FROM Temporary_Person WHERE Application_ID = '$applicationID'";

        $rawData = mysqli_query($connection, $query_select_Temporary_Person);
        $data = mysqli_fetch_assoc($rawData);

        $CNICNumber = $data["CNIC_Number"];
        $fullName = $data["Full_Name"];
        $dob = $data["Date_Of_Death"];
        $father = $data["Father_CNIC"];
        $mother = $data["Mother_CNIC"];
        $nationality = $data["Nationality"];
        $Marital_Status = $data["Marital_Status"];
        $religion = $data["Religion"];

        $query_update_Person = "UPDATE Person SET CNIC_Number = '$CNICNumber', Full_Name = '$fullName', Father_CNIC = '$father', 
        Mother_CNIC = '$mother', Date_Of_Birth = '$dob', Nationality = '$nationality', Marital_Status = '$Marital_Status', Religion = '$religion'
        WHERE CNIC_Number = '$CNICNumber'";

        mysqli_query($connection, $query_update_Person);

        setApplicationStatus($applicationID, "Done");
        setApplicationRemarks($applicationID, "Accepted");
        deleteRowFromTemporaryTable($applicationID, "Temporary_Person");

        header("Location: handleApplications.php");

    }
    if($applicationType == "updated_permanent_address"){
        $query_select_Temporary_Address = "SELECT * FROM t_PermanentAddress WHERE Application_ID = '$applicationID'";

        $rawData = mysqli_query($connection, $query_select_Temporary_Address);
        $data = mysqli_fetch_assoc($rawData);

        $CNICNumber = $data["CNIC_Number"];
        $addressID = $data["Address_ID"];
        $houseNo = $data["House_No"];
        $street = $data["Street"];
        $district = $data["District"];
        $province = $data["Province"];
        $city = $data["City"];
        $country = $data["Country"];
        $postal_code = $data["Postal_Code"];

        $query_update_PermanentAddress = "UPDATE permanentaddress SET CNIC_Number = '$CNICNumber', Address_ID = '$addressID', House_No = '$houseNo', 
        Street = '$street', District = '$district', Province = '$province', City = '$city', Country = '$country', Postal_Code = '$postal_code'
        WHERE CNIC_Number = '$CNICNumber'";

        mysqli_query($connection, $query_update_PermanentAddress);

        setApplicationStatus($applicationID, "Done");
        setApplicationRemarks($applicationID, "Accepted");
        deleteRowFromTemporaryTable($applicationID, "t_permanentaddress");

        header("Location: handleApplications.php");
    }
    if($applicationType == "updated_temporary_address"){

        $query_select_Temporary_Address = "SELECT * FROM t_TemporaryAddress WHERE Application_ID = '$applicationID'";

        $rawData = mysqli_query($connection, $query_select_Temporary_Address);
        $data = mysqli_fetch_assoc($rawData);

        $CNICNumber = $data["CNIC_Number"];
        $addressID = $data["Address_ID"];
        $houseNo = $data["House_No"];
        $street = $data["Street"];
        $district = $data["District"];
        $province = $data["Province"];
        $city = $data["City"];
        $country = $data["Country"];
        $postal_code = $data["Postal_Code"];

        $query_update_PermanentAddress = "UPDATE temporaryaddress SET CNIC_Number = '$CNICNumber', Address_ID = '$addressID', House_No = '$houseNo', 
        Street = '$street', District = '$district', Province = '$province', City = '$city', Country = '$country', Postal_Code = '$postal_code'
        WHERE CNIC_Number = '$CNICNumber'";

        mysqli_query($connection, $query_update_PermanentAddress);

        setApplicationStatus($applicationID, "Done");
        setApplicationRemarks($applicationID, "Accepted");
        deleteRowFromTemporaryTable($applicationID, "t_temporaryaddress");

        header("Location: handleApplications.php");
    }
    if($applicationType == "new_cnic_card"){

        $query_select_Temporary_Cnic_Card = "SELECT * FROM Temporary_Cnic_Card WHERE Application_ID = '$applicationID'";

        $rawData = mysqli_query($connection, $query_select_Temporary_Cnic_Card);
        $data = mysqli_fetch_assoc($rawData);

        $CNICNumber = $data["Card_No"];
        $cardType = $data["Card_Type"];

        $query_select_todayDate = "SELECT CURDATE() AS todayDate";
        $rawData = mysqli_query($connection, $query_select_todayDate);
        $data = mysqli_fetch_assoc($rawData);
        $issueDate = $data["todayDate"];

        // Convert to DateTime and add 10 years
        $dateObj = new DateTime($issueDate);
        $dateObj->modify('+10 years');
        $expireDate = $dateObj->format('Y-m-d'); // Final date string

        $query_insert_Cnic_Card = "INSERT INTO Cnic_Card(Card_ID, Card_No, Card_Type, Issue_Date, Expire_Date)
        VALUES(NULL, $CNICNumber, 'CNIC', '$issueDate', '$expireDate')";

        mysqli_query($connection, $query_insert_Cnic_Card);

        setApplicationStatus($applicationID, "Done");
        setApplicationRemarks($applicationID, "Accepted");
        deleteRowFromTemporaryTable($applicationID, "Temporary_CNIC_Card");

        header("Location: handleApplications.php");
    }
    if($applicationType == "new_bayform"){

        $query_select_Temporary_Cnic_Card = "SELECT * FROM Temporary_Cnic_Card WHERE Application_ID = '$applicationID'";

        $rawData = mysqli_query($connection, $query_select_Temporary_Cnic_Card);
        $data = mysqli_fetch_assoc($rawData);

        $CNICNumber = $data["Card_No"];
        $cardType = $data["Card_Type"];

        $query_select_todayDate = "SELECT CURDATE() AS todayDate";
        $rawData = mysqli_query($connection, $query_select_todayDate);
        $data = mysqli_fetch_assoc($rawData);
        $issueDate = $data["todayDate"];

        $query_insert_Cnic_Card = "INSERT INTO Cnic_Card(Card_ID, Card_No, Card_Type, Issue_Date, Expire_Date)
        VALUES(NULL, $CNICNumber, 'Bayform', '$issueDate', NULL)";

        mysqli_query($connection, $query_insert_Cnic_Card);

        setApplicationStatus($applicationID, "Done");
        setApplicationRemarks($applicationID, "Accepted");
        deleteRowFromTemporaryTable($applicationID, "Temporary_CNIC_Card");

        header("Location: handleApplications.php");
    }

}

function removeRowFromApplication_History($applicationID){
    global $connection;

    $query_delete_Application_History = "DELETE FROM Application_History WHERE Application_ID = '$applicationID'";
    mysqli_query($connection, $query_delete_Application_History);
    
}

function setApplicationStatus($applicationID, $newApplicationStatus){
    global $connection;

    $query_update_Application_Status = "UPDATE Application_History SET Status = '$newApplicationStatus' WHERE Application_ID = '$applicationID'";
    mysqli_query($connection, $query_update_Application_Status);
}

function setApplicationRemarks($applicationID, $newRemarks){
    global $connection;

    $query_update_Application_Status = "UPDATE Application_History SET Remarks = '$newRemarks' WHERE Application_ID = '$applicationID'";
    mysqli_query($connection, $query_update_Application_Status);
}

function deleteRowFromTemporaryTable($applicationID, $tableName){
    global $connection;

    $query_delete_table = "DELETE FROM $tableName WHERE Application_ID = '$applicationID'";
    mysqli_query($connection, $query_delete_table);

}


?>