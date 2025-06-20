<?php require "../Others/Shared_Files/establishConnection.php";; include 'nav.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  
  <title>Family Dashboard</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>

  <div class="container">
    <h2>Family Dashboard</h2>
    
    <!-- Search Form -->
    <form method="GET" class="search-form">
      <input type="text" name="hof_cnic" placeholder="Enter Head of Family CNIC" 
             value="<?= $_GET['hof_cnic'] ?? '' ?>" required>
      <button type="submit">View Family</button>
    </form>

    <?php if (isset($_GET['hof_cnic'])): ?>
      <?php
      $conn = establishConnection();
      $hof_cnic = $conn->real_escape_string($_GET['hof_cnic']);
      
      // Find family where this CNIC is the head
      $familyQuery = $conn->query("SELECT fm.Family_ID 
                                  FROM family_members fm 
                                  WHERE fm.CNIC_Number = '$hof_cnic' AND fm.Relation_To_Head = 'Head'");
      
      if ($familyQuery->num_rows > 0): 
        $fid = $familyQuery->fetch_assoc()['Family_ID'];
        $hof = $conn->query("SELECT p.* FROM Person p WHERE p.CNIC_Number = '$hof_cnic'")->fetch_assoc();
        
        // Get all family members
        $membersQuery = $conn->query("SELECT p.*, fm.Relation_To_Head 
                                     FROM Person p
                                     JOIN family_members fm ON p.CNIC_Number = fm.CNIC_Number
                                     WHERE fm.Family_ID = '$fid'");
        $member_count = $membersQuery->num_rows;
      ?>
        <div class="family-summary">
          <h3>Family Summary</h3>
          <p><strong>Family ID:</strong> <?= $fid ?></p>
          <p><strong>Head of Family:</strong> <?= $hof['Full_Name'] ?> (<?= $hof_cnic ?>)</p>
          <p><strong>Total Members:</strong> <?= $member_count ?></p>
        </div>

        <div class="family-members">
          <h3>Family Members</h3>
          <?php if ($member_count > 0): ?>
            <table>
              <tr>
                <th>Name</th>
                <th>CNIC</th>
                <th>Relationship</th>
                <th>Date of Birth</th>
              </tr>
              <?php while ($member = $membersQuery->fetch_assoc()): ?>
                <tr>
                  <td><?= $member['Full_Name'] ?></td>
                  <td><?= $member['CNIC_Number'] ?></td>
                  <td><?= $member['Relation_To_Head'] ?></td>
                  <td><?= $member['Date_Of_Birth'] ?></td>
                </tr>
              <?php endwhile; ?>
            </table>
          <?php else: ?>
            <p class="message">No family members found</p>
          <?php endif; ?>
        </div>

        <div class="family-actions">
          <a href="../Others/Registration/registration.html" class="button">Add Child</a>
          <a href="family_tree.php?family_id=<?= $fid ?>" class="button">View Family Tree</a>
        </div>

      <?php else: ?>
        <p class="error">No family found with the provided Head of Family CNIC or the person is not a family head</p>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</body>
</html>