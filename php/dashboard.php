<?php
session_start();
include __DIR__ . '/../config/db.php';

if (empty($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Fetch users
$stmt = $pdo->query("SELECT * FROM users ORDER BY id_number ASC");
$users = $stmt->fetchAll();

$totalUsers = count($users);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Brewstack Coffee - Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">


<link rel="stylesheet" href="../css/style.css">
<style>

body {
    font-family: Arial, sans-serif;
    background: url("../image/image2.png") no-repeat center center/cover;
    min-height: 100vh;
    margin: 0;
}

/* HEADER */
/* header {
    background: #1a1412cc;
    backdrop-filter: blur(4px);
    padding: 15px 20px;
    color: #f8f3e9;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.nav-brand {
    font-size: 1.6rem;
    font-weight: bold;
    color: #f8f3e9;
    text-decoration: none;
}

.nav-brand:hover {
    opacity: 0.8;
} */

/* MAIN WRAPPER */
.dashboard-wrapper {
    padding: 25px;
    max-width: 1200px;
    margin: auto;
}

/* DASHBOARD CARDS */
.card-box {
    background: rgba(31, 24, 22, 0.85);
    padding: 25px;
    border-radius: 18px;
    text-align: center;
    color: #f8f3e9;
    box-shadow: 0 4px 12px rgba(0,0,0,0.4);
    transition: 0.2s;
}

.card-box:hover {
    transform: translateY(-4px);
}

.card-box i {
    font-size: 32px;
    color: #c09d74;
    margin-bottom: 10px;
}

/* TABLE WRAPPER */
.table-container {
    margin-top: 25px;
    background: rgba(31, 24, 22, 0.85);
    backdrop-filter: blur(4px);
    padding: 20px;
    border-radius: 18px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.4);
    color: #f8f3e9;
}

/* TABLE */
table {
    width: 100%;
}

thead th {
    background: #4e342e;
    color: #f8f3e9;
    padding: 10px;
}

tbody tr:nth-child(even) {
    background: rgba(255,255,255,0.08);
}

tbody td {
    padding: 10px;
    color: #f8f3e9;
}

/* FOOTER */
footer {
    text-align: center;
    padding: 10px;
    margin-top: 43px;
    background: #1a1412cc;
    color: #f8f3e9;
}

</style>
</head>
<body>

<!-- header -->
<?php include '../includes/header.php' ?>

<div class="dashboard-wrapper">

    <h2 class="text-light mb-4">Dashboard Overview</h2>

    <!-- DASHBOARD CARDS -->
    <div class="row g-3">
        <div class="col-md-3">
            <div class="card-box">
                <i class="fas fa-users"></i>
                <h4>Total Users</h4>
                <p><?= $totalUsers ?></p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card-box">
                <i class="fas fa-user-clock"></i>
                <h4>Active Today</h4>
                <p>5</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card-box">
                <i class="fas fa-envelope"></i>
                <h4>Messages</h4>
                <p>3</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card-box">
                <i class="fas fa-chart-line"></i>
                <h4>System Logs</h4>
                <p>12</p>
            </div>
        </div>
    </div>

    <!-- USER TABLE -->
    <div class="table-container mt-4">
        <h3 class="mb-3">Registered Users</h3>

        <div class="table-responsive">
            <table class="table table-borderless table-hover align-middle">
                <thead>
                    <tr>
                        <th>ID Number</th>
                        <th>Name</th>
                        <th>Extension</th>
                        <th>Sex</th>
                        <th>Birthdate</th>
                        <th>Age</th>
                        <th>Address</th>
                        <th>Email</th>
                        <th>Username</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= htmlspecialchars($user['id_number']) ?></td>
                                <td><?= htmlspecialchars($user['first_name'].' '.$user['middle_name'].' '.$user['last_name']) ?></td>
                                <td><?= htmlspecialchars($user['extension_name']) ?></td>
                                <td><?= htmlspecialchars($user['sex']) ?></td>
                                <td><?= htmlspecialchars($user['birthdate']) ?></td>
                                <td><?= htmlspecialchars($user['age']) ?></td>
                                <td><?= htmlspecialchars($user['purok'].', '.$user['barangay'].', '.$user['city']) ?></td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td><?= htmlspecialchars($user['username']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="9" class="text-center">No users found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

<footer>
    &copy; 2025 Brewstack Coffee — All Rights Reserved.
</footer>

</body>
</html>
