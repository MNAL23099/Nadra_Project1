<?php
require "../../../Others/Shared_Files/establishConnection.php";
function getPersonData() {
    $conn = establishConnection();
    $results = [];

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["search_by"]) && isset($_POST["search_value"])) {
        $field = $_POST["search_by"];
        $value = mysqli_real_escape_string($conn, $_POST["search_value"]);

        if (!empty($field) && !empty($value)) {
            if ($field == "Full_Name") {
                $sql = "SELECT * FROM Person WHERE Full_Name LIKE '%$value%'";
            } else {
                $sql = "SELECT * FROM Person WHERE $field = '$value'";
            }

            $query = mysqli_query($conn, $sql);

            while ($row = mysqli_fetch_assoc($query)) {
                $results[] = $row;
            }
        }
    }

    return $results;
}

$persons = getPersonData();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Person</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #e9f5ec;
        }
        .card {
            background-color: #ffffff;
            border: 1px solid #bcd0c7;
            box-shadow: 0 0 12px rgba(24, 76, 42, 0.1);
        }
        h3 {
            color: #184c2a;
        }
        .btn-primary {
            background-color: #184c2a;
            border-color: #184c2a;
        }
        .btn-primary:hover {
            background-color: #145c33;
            border-color: #145c33;
        }
        .table-dark {
            background-color: #184c2a;
            color: white;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f1f9f4;
        }
        .alert-warning {
            background-color: #fff3cd;
            color: #856404;
        }

            /* Navbar Styling */
    .navbar-brand {
      display: flex;
      align-items: center;
    }

    .navbar-brand img {
      height: 65px;
      margin-right: 15px;
    }

    .navbar-text-title {
      display: flex;
      flex-direction: column;
      justify-content: center;
      color: white;
      line-height: 1.2;
    }

    .navbar-text-title span:first-child {
      font-size: 1.5rem;
      font-weight: bold;
    }

    .navbar-text-title span:last-child {
      font-size: 0.9rem;
    }
    </style>
</head>
<body>
  
   <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #104d2f; padding-top: 0.7rem; padding-bottom: 0.7rem;">
  <div class="container-fluid">
    <a class="navbar-brand" href="../../../nadra.html">
      <img src="../../../Data/Data_Signup/OIP.webp" alt="NADRA Logo">
      <div class="navbar-text-title">
        <span>NADRA</span>
        <span>National Database & Registration Authority</span>
      </div>
    </a>
  </div>
</nav>
    <div class="container py-5">
        <div class="card p-4">
            <h3 class="text-center mb-4"><i class="bi bi-search"></i> Search Person</h3>

            <form method="post">
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Search By</label>
                        <select name="search_by" class="form-select" required>
                            <option value="">Choose field</option>
                            <option value="CNIC_Number">CNIC Number</option>
                            <option value="Full_Name">Full Name</option>
                            <option value="Father_CNIC">Father CNIC</option>
                            <option value="Mother_CNIC">Mother CNIC</option>
                            <option value="Date_Of_Birth">Date of Birth</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Search Value</label>
                        <input type="text" name="search_value" class="form-control" placeholder="Enter value..." required>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Search</button>
                    </div>
                </div>
            </form>

            <?php if (!empty($persons)): ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped text-center">
                        <thead class="table-dark">
                            <tr>
                                <th>CNIC</th>
                                <th>Name</th>
                                <th>Father CNIC</th>
                                <th>Mother CNIC</th>
                                <th>DOB</th>
                                <th>Nationality</th>
                                <th>Status</th>
                                <th>Religion</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($persons as $p): ?>
                            <tr>
                                <td><?= $p['CNIC_Number'] ?></td>
                                <td><?= $p['Full_Name'] ?></td>
                                <td><?= $p['Father_CNIC'] ?></td>
                                <td><?= $p['Mother_CNIC'] ?></td>
                                <td><?= $p['Date_Of_Birth'] ?></td>
                                <td><?= $p['Nationality'] ?></td>
                                <td><?= $p['Marital_Status'] ?></td>
                                <td><?= $p['Religion'] ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php elseif ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
                <div class="alert alert-warning text-center mt-3">No record found.</div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
