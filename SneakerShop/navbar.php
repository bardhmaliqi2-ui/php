<?php
$root = dirname(__DIR__);
require_once "config.php";
require_once "functions.php";

$loggedIn = isLoggedIn();
$admin = $loggedIn && isAdmin();

?>
<style>

* {
    box-sizing: border-box;
}

.navbar {
    width: 100%;
    background: #111;
    color: white;
    padding: 15px 6%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: sticky;
    top: 0;
    z-index: 1000;
}

.nav-logo {
    font-size: 26px;
    font-weight: 900;
    text-decoration: none;
    color: white;
    letter-spacing: 1px;
}

.nav-logo span {
    color: #e63946;
}

.nav-links {
    display: flex;
    align-items: center;
    gap: 25px;
}

.nav-links a {
    text-decoration: none;
    color: white;
    font-size: 15px;
    font-weight: bold;
    transition: 0.3s;
}

.nav-links a:hover {
    color: #e63946;
}

.nav-cart {
    border: 1px solid white;
    padding: 9px 16px;
    border-radius: 25px;
}

.nav-cart:hover {
    background: #e63946;
    border-color: #e63946;
    color: white !important;
}

.nav-login {
    background: #e63946;
    padding: 10px 18px;
    border-radius: 25px;
}

.nav-login:hover {
    background: white;
    color: #111 !important;
}

.nav-logout {
    color: #ff7777 !important;
}

.nav-admin {
    color: #ffd166 !important;
}

@media(max-width: 750px) {

    .navbar {
        flex-direction: column;
        gap: 15px;
        padding: 18px 5%;
    }

    .nav-links {
        flex-wrap: wrap;
        justify-content: center;
        gap: 15px;
    }

    .nav-links a {
        font-size: 14px;
    }

}

</style>

<nav class="navbar">

    <a href="<?= $root ?>/index.php" class="nav-logo">
        SNEAKER<span>SHOP</span>
    </a>

    <div class="nav-links">

        <a href="<?= $root ?>/index.php">
            Home
        </a>

        <a href="<?= $root ?>/shop.php">
            Shop
        </a>

        <a href="<?= $root ?>/contact.php">
            Contact
        </a>

        <?php if ($loggedIn): ?>

            <a href="<?= $root ?>/cart.php" class="nav-cart">
                🛒 Cart
            </a>

            <?php if ($admin): ?>

                <a href="<?= $root ?>/admin/dashboard.php"
                   class="nav-admin">
                    Admin Dashboard
                </a>

                <a href="<?= $root ?>/products.php">
                    Products
                </a>

            <?php endif; ?>

            <a href="<?= $root ?>/logout.php"
               class="nav-logout">
                Logout
            </a>

        <?php else: ?>

            <a href="<?= $root ?>/login.php"
               class="nav-login">
                Login
            </a>

            <a href="<?= $root ?>/register.php">
                Register
            </a>

        <?php endif; ?>
    </div>
</nav>