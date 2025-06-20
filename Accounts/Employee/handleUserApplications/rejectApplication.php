<?php

require "../../../Others/Shared_Files/establishConnection.php";
$connection = establishConnection();

if($_SERVER["REQUEST_METHOD"] == "POST"){
    rejectApplication($_POST["applicationID"], $_POST["applicationType"]);
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

function rejectApplication($applicationID, $applicationType){
    global $connection;

    if($applicationType == "update_death"){
        $query_delete_Temporary_Death = "DELETE FROM Temporary_Death WHERE Application_ID = '$applicationID'";
        mysqli_query($connection, $query_delete_Temporary_Death);
        
        setApplicationStatus($applicationID, "Done");
        setApplicationRemarks($applicationID, "Rejected");

        header("Location: handleApplications.php");
    }
    if($applicationType == "update_person_info"){
        $query_delete_Temporary_Death = "DELETE FROM Temporary_Person WHERE Application_ID = '$applicationID'";
        mysqli_query($connection, $query_delete_Temporary_Death);
        
        setApplicationStatus($applicationID, "Done");
        setApplicationRemarks($applicationID, "Rejected");

        header("Location: handleApplications.php");
    }
    if($applicationType == "updated_permanent_address"){
        $query_delete_Temporary_Death = "DELETE FROM t_Permeanent_Address WHERE Application_ID = '$applicationID'";
        mysqli_query($connection, $query_delete_Temporary_Death);
        
        setApplicationStatus($applicationID, "Done");
        setApplicationRemarks($applicationID, "Rejected");

        header("Location: handleApplications.php");

    }
    if($applicationType == "updated_temporary_address"){
        $query_delete_Temporary_Death = "DELETE FROM t_Temporary_Address WHERE Application_ID = '$applicationID'";
        mysqli_query($connection, $query_delete_Temporary_Death);
        
        setApplicationStatus($applicationID, "Done");
        setApplicationRemarks($applicationID, "Rejected");

        header("Location: handleApplications.php");

    }
    if($applicationType == "new_cnic_card" || $applicationID == "new_bayform"){
        $query_delete_Temporary_Cnic_Card = "DELETE FROM Temporary_Cnic_Card WHERE Application_ID = '$applicationID'";
        mysqli_query($connection, $query_delete_Temporary_Cnic_Card);
        
        setApplicationStatus($applicationID, "Done");
        setApplicationRemarks($applicationID, "Rejected");

        header("Location: handleApplications.php");

    }
}


?>