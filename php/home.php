<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Brewstack Coffee - Home</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<link rel="stylesheet" href="../css/home.css">
<link rel="stylesheet" href="../css/style.css">

</head>
<body>

<!-- header -->
<?php include '../includes/header.php' ?>

<section class="hero-section">
    <div class="hero-content">
        <h1>Welcome to Brewstack Coffee</h1>
        <p>Debug your day with a fresh brew. Taste the perfect coffee experience.</p>
        <?php if (empty($_SESSION['username'])): ?>
            <a href="login.php" class="btn-primary">Get Started</a>
        <?php else: ?>
            <a href="dashboard.php" class="btn-primary">Go to Dashboard</a>
        <?php endif; ?>
    </div>
</section>

<footer>
    &copy; 2025 Brewstack Coffee — All Rights Reserved.
</footer>

<?php if (!empty($_SESSION['username'])): ?>
<script>
    (function() {
        if (!window.history || !history.pushState) return;
        const lockHistory = () => history.pushState(null, '', location.href);
        lockHistory();
        window.addEventListener('popstate', lockHistory);
    })();
</script>
<?php endif; ?>

</body>
</html>
