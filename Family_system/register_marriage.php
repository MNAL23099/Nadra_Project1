<?php
// FILE: register_marriage.php
require '../Others/Shared_Files/establishConnection.php';
include 'functions.php';
include 'nav.php';

// Get database connection
$conn = establishConnection();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register Marriage</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="container">
    <h2>Register Marriage</h2>
    <form method="POST" class="form-container">
      <div class="form-row">
        <div class="form-group">
          <label for="spouse1">Spouse 1 CNIC</label>
          <input type="text" id="spouse1" name="spouse1" placeholder="Enter first spouse's CNIC" required>
        </div>
        
        <div class="form-group">
          <label for="spouse2">Spouse 2 CNIC</label>
          <input type="text" id="spouse2" name="spouse2" placeholder="Enter second spouse's CNIC" required>
        </div>
      </div>
      
      <div class="form-group">
        <label for="marriage_date">Marriage Date</label>
        <input type="date" id="marriage_date" name="marriage_date" required>
      </div>
      
      <button type="submit" class="submit-btn">Register Marriage</button>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $spouse1 = $conn->real_escape_string($_POST['spouse1']);
      $spouse2 = $conn->real_escape_string($_POST['spouse2']);
      $marriage_date = $conn->real_escape_string($_POST['marriage_date']);

      if ($spouse1 === $spouse2) {
        echo "<div class='error-message'>Spouses cannot be the same person.</div>";
      } else {
        // Verify both spouses exist in Person table
        $result1 = $conn->query("SELECT Date_Of_Birth, Full_Name FROM Person WHERE CNIC_Number = '$spouse1'");
        $result2 = $conn->query("SELECT Date_Of_Birth, Full_Name FROM Person WHERE CNIC_Number = '$spouse2'");

        if ($result1->num_rows === 0 || $result2->num_rows === 0) {
          echo "<div class='error-message'>One or both CNICs not found in our records.</div>";
        } else {
          // Check age requirements
          $sp1 = $result1->fetch_assoc();
          $sp2 = $result2->fetch_assoc();
          $year = date("Y");
          $age1 = $year - intval(substr($sp1['Date_Of_Birth'], 0, 4));
          $age2 = $year - intval(substr($sp2['Date_Of_Birth'], 0, 4));

          if ($age1 < 18 || $age2 < 18) {
            echo "<div class='error-message'>Both spouses must be at least 18 years old.</div>";
          } else {
            // Check for existing active marriages
            $check1 = $conn->query("SELECT * FROM marriages WHERE (spouse1_cnic = '$spouse1' OR spouse2_cnic = '$spouse1') AND status = 'active'");
            $check2 = $conn->query("SELECT * FROM marriages WHERE (spouse1_cnic = '$spouse2' OR spouse2_cnic = '$spouse2') AND status = 'active'");

            if ($check1->num_rows > 0) {
              echo "<div class='error-message'>Spouse 1 is already married. Cannot register again.</div>";
            } elseif ($check2->num_rows > 0) {
              echo "<div class='error-message'>Spouse 2 is already married. Cannot register again.</div>";
            } else {
              // Start transaction
              $conn->autocommit(FALSE);
              
              try {
                  // 1. Insert into marriages table
                  $marriage_sql = "INSERT INTO marriages (spouse1_cnic, spouse2_cnic, marriage_date, status) 
                                  VALUES ('$spouse1', '$spouse2', '$marriage_date', 'active')";
                  if (!$conn->query($marriage_sql)) {
                      throw new Exception("Marriage registration failed: " . $conn->error);
                  }
                  $marriage_id = $conn->insert_id;
                  
                  // 2. Get new Family_ID
                  $fidResult = $conn->query("SELECT IFNULL(MAX(family_id), 0) + 1 AS NewFamilyID FROM families");
                  $fidRow = $fidResult->fetch_assoc();
                  $new_fid = $fidRow['NewFamilyID'];
                  
                  // 3. Insert into families table (HOF is spouse1)
                  $family_sql = "INSERT INTO families (hof_cnic, family_id) 
                                VALUES ('$spouse1', '$new_fid')";
                  if (!$conn->query($family_sql)) {
                      throw new Exception("Family creation failed: " . $conn->error);
                  }
                  
                  // 4. Insert into members table (spouse1 as Head)
                  $member1_sql = "INSERT INTO members (cnic, full_name, dob, relationship, family_id, status) 
                                 VALUES ('$spouse1', '{$sp1['Full_Name']}', '{$sp1['Date_Of_Birth']}', 
                                 'Head', '$new_fid', 'alive')";
                  if (!$conn->query($member1_sql)) {
                      throw new Exception("Spouse 1 registration failed: " . $conn->error);
                  }
                  
                  // 5. Insert into members table (spouse2 as Spouse)
                  $member2_sql = "INSERT INTO members (cnic, full_name, dob, relationship, family_id, status) 
                                 VALUES ('$spouse2', '{$sp2['Full_Name']}', '{$sp2['Date_Of_Birth']}', 
                                 'Spouse', '$new_fid', 'alive')";
                  if (!$conn->query($member2_sql)) {
                      throw new Exception("Spouse 2 registration failed: " . $conn->error);
                  }
                  
                  // 6. Insert into family_members table (backward compatibility)
                  $fam_member1_sql = "INSERT INTO family_members (Family_ID, CNIC_Number, Relation_To_Head) 
                                     VALUES ('$new_fid', '$spouse1', 'Head')";
                  $fam_member2_sql = "INSERT INTO family_members (Family_ID, CNIC_Number, Relation_To_Head) 
                                     VALUES ('$new_fid', '$spouse2', 'Wife')";
                  if (!$conn->query($fam_member1_sql) || !$conn->query($fam_member2_sql)) {
                      throw new Exception("Family member registration failed: " . $conn->error);
                  }
                  
                  // 7. Log action
                  logAction($conn, 'Register Marriage', "Marriage ID: $marriage_id, Family ID: $new_fid, Spouses: $spouse1 & $spouse2");
                  
                  // Commit transaction
                  $conn->commit();
                  
                  echo "<div class='success-message'>
                          <h3>Marriage Registered Successfully!</h3>
                          <p><strong>Marriage ID:</strong> $marriage_id</p>
                          <p><strong>Family ID:</strong> $new_fid</p>
                          <p><strong>Head of Family:</strong> {$sp1['Full_Name']} ($spouse1)</p>
                          <p><strong>Spouse:</strong> {$sp2['Full_Name']} ($spouse2)</p>
                          <p><strong>Marriage Date:</strong> $marriage_date</p>
                        </div>";
                        
              } catch (Exception $e) {
                  $conn->rollback();
                  echo "<div class='error-message'>Registration failed: " . $e->getMessage() . "</div>";
              }
              
              // Restore autocommit
              $conn->autocommit(TRUE);
            }
          }
        }
      }
    }
    ?>
  </div>
</body>
</html>