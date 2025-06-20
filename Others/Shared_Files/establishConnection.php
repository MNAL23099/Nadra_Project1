<?php

function establishConnection(){
    $password = "";
    $hostName = "localhost";
    $userName = "root";

    // Connect to MySQL server
    $connection = mysqli_connect($hostName, $userName, $password);
    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Create database if not exists
    $database = "DBMS_Project_G4";
    $query_createDB = "CREATE DATABASE IF NOT EXISTS {$database}";
    mysqli_query($connection, $query_createDB);

    // Connect to the created database
    $connection = mysqli_connect($hostName, $userName, $password, $database);
    if (!$connection) {
        die("Database connection failed: " . mysqli_connect_error());
    }

    // Call all table creation functions
    createTable_Users($connection);
    createTable_Person($connection);
    createTable_Address($connection);
    createTable_Addresst($connection);
    createTable_tAddress($connection);
    createTable_temporary_tAddresst($connection);
    createCNIC($connection);
    createBiometric($connection);
    createFamily($connection);
    createFamilyMember($connection);
    createTable_marriages($connection);
    createTable_history_log($connection);
    createApplicationHistory($connection);
    createDeathTable($connection);  // renamed function to match others
    person_family_link($connection);// family link with person donot change it please!
    createTable_Temporary_Person($connection);
    createTable_Temporary_Death($connection);
    createTable_FamilyMembers($connection);
    createTable_Temporary_CNIC_Card($connection);

    return $connection;
}


function createTable_Users($connection){
    $query = "CREATE TABLE IF NOT EXISTS Users (
        ID INT AUTO_INCREMENT PRIMARY KEY,
        Name VARCHAR(100),
        Email VARCHAR(100),
        Type VARCHAR(100),
        Password VARCHAR(100),
        CNIC_No VARCHAR(13)
    )";
    mysqli_query($connection, $query);
}


function createTable_Person($connection){
    $query = "CREATE TABLE IF NOT EXISTS Person (
        CNIC_Number VARCHAR(15) PRIMARY KEY,
        Full_Name VARCHAR(100),
        Father_CNIC VARCHAR(100),
        Mother_CNIC VARCHAR(100),
        Date_Of_Birth DATE,
        Nationality VARCHAR(50),
        Marital_Status VARCHAR(20),
        Religion VARCHAR(50)
    )";
    mysqli_query($connection, $query);
}


function createTable_Address($connection){
    $query = "CREATE TABLE IF NOT EXISTS PermanentAddress (
        Address_ID INT AUTO_INCREMENT PRIMARY KEY,
        CNIC_Number VARCHAR(15) UNIQUE,
        House_No VARCHAR(50),
        Street VARCHAR(50),
        District VARCHAR(50),
        Province VARCHAR(50),
        City VARCHAR(50),
        Country VARCHAR(50),
        Postal_Code VARCHAR(20),
        FOREIGN KEY (CNIC_Number) REFERENCES Person(CNIC_Number)
    )";
    mysqli_query($connection, $query);
}
function createTable_tAddress($connection){
    $query = "CREATE TABLE IF NOT EXISTS T_PermanentAddress (
      
        Application_ID INT,
        CNIC_Number VARCHAR(15) UNIQUE,
        House_No VARCHAR(50),
        Street VARCHAR(50),
        District VARCHAR(50),
        Province VARCHAR(50),
        City VARCHAR(50),
        Country VARCHAR(50),
        Postal_Code VARCHAR(20),
        FOREIGN KEY (CNIC_Number) REFERENCES Person(CNIC_Number)
    )";
    mysqli_query($connection, $query);
}


function createTable_Addresst($connection){
    $query = "CREATE TABLE IF NOT EXISTS TemporaryAddress (
        Address_ID INT AUTO_INCREMENT PRIMARY KEY,
        CNIC_Number VARCHAR(15) UNIQUE,
        House_No VARCHAR(50),
        Street VARCHAR(50),
        District VARCHAR(50),
        Province VARCHAR(50),
        City VARCHAR(50),
        Country VARCHAR(50),
        Postal_Code VARCHAR(20),
        FOREIGN KEY (CNIC_Number) REFERENCES Person(CNIC_Number)
    )";
    mysqli_query($connection, $query);
}
function createTable_temporary_tAddresst($connection){
    $query = "CREATE TABLE IF NOT EXISTS T_TemporaryAddress (
   
        Application_ID INT,
        CNIC_Number VARCHAR(15) UNIQUE,
        House_No VARCHAR(50),
        Street VARCHAR(50),
        District VARCHAR(50),
        Province VARCHAR(50),
        City VARCHAR(50),
        Country VARCHAR(50),
        Postal_Code VARCHAR(20),
        FOREIGN KEY (CNIC_Number) REFERENCES Person(CNIC_Number)
    )";
    mysqli_query($connection, $query);
}

function createCNIC($connection){
    $query = "CREATE TABLE IF NOT EXISTS CNIC_Card (
        Card_ID INT AUTO_INCREMENT PRIMARY KEY,
        Card_No VARCHAR(15),
        Card_Type VARCHAR(50),
        Issue_Date DATE,
        Expire_Date DATE,
        FOREIGN KEY (Card_No) REFERENCES Person(CNIC_Number)
    )";
    mysqli_query($connection, $query);
}


function createBiometric($connection){
    $query = "CREATE TABLE IF NOT EXISTS Biometric (
        Biometric_ID INT AUTO_INCREMENT PRIMARY KEY,
        CNIC_Number VARCHAR(15),
        Fingerprint VARCHAR(200),
        FOREIGN KEY (CNIC_Number) REFERENCES Person(CNIC_Number)
    )";
    mysqli_query($connection, $query);
}


function createFamily($connection) {
    $query = "CREATE TABLE IF NOT EXISTS families (
        id INT AUTO_INCREMENT PRIMARY KEY,
        hof_cnic VARCHAR(13) NOT NULL,
        family_id INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (hof_cnic) REFERENCES Person(CNIC_Number),
        UNIQUE KEY (family_id)
    )";
    mysqli_query($connection, $query);

}


function createTable_marriages($connection){
    $query = "CREATE TABLE IF NOT EXISTS marriages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    spouse1_cnic VARCHAR(13) NOT NULL,
    spouse2_cnic VARCHAR(13) NOT NULL,
    marriage_date DATE,
    status ENUM('active', 'divorced', 'widowed') DEFAULT 'active',
    FOREIGN KEY (spouse1_cnic) REFERENCES members(cnic) ON DELETE CASCADE,
    FOREIGN KEY (spouse2_cnic) REFERENCES members(cnic) ON DELETE CASCADE)";
    mysqli_query($connection, $query);
}


function createTable_history_log($connection){
    $query = "CREATE TABLE IF NOT EXISTS history_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    action VARCHAR(50),
    description TEXT,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP)";
    mysqli_query($connection, $query);

}
function createTable_FamilyMembers($connection) {
    $query = "CREATE TABLE IF NOT EXISTS family_members (
        Family_ID INT,
        CNIC_Number VARCHAR(15),
        Relation_To_Head VARCHAR(50),
        PRIMARY KEY (Family_ID, CNIC_Number),
        FOREIGN KEY (CNIC_Number) REFERENCES Person(CNIC_Number)
            ON DELETE CASCADE ON UPDATE CASCADE
    )";
    mysqli_query($connection, $query);
}



function createFamilyMember($connection){
    // $query = "CREATE TABLE IF NOT EXISTS Family_Member (
    //     Family_ID INT,
    //     CNIC_Number VARCHAR(15),
    //     Parent_ID INT,
    //     Relationship_Type VARCHAR(50),
    //     FOREIGN KEY (Family_ID) REFERENCES Family(Family_ID),
    //     FOREIGN KEY (CNIC_Number) REFERENCES Person(CNIC_Number)
    // )";
    // mysqli_query($connection, $query);

    $query = "CREATE TABLE IF NOT EXISTS members (
    cnic VARCHAR(13) PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    dob DATE NOT NULL,
    gender ENUM('Male', 'Female') NOT NULL,
    relationship ENUM('Head', 'Spouse', 'Child', 'Sibling', 'Parent') NOT NULL,
    family_id INT DEFAULT NULL,
    status ENUM('alive', 'deceased') NOT NULL DEFAULT 'alive')";
    mysqli_query($connection, $query);

}


function createApplicationHistory($connection){
    $query = "CREATE TABLE IF NOT EXISTS Application_History (
        Application_ID INT AUTO_INCREMENT PRIMARY KEY,
        CNIC_Number VARCHAR(15),
        Application_Type VARCHAR(50),
        Submission_Date DATE,
        Status VARCHAR(20),
        Remarks VARCHAR(255),
        FOREIGN KEY (CNIC_Number) REFERENCES Person(CNIC_Number)
    )";
    mysqli_query($connection, $query);
}


// Corrected Death table function name (keep naming consistent)
function createDeathTable($connection){
    $query = "CREATE TABLE IF NOT EXISTS Death (
        ID INT AUTO_INCREMENT PRIMARY KEY,
        CNIC_Number VARCHAR(20) NOT NULL,
        Full_Name VARCHAR(100) NOT NULL,
        Date_Of_Death DATE NOT NULL,
        Death_Certificate_Path VARCHAR(255) NOT NULL,
        Created_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    mysqli_query($connection, $query);
}


function createTable_Temporary_Person($connection){ //This table stores data of Person Temporararily until Employee accepts the data into actual Person
    //Table or rejects the user application, deleting the data from this table too and not entering it into actual Person table
    $query = "CREATE TABLE IF NOT EXISTS Temporary_Person(
        Application_ID INT PRIMARY KEY,
        CNIC_Number VARCHAR(15),
        Full_Name VARCHAR(100),
        Father_CNIC VARCHAR(100),
        Mother_CNIC VARCHAR(100),
        Date_Of_Birth DATE,
        Nationality VARCHAR(50),
        Marital_Status VARCHAR(20),
        Religion VARCHAR(50),
        House_No VARCHAR(50),
        Street VARCHAR(50),
        District VARCHAR(50),
        Province VARCHAR(50),
        City VARCHAR(50),
        Country VARCHAR(50),
        Postal_Code VARCHAR(20),
        Temp_House_No VARCHAR(50),
        Temp_Street VARCHAR(50),
        Temp_District VARCHAR(50),
        Temp_Province VARCHAR(50),
        Temp_City VARCHAR(50),
        Temp_Country VARCHAR(50),
        Temp_Postal_Code VARCHAR(20)
    )";

    mysqli_query($connection, $query);
}



function createTable_Temporary_Death($connection){ //Create the temporary death table which stores deah info before employee accepts the application

    $query = "CREATE TABLE IF NOT EXISTS Temporary_Death (
        Application_ID INT AUTO_INCREMENT PRIMARY KEY,
        CNIC_Number VARCHAR(20) NOT NULL,
        Full_Name VARCHAR(100) NOT NULL,
        Date_Of_Death DATE NOT NULL,
        Death_Certificate_Path VARCHAR(255) NOT NULL,
        Created_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    mysqli_query($connection, $query);


}

function createTable_Temporary_CNIC_Card($connection){
    $query = "CREATE TABLE IF NOT EXISTS Temporary_CNIC_Card (
            Application_ID INT PRIMARY KEY,
            Card_No VARCHAR(15),
            Card_Type VARCHAR(50),
            FOREIGN KEY (Card_No) REFERENCES Person(CNIC_Number)
        )";
        mysqli_query($connection, $query);

}


function generateCNIC($length) {
    $min = pow(10, $length - 1); // e.g. 1000 for length 4
    $max = pow(10, $length) - 1; // e.g. 9999 for length 4
    return rand($min, $max);
}


function generateRandomCNIC_Umar(){
    global $connection;

    //Make sure that the random CNIC is truly unique, and doesn't match any existing CNIC inside the Person table
    $duplicateCNICExists = true;
    $randomCNIC = generateCNIC(13);
    $query_select_Person = "SELECT COUNT(*) as alreadyExistingCNIC FROM Person WHERE CNIC_Number = '$randomCNIC'";
    $rawData = mysqli_query($connection, $query_select_Person);
    $data = mysqli_fetch_assoc($rawData);

    if($data["alreadyExistingCNIC"] == 0){
            $duplicateCNICExists = false;
        }

    while ($duplicateCNICExists == true) {
        $query_select_Person = "SELECT COUNT(*) as alreadyExistingCNIC FROM Person WHERE CNIC_Number = '$randomCNIC'";
        $rawData = mysqli_query($connection, $query_select_Person);
        $data = mysqli_fetch_assoc($rawData);

        if($data["alreadyExistingCNIC"] >= 1){
            $randomCNIC = generateCNIC(13);
        }else $duplicateCNICExists = false;
    }
    //CNIC uniqueness verification ends here

    return $randomCNIC;
}


function CNICAlreadyExists_Umar($targetCNIC){ //True = CNIC already exists, False = CNIC doesn't exist

    global $connection;
    $query_select_Person = "SELECT CNIC_Number FROM Person";
    $rawData = mysqli_query($connection, $query_select_Person);

    while ($data = mysqli_fetch_assoc($rawData)){
        if($targetCNIC == $data["CNIC_Number"]){
            return true;
        }
    }

    return false;

}


function insertIntoTemporary_Person($applicationID, $cnic, $name, $father, $mother, $dob, $nationality, $maritalStatus, $religion, $perm_houseNo, $perm_street, $perm_district, $perm_province, $perm_city, $perm_country, $perm_Postal, $temp_houseNo, $temp_street, $temp_district, $temp_province, $temp_city, $temp_country, $temp_Postal){ //Insert the received data inside the Temporary_Person
    global $connection;

    $query_insert_Temporary_Person = "INSERT INTO Temporary_Person(Application_ID, CNIC_Number, Full_Name, Father_CNIC, Mother_CNIC, Date_Of_Birth, 
    Nationality, Marital_Status, Religion, House_No, Street, District, Province, City, Country, Postal_Code, Temp_House_No, Temp_Street,
    Temp_District, Temp_Province, Temp_City, Temp_Country, Temp_Postal_Code)
    VALUES('$applicationID', '$cnic', '$name', '$father', '$mother', '$dob', '$nationality', '$maritalStatus', '$religion', '$perm_houseNo', '$perm_street',
    '$perm_district', '$perm_province', '$perm_city', '$perm_country', '$perm_Postal', '$temp_houseNo', '$temp_street', '$temp_district', 
    '$temp_province', '$temp_city', '$temp_country', '$temp_Postal')";

    mysqli_query($connection, $query_insert_Temporary_Person); //Insert the data into the table

}


function insertDataIntoApplication_History($CNIC, $applicationType){ //This function inserts the data into Application_History and returns Application ID
    global $connection;

    $query_todayDate = "SELECT CURDATE() as todayDate";
    $rawData = mysqli_query($connection, $query_todayDate);
    $data = mysqli_fetch_assoc($rawData);
    $todayDate = $data["todayDate"];

    $query_insert_Application_History = "INSERT INTO Application_History(Application_ID, CNIC_Number, Application_Type, Submission_Date, Status, Remarks)
    VALUES(NULL, $CNIC, '$applicationType', '$todayDate', 'Pending', 'None')";

    mysqli_query($connection, $query_insert_Application_History);

    $query_select_Application_History = "SELECT MAX(Application_ID) AS currentID FROM Application_History";
    $rawData = mysqli_query($connection, $query_select_Application_History);
    $data = mysqli_fetch_assoc($rawData);

    return $data["currentID"];
}


function addChildToFamily($childCNIC, $childName, $dob, $gender, $family_id){
    global $connection;

    $relationship = 'Child';
    $status = 'alive';
    
    $query = "INSERT INTO members (cnic, full_name, dob, gender, relationship, family_id, status) 
              VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $connection->prepare($query);
    $stmt->bind_param("sssssis", $childCNIC, $childName, $dob, $gender, $relationship, $family_id, $status);

    if($stmt->execute()){
        echo "Child added to family successfully!<br>";
    } else {
        echo "Error: " . $stmt->error . "<br>";
    }

    $stmt->close();
}


function getFamilyIdByParentCNIC($parentCNIC){
    global $connection;

    $query = "SELECT family_id FROM members WHERE cnic=?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("s", $parentCNIC);
    $stmt->execute();
    $result = $stmt->get_result();

    if($row = $result->fetch_assoc()){
        return $row['family_id'];
    } else {
        return null;
    }

    $stmt->close();
}


function person_family_link($connection){
$query="CREATE TABLE IF NOT EXISTS Person_Family_Link (
    Link_ID INT AUTO_INCREMENT PRIMARY KEY,
    CNIC_Number VARCHAR(15),
    family_id INT,
    FOREIGN KEY (CNIC_Number) REFERENCES Person(CNIC_Number),
    FOREIGN KEY (family_id) REFERENCES families(id))";

    mysqli_query($connection, $query);
    
}
?>
