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
<style>
/* BODY */
body, html {
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
    height: 100%;
}

/* HEADER */
header {
    background: rgba(26, 20, 18, 0.85);
    backdrop-filter: blur(4px);
    padding: 15px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: fixed;
    width: 100%;
    z-index: 10;
}

.nav-brand {
    font-size: 1.8rem;
    font-weight: bold;
    color: #f8f3e9;
    text-decoration: none;
}

.nav-links a, .logout-form button {
    color: #f8f3e9;
    margin-left: 20px;
    text-decoration: none;
    font-weight: bold;
    border: none;
    background: none;
    cursor: pointer;
}

.nav-links a:hover, .logout-form button:hover {
    color: #c09d74;
}

/* HERO SECTION */
.hero-section {
    background: url('../image/pngtree-coffee-shop-with-many-wooden-tables-and-chairs-picture-image_2611848.jpg.jpg') no-repeat center center/cover;
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 0 20px;
}

.hero-content {
    background: rgba(31, 24, 22, 0.75);
    padding: 40px 30px;
    border-radius: 20px;
    color: #f8f3e9;
    box-shadow: 0 6px 15px rgba(0,0,0,0.5);
    max-width: 700px;
}

.hero-content h1 {
    font-size: 3rem;
    margin-bottom: 15px;
}

.hero-content p {
    font-size: 1.3rem;
    margin-bottom: 25px;
}

.btn-primary {
    background-color: #4e342e;
    color: #c09d74;
    border: none;
    padding: 10px 25px;
    font-size: 1rem;
    font-weight: bold;
    border-radius: 10px;
    transition: 0.3s;
    text-decoration: none;
}

.btn-primary:hover {
    background-color: #3e2721;
    color: #f8f3e9;
}

/* FOOTER */
footer {
    text-align: center;
    padding: 15px 0;
    background: rgba(26, 20, 18, 0.85);
    color: #f8f3e9;
    position: relative;
    margin-top: 0;
    font-size: 0.9rem;
}

/* RESPONSIVE */
@media(max-width: 768px){
    .hero-content h1 {
        font-size: 2.2rem;
    }

    .hero-content p {
        font-size: 1rem;
    }

    .nav-links a, .logout-form button {
        margin-left: 10px;
        font-size: 0.9rem;
    }
}
</style>
</head>
<body>

<header>
    <a href="home.php" class="nav-brand">Brewstack Coffee</a>
    <div class="nav-links">
        <a href="home.php">Home</a>
        <?php if (!empty($_SESSION['username'])): ?>
            <form action="logout.php" method="post" class="logout-form" style="display:inline;">
                <button type="submit">Logout</button>
            </form>
        <?php else: ?>
            <a href="login.php">Login</a>
        <?php endif; ?>
    </div>
</header>

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
