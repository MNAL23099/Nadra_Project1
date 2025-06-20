<?php

require "../../../Others/Shared_Files/establishConnection.php";
echo "<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>";
$connection = establishConnection();

//Display Navbar
echo '
<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #104d2f; padding-top: 0.7rem; padding-bottom: 0.7rem;">
  <div class="container-fluid">
    <a class="navbar-brand" href="../../../nadra.html" style="display: flex; align-items: center;">
      <img src="../../../Data/Data_Signup/OIP.webp" alt="NADRA Logo" style="height: 60px; margin-right: 15px;">
      <div class="navbar-text-title text-white" style="display: flex; flex-direction: column; line-height: 1.2;">
        <span style="font-size: 1.5rem; font-weight: bold;">NADRA</span>
        <span style="font-size: 0.9rem;">National Database & Registration Authority</span>
      </div>
    </a>
  </div>
</nav>
';

function getUserCNIC(){
    $fd = fopen("../../../signUp/Current_Session/current_session_cnic.txt", "r");
    $userCNIC = fgets($fd);
    return $userCNIC;
}

function showApplications(){
    global $connection;

    $userCNIC = getUserCNIC();

    $query_select_Application_History = "SELECT * FROM Application_History WHERE CNIC_Number = '$userCNIC'";
    $rawData = mysqli_query($connection, $query_select_Application_History);

    echo "These are your applications!". " <br>". PHP_EOL;

    echo
    "<table class='table table-bordered table-striped table-hover'>
        <thead class='table-dark'>
            <tr>
            <th>Application ID</th>
            <th>Application Type</th>
            <th>Applicant's CNIC</th>
            <th>Submission Date</th>
            <th>Status</th>
            <th>Remarks</th>
            </tr>
        </thead>
    ";

    while ($data = mysqli_fetch_assoc($rawData)){
        echo 
        "
        <tbody>
            <tr>
            <td>{$data["Application_ID"]}</td>
            <td>{$data["Application_Type"]}</td>
            <td>{$data["CNIC_Number"]}</td>
            <td>{$data["Submission_Date"]}</td>
            <td>{$data["Status"]}</td>
            <td>{$data["Remarks"]}</td>
            </tr>
        </tbody>
        "; 
    }

    echo "</table>";
}

showApplications();



?>