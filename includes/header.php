
 <header class="d-flex justify-content-between align-items-center">
    <a href="home.php" class="nav-brand">Brewstack Coffee</a>
    <div class="nav-links align-items-center">
        <?php if ($_SESSION['username']):?>
            <a href="dashboard.php">Dashboard</a>
            <form method="post" action="logout.php" style="display:inline;">
                <button class="btn btn-warning btn-sm ms-2">Logout</button>
            </form>
        <?php else: ?>    
            <?php if (str_contains($_SERVER['REQUEST_URI'], 'home')): ?>
                <a href="home.php">Home</a> 
                <a href="login.php" class="">Login</a>
                <a href="register.php" id="register-link-header" class="btn btn-sm btn-warning register-link primary">Register</a>
            <?php elseif (str_contains($_SERVER['REQUEST_URI'], 'login')): ?>
                <a href="home.php">Home</a> 
                <a href="register.php" id="register-link-header" class="btn btn-sm btn-warning register-link primary">Register</a>
            <?php elseif (str_contains($_SERVER['REQUEST_URI'], 'register')): ?>
                <a href="home.php">Home</a>
                <a href="login.php" class="btn btn-sm btn-warning primary">Login</a>
            <?php elseif (str_contains($_SERVER['REQUEST_URI'], 'dashboard')): ?>
                <a href="home.php">Home</a>
                Welcome, <?= htmlspecialchars($_SESSION['username']) ?>
                <form method="post" action="logout.php" style="display:inline;">
                    <button class="btn btn-warning btn-sm ms-2">Logout</button>
                </form>
            <?php endif ?>
        <?php endif; ?>

    </div>
</header> <!-- login header -->


<!-- <header>
    <a href="dashboard.php" class="nav-brand">Brewstack Coffee</a>

    <div>
        Welcome, <?= htmlspecialchars($_SESSION['username']) ?>
        <form method="post" action="logout.php" style="display:inline;">
            <button class="btn btn-warning btn-sm ms-2">Logout</button>
        </form>
    </div>
</header> -->


<!--
<header class="d-flex justify-content-between align-items-center">
    <a href="home.php" class="nav-brand">Brewstack Coffee</a>

    <?php if (!empty($_SESSION['username'])): ?>
        <form action="logout.php" method="post">
            <button type="submit" class="btn btn-sm btn-secondary">Logout</button>
        </form>
    <?php else: ?>
        <a href="login.php" class="btn btn-sm btn-warning">Login</a>
    <?php endif; ?>
</header> <!-- register header --


<header class="d-flex justify-content-between align-items-center">
    <a href="home.php" class="nav-brand">Brewstack Coffee</a>
    <div class="nav-links">
        <a href="home.php" class="btn btn-sm mx-0">Home</a>
        <a href="login.php" class="btn btn-sm ">Register</a>
        <?php if (!empty($_SESSION['username'])): ?>
            <form action="logout.php" method="post" class="logout-form" style="display:inline;">
                </form>
                <button type="submit">Logout</button>
        <?php else: ?>
            <a href="login.php" class="btn btn-sm btn-warning">Login</a>
        <?php endif; ?>
    </div>
</header> <!-- home header -->


<!-- <header class="d-flex justify-content-between align-items-center">
    <a href="home.php" class="nav-brand">Brewstack Coffee</a>

    <?php if (!empty($_SESSION['username'])): ?>
        <form action="logout.php" method="post">
            <button type="submit" class="btn btn-sm btn-secondary">Logout</button>
        </form>
    <?php else: ?>
        <a href="login.php" class="btn btn-sm btn-warning">Login</a>
    <?php endif; ?>
</header>-->