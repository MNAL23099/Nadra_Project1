<?php 
require "../Others/Shared_Files/establishConnection.php";
include 'nav.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>View Data</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="container">
    <h2>All Members</h2>
    <table>
      <tr>
        <th>CNIC</th>
        <th>Name</th>
        <th>DOB</th>
        <th>Gender</th>
        <th>Relationship</th>
        <th>Family ID</th>
        <th>Status</th>
      </tr>
      <?php
      $conn = establishConnection();
      $result = $conn->query("SELECT * FROM Person");
      while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['CNIC_Number']}</td>
                <td>{$row['Full_Name']}</td>
                <td>{$row['Date_Of_Birth']}</td>
                <td>".($row['Gender'] ?? 'N/A')."</td>
                <td>".getRelation($conn, $row['CNIC_Number'])."</td>
                <td>".getFamilyID($conn, $row['CNIC_Number'])."</td>
                <td>{$row['Marital_Status']}</td>
              </tr>";
      }

      // Helper functions to get relationship and family ID
      function getRelation($conn, $cnic) {
        $result = $conn->query("SELECT Relation_To_Head FROM family_members WHERE CNIC_Number = '$cnic' LIMIT 1");
        return $result->num_rows > 0 ? $result->fetch_assoc()['Relation_To_Head'] : 'N/A';
      }

      function getFamilyID($conn, $cnic) {
        $result = $conn->query("SELECT Family_ID FROM family_members WHERE CNIC_Number = '$cnic' LIMIT 1");
        return $result->num_rows > 0 ? $result->fetch_assoc()['Family_ID'] : 'N/A';
      }
      ?>
    </table>

    <h2>All Marriages</h2>
    <table>
      <tr>
        <th>Spouse 1 CNIC</th>
        <th>Spouse 2 CNIC</th>
        <th>Marriage Date</th>
        <th>Status</th>
      </tr>
      <?php
      $result = $conn->query("SELECT * FROM marriages");
      while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['spouse1_cnic']}</td>
                <td>{$row['spouse2_cnic']}</td>
                <td>{$row['marriage_date']}</td>
                <td>{$row['status']}</td>
              </tr>";
      }
      ?>
    </table>

    <h2>History Log</h2>
    <table>
      <tr>
        <th>ID</th>
        <th>Action</th>
        <th>Description</th>
        <th>Timestamp</th>
      </tr>
      <?php
      $result = $conn->query("SELECT * FROM history_log ORDER BY timestamp DESC");
      while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['action']}</td>
                <td>{$row['description']}</td>
                <td>{$row['timestamp']}</td>
              </tr>";
      }
      ?>
    </table>
  </div>
</body>
</html>