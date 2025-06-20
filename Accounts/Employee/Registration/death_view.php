<?php
require "death_records.php";
$deathRecords = fetchDeathRecords();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Death Records</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
         background-color: #f5f5f5; 
         padding: 2rem; 
         font-family: Arial,  sans-serif; 
        }
    table {
         background-color: white;
         }
    h2 {
         color: #184c2a; 
         margin-bottom: 1.5rem;
         }
    .update-form { 
        display: none;
        margin-top: 2rem;
        background: #fff; 
        padding: 1rem; 
        border-radius: 8px; 
        box-shadow: 0 0 10px rgba(0,0,0,0.1); 
    }
  </style>
</head>
<body>
  <h2>Death Table</h2>
  <table class="table table-bordered text-center table-striped">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>CNIC</th>
        <th>Name</th>
        <th>Date</th>
        <th>Certificate</th>
        <th>Created At</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($deathRecords as $row): ?>
        <tr>
          <td><?= $row['ID'] ?></td>
          <td><?= $row['CNIC_Number'] ?></td>
          <td><?= $row['Full_Name'] ?></td>
          <td><?= $row['Date_Of_Death'] ?></td>
          <td><a href="<?= $row['Death_Certificate_Path'] ?>" target="_blank">View Certificate</a></td>
          <td><?= $row['Created_At'] ?></td>
          <td>
            <button class="btn btn-primary btn-sm" onclick='showUpdateForm(<?= json_encode($row) ?>)'>Update</button>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <div class="update-form" id="updateForm">
    <h4>Update Record</h4>
    <form method="post" action="update_death.php">
      <input type="hidden" name="ID" id="formID">
      <div class="mb-2"><label>CNIC</label><input type="text" name="CNIC_Number" class="form-control" id="formCNIC" readonly></div>
      <div class="mb-2"><label>Full Name</label><input type="text" name="Full_Name" class="form-control" id="formName"></div>
      <div class="mb-2"><label>Date of Death</label><input type="date" name="Date_Of_Death" class="form-control" id="formDate"></div>
      <div class="mb-2"><label>Certificate Path</label><input type="text" name="Death_Certificate_Path" class="form-control" id="formCert"></div>
      <button type="submit" class="btn btn-success">Accept Changes</button>
    </form>
  </div>

  <script>
    function showUpdateForm(data) {
      document.getElementById('updateForm').style.display = 'block';
      document.getElementById('formID').value = data.ID;
      document.getElementById('formCNIC').value = data.CNIC_Number;
      document.getElementById('formName').value = data.Full_Name;
      document.getElementById('formDate').value = data.Date_Of_Death;
      document.getElementById('formCert').value = data.Death_Certificate_Path;
    }
  </script>
</body>
</html>
