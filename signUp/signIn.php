<?php
 
require "../Others/Shared_Files/establishConnection.php";

$connection = establishConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (validateData()) {
    $email = $_POST["email"];
    $pass = $_POST["pass"];
  }
  $command4 = "SELECT * FROM Users";
  $table = mysqli_query($connection, $command4);
  while ($data = mysqli_fetch_assoc($table)) {
    if ($email == $data["Email"] && $pass == $data["Password"]) {

      if ($data["Type"] == "User") { //Log in if user
        registerCurrentSession($data["Email"], $data["CNIC_No"], $data["Name"], $data["Type"]);
        header("Location: ../Accounts/User/homePage/homePage.html");
      }
      if ($data["Type"] == "Admin") { //Log in if Admin
        registerCurrentSession($data["Email"], $data["CNIC_No"], $data["Name"], $data["Type"]);
        header("Location: ../Accounts/Admin/Admin_Dashboard/Adashboard.html");
      }
      if ($data["Type"] == "Employee") { //Log in if Employee
        registerCurrentSession($data["Email"], $data["CNIC_No"], $data["Name"], $data["Type"]);
        header("Location: ../Accounts/Employee/Employee_Dashboard/Edashboard.html");
      }
    }
  }
  echo "Account not found!". " <br>". PHP_EOL;
}


function validateData(){
  if (isset($_POST["email"]) && isset($_POST["pass"])){
    return true;
  } else return false;
}


function registerCurrentSession($userEmail, $userCNIC, $userName, $userType) {

  registerName($userName);
  registerEmail($userEmail);

  if($userType == "User"){
    registerCNIC($userCNIC);
  }

}


function registerCNIC($userCNIC){

  $fd = fopen("Current_Session/current_session_cnic.txt", "w");
  fwrite($fd, $userCNIC);
  fclose($fd);

}


function registerEmail($userEmail){

  $fd = fopen("Current_Session/current_session_email.txt", "w");
  fwrite($fd, $userEmail);
  fclose($fd);
}


function registerName($userName){

  $fd = fopen("Current_Session/current_session_name.txt", "w");
  fwrite($fd, $userName);
  fclose($fd);
}

?>