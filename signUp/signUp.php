<?php
require "../Others/Shared_Files/establishConnection.php";

$connection = establishConnection();

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(dataValidation()){
        handleRequest();
    }
}

function dataValidation(){ //This function makes sure that $_POST has all the required variables
    if (isset($_POST["input_email"]) && isset($_POST["input_name"]) && isset($_POST["input_password"]) && isset($_POST["input_type"])){
        return true;
    }
    if($_POST["input_type"] == "User"){
       if(isset($_POST["input_cnic"])){
            return true;
        }else return false;
    }

    return false; //return false if nothing else is true
}

function userAlreadyExists($newEmail){ //This function returns true if the new user email already exists, otherwise, returns false
    global $connection;
    $query_select_Users = "SELECT Email FROM Users";
    $rawData = mysqli_query($connection, $query_select_Users);

    while($data = mysqli_fetch_assoc($rawData)){
        if($newEmail == $data["Email"]){
            return true;
        }
    }

    return false;
}


function addUserToTable($userName, $userEmail, $userPassword, $userType){ //This function adds user to the Users table
    global $connection;

    $query_insert_Users = "INSERT INTO Users(Name, Email, ID, Type, Password)
    VALUES('$userName', '$userEmail', NULL, '$userType', '$userPassword')";

    mysqli_query($connection, $query_insert_Users);

    echo "Account has been registered!". " <br>". PHP_EOL;
}

function handleRequest(){

    if($_POST["input_type"] == "Admin" || $_POST["input_type"] == "Employee"){
        if(!userAlreadyExists($_POST["input_email"])){
            $userEmail = $_POST["input_email"];
            $userName = $_POST["input_name"];
            $userPassword = $_POST["input_password"];
            $userType = $_POST["input_type"];

            addUserToTable($userName, $userEmail, $userPassword, $userType); //Add user to table
           
        }else echo "User account already exists!"." <br>". PHP_EOL;
    }
    if($_POST["input_type"] == "User"){ //if the account is user, we also need to handle the cnic

        $userEmail = $_POST["input_email"];
        $userName = $_POST["input_name"];
        $userPassword = $_POST["input_password"];
        $userType = $_POST["input_type"];

       if(userAlreadyExists($userEmail) == false){
            if(CNICAlreadyExists_Umar($_POST["input_cnic"]) == true){ //CNIC should exist inside Person Table
                if(CNICExistsInisdeUsersTable($_POST["input_cnic"]) == false){ //CNIC should not be registered as account already
                    addUserToUsers($userName, $userEmail, $userPassword, $_POST["input_cnic"]);
                }else echo "The CNIC is already registered in another account!". " <br>". PHP_EOL;
            }else echo "The CNIC is not registered inside NADRA database!". " <br>". PHP_EOL;
        }else echo "Account email already registered!". " <br>". PHP_EOL;
    }

}

function CNICExistsInisdeUsersTable($userCNIC){

    global $connection;

    $query_select_Users = "SELECT COUNT(*) AS NoOfCNICs FROM Users WHERE CNIC_No = '$userCNIC'";
    $rawData = mysqli_query($connection, $query_select_Users);
    $data = mysqli_fetch_assoc($rawData);

    if($data["NoOfCNICs"] == 0){
        return false;
    }else return true;

}

function addUserToUsers($userName, $userEmail, $userPassword, $userCNIC){

    global $connection;

    $query_insert_Users = "INSERT INTO Users(Name, Email, ID, Type, Password, CNIC_No)
    VALUES('$userName', '$userEmail', NULL, 'User', '$userPassword', '$userCNIC')";

    mysqli_query($connection, $query_insert_Users);

    echo "Account has been registered!". " <br>". PHP_EOL;

}

?>