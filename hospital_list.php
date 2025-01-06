<?php
require_once("conn.php");
include "header_footer_admin.html";

$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page > 1) ? ($page - 1) * $limit : 0;

$search = isset($_GET['search']) ? $_GET['search'] : '';
$searchQuery = $search ? "WHERE hospital_name LIKE '%$search%' OR hospital_address LIKE '%$search%'" : "";

$result = $conn->query("SELECT COUNT(*) AS total FROM hospital $searchQuery");
$totalRecords = $result->fetch_assoc()['total'];
$totalPages = ceil($totalRecords / $limit);

$sql = "SELECT hospital_id, hospital_username, hospital_email, hospital_password, hospital_name, hospital_address, hospital_contact_no 
        FROM hospital $searchQuery
        LIMIT $start, $limit";
$hospitals = $conn->query($sql);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital List</title>
    <style>
        /* table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        } */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 90%;
            max-width: 900px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #dc3545;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
         /*
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        table th, table td {
            padding: 12px 20px;
            text-align: center;
            border: 1px solid #ddd;
        }

        table th {
            background-color: grey;
            color: white;
            font-weight: bold;
        }

        table td {
            color: #333;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tr:hover {
            background-color: #f1f1f1;
            cursor: pointer;
        }*/
        button {
            padding: 5px 10px;
            margin: 0 5px;
            cursor: pointer;
        }
        .pagination a {
            display: inline-block;
            padding: 8px 12px;
            margin: 0 5px;
            text-decoration: none;
            background-color: #e6e6e6;
            color: black; 
            border-radius: 4px;
            border: 1px solid #ddd;
            transition: background-color 0.3s, color 0.3s;
        }

        .pagination a.active {
            background-color: #cccccc;
            font-weight: bold;
        }

        .pagination a:hover {
            background-color: #cccccc;
        }

        a.edit-button {
            display: inline-block;
            padding: 5px 10px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        a.edit-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <center><h2>Hospital List</h2></center>
        <div class="search-bar">
            <center>
            <form method="GET" action="">
                <input type="text" name="search" placeholder="Search by hospital name or area..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit">Search</button>
            </form>
            </center>
        </div>
        <br>
        <br>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Hospital Name</th>
                    <th>Address</th>
                    <th>Contact No</th>
                    <th>Update</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($hospitals->num_rows > 0): ?>
                    <?php while ($row = $hospitals->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['hospital_id']; ?></td>
                            <td><?php echo $row['hospital_username']; ?></td>
                            <td><?php echo $row['hospital_email']; ?></td>
                            <td><?php echo $row['hospital_name']; ?></td>
                            <td><?php echo $row['hospital_address']; ?></td>
                            <td><?php echo $row['hospital_contact_no']; ?></td>
                            <td><a href="update_hospital.php?hospital_id=<?php echo $row['hospital_id']; ?>" class="edit-button">Edit</a></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No hospitals found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <br>
        <div class="pagination">
            <center>
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?page=<?php echo $i; ?>" class="<?php echo ($i == $page) ? 'active' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
            </center>
        </div>
        <button><a href="hospital_registration_page.php">Add Hospital</button></a>
    </div>
</body>
</html>

<?php
$conn->close();
?>
