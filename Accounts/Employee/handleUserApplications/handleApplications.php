<?php

require "../../../Others/Shared_Files/establishConnection.php";
echo "<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>";
echo "<script src='handleApplication.js?v=2'></script>";

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

echo "<h1 class='text-center mt-4'>Application Handler</h1>";

$connection = establishConnection();

function displayActiveApplications(){ //This function will fetch all the Active applications from the db and display them along with some buttons
    global $connection;
    $query_select_Application_History = "SELECT * FROM Application_History WHERE Status = 'Pending'";
    $rawData = mysqli_query($connection, $query_select_Application_History);

    echo
    "<table class='table table-bordered table-striped table-hover'>
        <thead class='table-dark'>
            <tr>
            <th>Application ID</th>
            <th>Application Type</th>
            <th>Applicant's CNIC</th>
            <th>Accept Application</th>
            <th>Reject Application</th>
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
            <td> <button type='button' onclick =\"acceptApplication('{$data["Application_ID"]}', '{$data["Application_Type"]}')\" class='btn btn-success'>Accept</button></td>
            <td> <button type='button' onclick =\"rejectApplication('{$data["Application_ID"]}', '{$data["Application_Type"]}')\" class='btn btn-danger'>Reject</button> </td>
            </tr>
        </tbody>
        "; 
    }

    echo "</table>";

}


displayActiveApplications();


?>