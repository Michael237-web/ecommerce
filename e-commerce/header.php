<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}

require_once 'db.php';

$cartCount = 0;
if(isset($_SESSION['cart'])){
    $cartCount = array_sum($_SESSION['cart']);
}

$catStmt = $pdo->query("SELECT * FROM categories ORDER BY name");
$categories = $catStmt->fetchAll();

$userName = '';
if(isset($_SESSION['user_id'])){
    $userStmt = $pdo->prepare("SELECT fullname FROM users WHERE id = ?");
    $userStmt->execute([$_SESSION['user_id']]);
    $user = $userStmt->fetch();
    $userName = $user ? $user['fullname'] : 'User';
}

$currentPage = basename($_SERVER['PHP_SELF']);
?>

<header class="main-header">
    <div class="header-container">
        <div class="logo">
            <a href="index.php">
                <span class="logo-icon">🛍️</span>
                <span class="logo-text">Michael<span>Store</span></span>
            </a>
        </div>

        <div class="search-bar">
            <form action="products.php" method="GET">
                <input type="text" name="search" placeholder="Search for products, brands, categories..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                <button type="submit">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"/>
                        <path d="M21 21l-4.35-4.35"/>
                    </svg>
                </button>
            </form>
        </div>

        <div class="header-actions">
            <button class="menu-toggle" onclick="toggleMenu()" aria-label="Toggle menu">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="3" y1="12" x2="21" y2="12"/>
                    <line x1="3" y1="6" x2="21" y2="6"/>
                    <line x1="3" y1="18" x2="21" y2="18"/>
                </svg>
            </button>

            <a href="wishlist.php" class="header-action">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                </svg>
                <span class="action-label">Wishlist</span>
            </a>

            <a href="cart.php" class="header-action cart-action">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="9" cy="21" r="1"/>
                    <circle cx="20" cy="21" r="1"/>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                </svg>
                <span class="action-label">Cart</span>
                <span class="cart-badge"><?= $cartCount ?></span>
            </a>

            <?php if(isset($_SESSION['user_id'])): ?>
                <div class="header-action user-dropdown">
                    <div class="user-avatar">
                        <span><?= substr($userName, 0, 1) ?></span>
                    </div>
                    <span class="action-label"><?= htmlspecialchars($userName) ?></span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="6 9 12 15 18 9"/>
                    </svg>
                    
                    <div class="dropdown-menu user-menu">
                        <a href="account.php">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                            My Account
                        </a>
                        <a href="orders.php">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
                                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                            </svg>
                            My Orders
                        </a>
                        <?php if(isset($_SESSION['role']) && $_SESSION['role'] == "admin"): ?>
                            <a href="admin-panel.php">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                </svg>
                                Admin Panel
                            </a>
                        <?php endif; ?>
                        <hr>
                        <a href="logout.php" class="logout-link">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                                <polyline points="16 17 21 12 16 7"/>
                                <line x1="21" y1="12" x2="9" y2="12"/>
                            </svg>
                            Logout
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <div class="header-action login-btn">
                    <a href="login.php" class="btn-login">Login</a>
                    <a href="register.php" class="btn-register">Register</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <nav class="main-nav" id="mainNav">
        <div class="nav-container">
            <ul class="nav-list">
                <li><a href="index.php" class="nav-link <?= $currentPage == 'index.php' ? 'active' : '' ?>">Home</a></li>
                
                <li class="dropdown">
                    <a href="products.php" class="nav-link dropdown-trigger <?= $currentPage == 'products.php' ? 'active' : '' ?>">
                        Products
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="6 9 12 15 18 9"/>
                        </svg>
                    </a>
                    <div class="dropdown-menu">
                        <div class="mega-menu-content">
                            <div class="mega-column">
                                <h4>📂 Categories</h4>
                                <a href="products.php">All Products</a>
                                <?php foreach($categories as $cat): ?>
                                    <a href="products.php?category=<?= $cat['id'] ?>">
                                        <?= htmlspecialchars($cat['name']) ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                            <div class="mega-column">
                                <h4>⭐ Popular</h4>
                                <a href="products.php?category=Electronics">📱 Electronics</a>
                                <a href="products.php?category=Computers">💻 Computers</a>
                                <a href="products.php?category=Fashion">👔 Fashion</a>
                                <a href="products.php?category=Accessories">🎧 Accessories</a>
                            </div>
                            <div class="mega-column featured-category">
                                <h4>🔥 Featured</h4>
                                <a href="products.php?featured=1">Trending Now</a>
                                <a href="products.php?sort=popular">Best Sellers</a>
                                <a href="products.php?sort=newest">New Arrivals</a>
                                <a href="products.php?discount=1">On Sale</a>
                            </div>
                        </div>
                    </div>
                </li>

                <li><a href="products.php?sort=popular" class="nav-link <?= isset($_GET['sort']) && $_GET['sort'] == 'popular' ? 'active' : '' ?>">Best Sellers</a></li>
                <li><a href="products.php?discount=1" class="nav-link <?= isset($_GET['discount']) ? 'active' : '' ?>">On Sale</a></li>
                <li><a href="about.php" class="nav-link <?= $currentPage == 'about.php' ? 'active' : '' ?>">About</a></li>
                <li><a href="contact.php" class="nav-link <?= $currentPage == 'contact.php' ? 'active' : '' ?>">Contact</a></li>
            </ul>
        </div>
    </nav>
</header>