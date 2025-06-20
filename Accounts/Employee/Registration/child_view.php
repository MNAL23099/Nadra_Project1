<?php

require "child_record.php";
$personRecords = fetchPersonRecords(); // Update this function in child_record.php to fetch from Person table
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Child (Person) Records</title>
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
  <h2>Child (Person) Registration Table</h2>
  <table class="table table-bordered text-center table-striped">
    <thead class="table-dark">
      <tr>
        <th>CNIC Number</th>
        <th>Full Name</th>
        <th>Father CNIC</th>
        <th>Mother CNIC</th>
        <th>Date of Birth</th>
        <th>Nationality</th>
        <th>Marital Status</th>
        <th>Religion</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($personRecords as $row): ?>
        <tr>
          <td><?= htmlspecialchars($row['CNIC_Number']) ?></td>
          <td><?= htmlspecialchars($row['Full_Name']) ?></td>
          <td><?= htmlspecialchars($row['Father_CNIC']) ?></td>
          <td><?= htmlspecialchars($row['Mother_CNIC']) ?></td>
          <td><?= htmlspecialchars($row['Date_Of_Birth']) ?></td>
          <td><?= htmlspecialchars($row['Nationality']) ?></td>
          <td><?= htmlspecialchars($row['Marital_Status']) ?></td>
          <td><?= htmlspecialchars($row['Religion']) ?></td>
          <td>
            <button class="btn btn-primary btn-sm" onclick='showUpdateForm(<?= json_encode($row) ?>)'>Update</button>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <div class="update-form" id="updateForm">
    <h4>Update Record</h4>
    <form method="post" action="update_person_info.php">
      <div class="mb-2"><label>CNIC Number</label><input type="text" name="CNIC_Number" class="form-control" id="formCNIC" readonly></div>
      <div class="mb-2"><label>Full Name</label><input type="text" name="Full_Name" class="form-control" id="formName"></div>
      <div class="mb-2"><label>Father CNIC</label><input type="text" name="Father_CNIC" class="form-control" id="formFatherCNIC"></div>
      <div class="mb-2"><label>Mother CNIC</label><input type="text" name="Mother_CNIC" class="form-control" id="formMotherCNIC"></div>
      <div class="mb-2"><label>Date of Birth</label><input type="date" name="Date_Of_Birth" class="form-control" id="formDOB"></div>
      <div class="mb-2"><label>Nationality</label><input type="text" name="Nationality" class="form-control" id="formNationality"></div>
      <div class="mb-2"><label>Marital Status</label><input type="text" name="Marital_Status" class="form-control" id="formMarital"></div>
      <div class="mb-2"><label>Religion</label><input type="text" name="Religion" class="form-control" id="formReligion"></div>
      <button type="submit" class="btn btn-success">Accept Changes</button>
    </form>
  </div>

  <script>
    function showUpdateForm(data) {
      document.getElementById('updateForm').style.display = 'block';
      document.getElementById('formCNIC').value = data.CNIC_Number;
      document.getElementById('formName').value = data.Full_Name;
      document.getElementById('formFatherCNIC').value = data.Father_CNIC;
      document.getElementById('formMotherCNIC').value = data.Mother_CNIC;
      document.getElementById('formDOB').value = data.Date_Of_Birth;
      document.getElementById('formNationality').value = data.Nationality;
      document.getElementById('formMarital').value = data.Marital_Status;
      document.getElementById('formReligion').value = data.Religion;
    }
  </script>
</body>
</html>
