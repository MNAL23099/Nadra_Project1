<?php 
require "../Others/Shared_Files/establishConnection.php"; 
include 'nav.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Family Tree Viewer</title>
  <link rel="stylesheet" href="styles.css">
  <script src="https://unpkg.com/vis-network@9.1.2/standalone/umd/vis-network.min.js"></script>
</head>
<body>
  <div class="container">
    <h2>Family Tree Viewer</h2>
    <form method="GET">
      <input type="text" name="family_id" placeholder="Enter Family ID" required>
      <button type="submit">View Tree</button>
    </form>
    
    <div id="tree" style="width:100%; height:600px;"></div>
    
    <?php
    $conn = establishConnection();

    if (isset($_GET['family_id'])) {
        $fid = $conn->real_escape_string($_GET['family_id']);

        // Modified query to use Relation_To_Head instead of Gender
        $query = "SELECT 
                    p.CNIC_Number, 
                    p.Full_Name, 
                    p.Father_CNIC, 
                    p.Mother_CNIC,
                    fm.Relation_To_Head
                  FROM Person p
                  JOIN family_members fm ON p.CNIC_Number = fm.CNIC_Number
                  WHERE fm.Family_ID = '$fid'";
        $result = $conn->query($query);

        $persons = [];
        while ($row = $result->fetch_assoc()) {
            $persons[$row['CNIC_Number']] = $row;
        }

        $nodes = [];
        $edges = [];

        // Create nodes with relation-based styling
        foreach ($persons as $cnic => $data) {
            $relation = strtolower($data['Relation_To_Head'] ?? '');
            
            // Determine color based on relationship
            $color = '#D3D3D3'; // Default color
            if (strpos($relation, 'husband') !== false || strpos($relation, 'son') !== false) {
                $color = '#97C2FC'; // Blue for males
            } elseif (strpos($relation, 'wife') !== false || strpos($relation, 'daughter') !== false) {
                $color = '#FFB6C1'; // Pink for females
            }
            
            $nodes[] = [
                'id' => $cnic,
                'label' => $data['Full_Name'] . "\n(" . $data['Relation_To_Head'] . ")",
                'color' => $color,
                'shape' => 'box',
                'font' => ['size' => 14]
            ];
        }

        // Create family relationships
        foreach ($persons as $cnic => $data) {
            if (!empty($data['Father_CNIC']) && isset($persons[$data['Father_CNIC']])) {
                $edges[] = [
                    'from' => $data['Father_CNIC'], 
                    'to' => $cnic,
                    'label' => 'Father',
                    'arrows' => 'to'
                ];
            }
            if (!empty($data['Mother_CNIC']) && isset($persons[$data['Mother_CNIC']])) {
                $edges[] = [
                    'from' => $data['Mother_CNIC'], 
                    'to' => $cnic,
                    'label' => 'Mother',
                    'arrows' => 'to'
                ];
            }
        }

            // Add marriage relationships
          $cnicList = implode("','", array_keys($persons));
          $marriagesQuery = $conn->query("SELECT * FROM marriages 
                                        WHERE (spouse1_cnic IN ('$cnicList') 
                                        OR spouse2_cnic IN ('$cnicList'))");  // Properly balanced parentheses

          while ($marriage = $marriagesQuery->fetch_assoc()) {
              if (isset($persons[$marriage['spouse1_cnic']]) && isset($persons[$marriage['spouse2_cnic']])) {
                  $edges[] = [
                      'from' => $marriage['spouse1_cnic'],
                      'to' => $marriage['spouse2_cnic'],
                      'label' => 'Married',
                      'color' => '#FF69B4',
                      'dashes' => true,
                      'arrows' => ''
                  ];
              }
          }

        echo "<script>
            const nodes = " . json_encode($nodes) . ";
            const edges = " . json_encode($edges) . ";
        </script>";
    }
    ?>
    
    <script>
    if (typeof nodes !== 'undefined') {
      const container = document.getElementById('tree');
      const data = {
        nodes: new vis.DataSet(nodes),
        edges: new vis.DataSet(edges)
      };
      
      const options = {
        layout: {
          hierarchical: {
            direction: 'UD',
            sortMethod: 'directed',
            nodeSpacing: 150,
            levelSeparation: 100
          }
        },
        physics: {
          hierarchicalRepulsion: {
            nodeDistance: 200
          }
        },
        edges: {
          smooth: true,
          font: {
            size: 12,
            align: 'middle'
          }
        },
        nodes: {
          font: {
            size: 14,
            face: 'Tahoma'
          },
          borderWidth: 1,
          shadow: true,
          margin: 10
        }
      };
      
      new vis.Network(container, data, options);
    }
    </script>
  </div>
</body>
</html>