<?php

require "address_record.php";
$temporaryRecords = fetchTemporaryRecords();
$permanentRecords = fetchPermanentRecords();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Address Records</title>
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
  </style>
</head>
<body>
  <h2>Temporary Address Records</h2>
  <table class="table table-bordered text-center table-striped">
    <thead class="table-dark">
      <tr>
        <th>CNIC Number</th>
        <th>House No</th>
        <th>Street</th>
        <th>District</th>
        <th>Province</th>
        <th>City</th>
        <th>Country</th>
        <th>Postal Code</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($temporaryRecords as $row): ?>
        <tr>
          <td><?= htmlspecialchars($row['CNIC_Number']) ?></td>
          <td><?= htmlspecialchars($row['House_No']) ?></td>
          <td><?= htmlspecialchars($row['Street']) ?></td>
          <td><?= htmlspecialchars($row['District']) ?></td>
          <td><?= htmlspecialchars($row['Province']) ?></td>
          <td><?= htmlspecialchars($row['City']) ?></td>
          <td><?= htmlspecialchars($row['Country']) ?></td>
          <td><?= htmlspecialchars($row['Postal_Code']) ?></td>
          <td><button class="btn btn-primary btn-sm" onclick='showUpdateForm(<?= json_encode($row) ?>)'>Update</button></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <h2>Permanent Address Records</h2>
  <table class="table table-bordered text-center table-striped">
    <thead class="table-dark">
      <tr>
        <th>CNIC Number</th>
        <th>House No</th>
        <th>Street</th>
        <th>District</th>
        <th>Province</th>
        <th>City</th>
        <th>Country</th>
        <th>Postal Code</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($permanentRecords as $row): ?>
        <tr>
          <td><?= htmlspecialchars($row['CNIC_Number']) ?></td>
          <td><?= htmlspecialchars($row['House_No']) ?></td>
          <td><?= htmlspecialchars($row['Street']) ?></td>
          <td><?= htmlspecialchars($row['District']) ?></td>
          <td><?= htmlspecialchars($row['Province']) ?></td>
          <td><?= htmlspecialchars($row['City']) ?></td>
          <td><?= htmlspecialchars($row['Country']) ?></td>
          <td><?= htmlspecialchars($row['Postal_Code']) ?></td>
          <td><button class="btn btn-primary btn-sm" onclick='showPermanentForm(<?= json_encode($row) ?>)'>Update</button></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <div class="update-form" id="updateForm" style="display:none;">
    <h4>Update Temporary Address</h4>
    <form method="post" action="updated_temporary_address.php">
      <div class="mb-2"><label>CNIC</label><input type="text" name="CNIC_Number" class="form-control" id="formCNIC" readonly></div>
      <div class="mb-2"><label>House No</label><input type="text" name="Temporary_House_No" class="form-control" id="formHouseNo"></div>
      <div class="mb-2"><label>Street</label><input type="text" name="Temporary_Street" class="form-control" id="formStreet"></div>
      <div class="mb-2"><label>District</label><input type="text" name="Temporary_District" class="form-control" id="formDistrict"></div>
      <div class="mb-2"><label>Province</label><input type="text" name="Temporary_Province" class="form-control" id="formProvince"></div>
      <div class="mb-2"><label>City</label><input type="text" name="Temporary_City" class="form-control" id="formCity"></div>
      <div class="mb-2"><label>Country</label><input type="text" name="Temporary_Country" class="form-control" id="formCountry"></div>
      <div class="mb-2"><label>Postal Code</label><input type="text" name="Temporary_Postal_Code" class="form-control" id="formPostal"></div>
      <button type="submit" class="btn btn-success">Accept Changes</button>
      <button type="button" class="btn btn-secondary" onclick="hideUpdateForm()">Cancel</button>
    </form>
  </div>

  <div class="update-form" id="permanentForm" style="display:none;">
    <h4>Update Permanent Address</h4>
      <form method="post" action="updated_permanent_address.php">
      <div class="mb-2"><label>CNIC</label><input type="text" name="CNIC_Number" class="form-control" id="permCNIC" readonly></div>
      <div class="mb-2"><label>House No</label><input type="text" name="Permanent_House_No" class="form-control" id="permHouseNo"></div>
      <div class="mb-2"><label>Street</label><input type="text" name="Permanent_Street" class="form-control" id="permStreet"></div>
      <div class="mb-2"><label>District</label><input type="text" name="Permanent_District" class="form-control" id="permDistrict"></div>
      <div class="mb-2"><label>Province</label><input type="text" name="Permanent_Province" class="form-control" id="permProvince"></div>
      <div class="mb-2"><label>City</label><input type="text" name="Permanent_City" class="form-control" id="permCity"></div>
      <div class="mb-2"><label>Country</label><input type="text" name="Permanent_Country" class="form-control" id="permCountry"></div>
      <div class="mb-2"><label>Postal Code</label><input type="text" name="Permanent_Postal_Code" class="form-control" id="permPostal"></div>
      <button type="submit" class="btn btn-success">Accept Changes</button>
      <button type="button" class="btn btn-secondary" onclick="hidePermanentForm()">Close</button>
    </form>
  </div>

  <script>
    function showUpdateForm(data) {
      document.getElementById('updateForm').style.display = 'block';
      document.getElementById('formCNIC').value = data.CNIC_Number;
      document.getElementById('formHouseNo').value = data.House_No;
      document.getElementById('formStreet').value = data.Street;
      document.getElementById('formDistrict').value = data.District;
      document.getElementById('formProvince').value = data.Province;
      document.getElementById('formCity').value = data.City;
      document.getElementById('formCountry').value = data.Country;
      document.getElementById('formPostal').value = data.Postal_Code;
    }
    function hideUpdateForm() {
      document.getElementById('updateForm').style.display = 'none';
    }
    function showPermanentForm(data) {
      document.getElementById('updateForm').style.display = 'none';
      document.getElementById('permanentForm').style.display = 'block';
      document.getElementById('permCNIC').value = data.CNIC_Number;
      document.getElementById('permHouseNo').value = data.House_No;
      document.getElementById('permStreet').value = data.Street;
      document.getElementById('permDistrict').value = data.District;
      document.getElementById('permProvince').value = data.Province;
      document.getElementById('permCity').value = data.City;
      document.getElementById('permCountry').value = data.Country;
      document.getElementById('permPostal').value = data.Postal_Code;
    }
    function hidePermanentForm() {
      document.getElementById('permanentForm').style.display = 'none';
    }
  </script>
</body>
</html>
