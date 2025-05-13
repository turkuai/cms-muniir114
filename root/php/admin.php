<?php
require_once 'functions.php';

// Handle form submissions
// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'update_logo':
                updateLogoUrl($_POST['logo_url']);
                break;
            case 'update_footer':
                updateFooterContent($_POST['footer_note']);
                break;
            case 'add_social':
                addSocialMedia($_POST['social_name'], $_POST['social_url']);
                break;
            case 'delete_social':
                deleteSocialMedia($_POST['platform_name']);
                break;
                case 'add_article':
                    $display_location = $_POST['display_location'] ?? 'home';
                    $menu_id = ($display_location === 'menu') ? $_POST['menu_id'] : null;
                    addArticle(
                        $_POST['article_title'],
                        $_POST['article_content'],
                        $_POST['article_image'],
                        $display_location,
                        $menu_id
                    );
                    break;
                
            case 'update_article':
                updateArticle($_POST['article_id'], $_POST['article_title'], $_POST['article_content'], $_POST['article_image']);
                break;
            case 'delete_article':
                deleteArticle($_POST['article_id']);
                break;
            case 'update_company':
                updateCompanyInfo($_POST['company_name'], $_POST['company_address'], $_POST['company_phone'], $_POST['company_email']);
                break;

            // ✅ Add these cases for menus:
            case 'add_menu':
                addMenu($_POST['menu_title'], $_POST['menu_url'], isset($_POST['is_article']) ? 1 : 0, $_POST['menu_order']);
                break;
            case 'update_menu':
                updateMenu($_POST['menu_id'], $_POST['menu_title'], $_POST['menu_url'], isset($_POST['is_article']) ? 1 : 0, $_POST['menu_order']);
                break;
            case 'delete_menu':
                deleteMenu($_POST['menu_id']);
                break;
        }
    
        // Redirect to avoid form resubmission
        header("Location: admin.php");
        exit();
    }
}

// Load data from database
$logoUrl = getLogoUrl();
$footerNote = getFooterContent();
$socialLinks = getAllSocialMedia();
$articles = getAllArticles();
$companyInfo = getCompanyInfo();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --success-color: #2ecc71;
            --danger-color: #e74c3c;
            --warning-color: #f39c12;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
            --border-radius: 0.375rem;
            --box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #212529;
            background-color: #f5f7fa;
        }

        /* Admin Header */
        .admin-header {
            background-color: var(--secondary-color);
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: var(--box-shadow);
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1030;
        }

        .admin-header h1 {
            font-size: 1.5rem;
            font-weight: 500;
            display: flex;
            align-items: center;
        }

        .admin-header h1 i {
            margin-right: 0.75rem;
            font-size: 1.25rem;
        }

        /* Sidebar Navigation */
        .admin-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            height: 100vh;
            background-color: white;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            padding-top: 70px;
            transform: translateX(-100%);
            transition: var(--transition);
            z-index: 1020;
        }

        .admin-sidebar.active {
            transform: translateX(0);
        }

        .admin-nav {
            list-style: none;
            padding: 1rem 0;
        }

        .admin-nav li {
            margin-bottom: 0.5rem;
        }

        .admin-nav a {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: var(--dark-color);
            text-decoration: none;
            transition: var(--transition);
        }

        .admin-nav a:hover,
        .admin-nav a.active {
            background-color: rgba(52, 152, 219, 0.1);
            color: var(--primary-color);
        }

        .admin-nav i {
            margin-right: 0.75rem;
            width: 20px;
            text-align: center;
        }

        /* Main Content */
        .admin-main {
            margin-left: 0;
            padding: 90px 20px 20px;
            transition: var(--transition);
        }

        .admin-main.active {
            margin-left: 250px;
        }

        /* Dashboard Cards */
        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .card {
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
            transition: var(--transition);
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
        }

        .card-header {
            padding: 1.25rem 1.5rem;
            background-color: rgba(0, 0, 0, 0.03);
            border-bottom: 1px solid rgba(0, 0, 0, 0.125);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-header h3 {
            margin: 0;
            font-size: 1.25rem;
            color: var(--secondary-color);
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--secondary-color);
        }

        .form-control {
            display: block;
            width: 100%;
            padding: 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: var(--border-radius);
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .form-control:focus {
            color: #495057;
            background-color: #fff;
            border-color: #80bdff;
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }

        /* Button Styles */
        .btn {
            display: inline-block;
            font-weight: 400;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            user-select: none;
            border: 1px solid transparent;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            line-height: 1.5;
            border-radius: var(--border-radius);
            transition: var(--transition);
            cursor: pointer;
        }

        .btn-primary {
            color: #fff;
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #2980b9;
            border-color: #2980b9;
        }

        .btn-success {
            color: #fff;
            background-color: var(--success-color);
            border-color: var(--success-color);
        }

        .btn-success:hover {
            background-color: #27ae60;
            border-color: #27ae60;
        }

        .btn-danger {
            color: #fff;
            background-color: var(--danger-color);
            border-color: var(--danger-color);
        }

        .btn-danger:hover {
            background-color: #c0392b;
            border-color: #c0392b;
        }

        .btn-warning {
            color: #212529;
            background-color: var(--warning-color);
            border-color: var(--warning-color);
        }

        .btn-warning:hover {
            background-color: #e67e22;
            border-color: #e67e22;
        }

        /* Table Styles */
        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 0.75rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
            background-color: rgba(0, 0, 0, 0.03);
            color: var(--secondary-color);
        }

        .table tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.02);
        }

        .table tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }

        /* Alert Styles */
        .alert {
            position: relative;
            padding: 0.75rem 1.25rem;
            margin-bottom: 1rem;
            border: 1px solid transparent;
            border-radius: var(--border-radius);
        }

        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }

        /* Toggle Button */
        .sidebar-toggle {
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.5rem;
            display: none;
        }

        /* Responsive Adjustments */
        @media (max-width: 992px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }
            .admin-sidebar.active {
                transform: translateX(0);
            }
            .admin-main {
                margin-left: 0;
            }
            .admin-main.active {
                margin-left: 0;
            }
            .sidebar-toggle {
                display: block;
            }
        }

        /* Font Awesome Icons (fallback) */
        i {
            font-style: normal;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
    </style>
    <!-- Keep the same head section as before -->
    <!-- ... -->
</head>
<body>
    <!-- Admin Header -->
    <header class="admin-header">
        <button class="sidebar-toggle" onclick="toggleSidebar()">☰</button>
        <h1><i>📊</i> CMS Admin Panel</h1>
        <nav>
            <a href="logout.php" style="color: white; text-decoration: none;">Logout</a>
        </nav>
    </header>

    <!-- Sidebar Navigation -->
    <aside class="admin-sidebar">
        <ul class="admin-nav">
            <li><a href="#dashboard" class="active"><i>📊</i> Dashboard</a></li>
            <li><a href="#logo"><i>🖼️</i> Logo Settings</a></li>
            <li><a href="#footer"><i>📝</i> Footer Content</a></li>
            <li><a href="#social"><i>🔗</i> Social Media</a></li>
            <li><a href="#articles"><i>📰</i> Articles</a></li>
            <li><a href="#company"><i>🏢</i> Company Info</a></li>
        </ul>
    </aside>

    <!-- Main Content -->
    <main class="admin-main">
        <div class="dashboard-cards">
            <!-- Logo Settings Card -->
            <div class="card" id="logo-card">
                <div class="card-header">
                    <h3><i>🖼️</i> Logo Settings</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="admin.php">
                        <input type="hidden" name="action" value="update_logo">
                        <div class="form-group">
                            <label for="logo-url" class="form-label">Logo URL</label>
                            <input type="text" id="logo-url" name="logo_url" class="form-control" 
                                   placeholder="Enter logo URL" value="<?= htmlspecialchars($logoUrl) ?>">
                        </div>
                        <button type="submit" class="btn btn-primary">Update Logo</button>
                    </form>
                </div>
            </div>

            <!-- Footer Content Card -->
            <div class="card" id="footer-card">
                <div class="card-header">
                    <h3><i>📝</i> Footer Content</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="admin.php">
                        <input type="hidden" name="action" value="update_footer">
                        <div class="form-group">
                            <label for="footer-note" class="form-label">Footer Note</label>
                            <textarea id="footer-note" name="footer_note" class="form-control" 
                                      placeholder="Enter footer note"><?= htmlspecialchars($footerNote) ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Footer Note</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Social Media Card -->
        <div class="card mb-4" id="social-card">
            <div class="card-header">
                <h3><i>🔗</i> Social Media Links</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <form method="POST" action="admin.php">
                            <input type="hidden" name="action" value="add_social">
                            <div class="form-group">
                                <label for="social-name" class="form-label">Social Media Name</label>
                                <input type="text" id="social-name" name="social_name" class="form-control" placeholder="e.g. Facebook">
                            </div>
                            <div class="form-group">
                                <label for="social-url" class="form-label">Profile URL</label>
                                <input type="text" id="social-url" name="social_url" class="form-control" placeholder="https://...">
                            </div>
                            <button type="submit" class="btn btn-success">Add Social Media</button>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <h4>Existing Links</h4>
                        <div id="social-media-list" class="mt-3">
                            <?php if (empty($socialLinks)): ?>
                                <div class="alert alert-info">No social media links added yet.</div>
                            <?php else: ?>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Platform</th>
                                            <th>URL</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($socialLinks as $name => $url): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($name) ?></td>
                                            <td><a href="<?= htmlspecialchars($url) ?>" target="_blank"><?= htmlspecialchars($url) ?></a></td>
                                            <td>
                                                <form method="POST" action="admin.php" style="display: inline;">
                                                    <input type="hidden" name="action" value="delete_social">
                                                    <input type="hidden" name="platform_name" value="<?= htmlspecialchars($name) ?>">
                                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Articles Card -->
        <div class="card mb-4" id="articles-card">
            <div class="card-header">
                <h3><i>📰</i> Articles Management</h3>
            </div>
           <!-- Replace your current article form with this -->
<form method="POST" action="admin.php">
    <input type="hidden" name="action" value="add_article">
    <div class="form-group">
        <label for="article-title" class="form-label">Article Title</label>
        <input type="text" id="article-title" name="article_title" class="form-control" placeholder="Enter title" required>
    </div>
    <div class="form-group">
        <label for="article-content" class="form-label">Content</label>
        <textarea id="article-content" name="article_content" class="form-control" placeholder="Enter content" required></textarea>
    </div>
    <div class="form-group">
        <label for="article-image" class="form-label">Image URL</label>
        <input type="text" id="article-image" name="article_image" class="form-control" placeholder="Enter image URL">
    </div>
    
    <!-- Display location selection -->
    <div class="form-group">
        <label class="form-label">Display Location</label>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="display_location" id="display-home" value="home" checked 
                   onchange="toggleMenuSelection(false)">
            <label class="form-check-label" for="display-home">Homepage</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="display_location" id="display-menu" value="menu"
                   onchange="toggleMenuSelection(true)">
            <label class="form-check-label" for="display-menu">Specific Menu</label>
        </div>
    </div>
    
    <!-- Menu selection (hidden by default) -->
    <div class="form-group" id="menu-selection-group" style="display:none;">
        <label for="menu-id" class="form-label">Select Menu</label>
        <select id="menu-id" name="menu_id" class="form-control">
            <?php foreach (getAllMenus() as $menu): ?>
                <option value="<?= $menu['id'] ?>"><?= htmlspecialchars($menu['title']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <button type="submit" class="btn btn-success">Add Article</button>
</form>

                    <div class="col-md-6">
                        <h4>Existing Articles</h4>
                        <div id="article-list" class="mt-3">
                            <?php if (empty($articles)): ?>
                                <div class="alert alert-info">No articles added yet.</div>
                            <?php else: ?>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Preview</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($articles as $article): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($article['title']) ?></td>
                                            <td><?= htmlspecialchars(substr($article['content'], 0, 50)) ?>...</td>
                                            <td>
                                                <a href="edit_article.php?id=<?= $article['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                                <form method="POST" action="admin.php" style="display: inline;">
                                                    <input type="hidden" name="action" value="delete_article">
                                                    <input type="hidden" name="article_id" value="<?= $article['id'] ?>">
                                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- In the sidebar navigation -->
<li><a href="#menus"><i>📋</i> Menu Management</a></li>

<!-- In the main content -->
<div class="card mb-4" id="menus-card">
    <div class="card-header">
        <h3><i>📋</i> Menu Management</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <form method="POST" action="admin.php">
                    <input type="hidden" name="action" value="add_menu">
                    <div class="form-group">
                        <label for="menu-title" class="form-label">Menu Title</label>
                        <input type="text" id="menu-title" name="menu_title" class="form-control" placeholder="Enter menu title">
                    </div>
                    <div class="form-group">
                        <label for="menu-url" class="form-label">URL</label>
                        <input type="text" id="menu-url" name="menu_url" class="form-control" placeholder="Enter URL or article slug">
                    </div>
                    <div class="form-group">
                        <label for="menu-order" class="form-label">Display Order</label>
                        <input type="number" id="menu-order" name="menu_order" class="form-control" placeholder="Enter display order">
                    </div>
                    <div class="form-group form-check">
                        <input type="checkbox" id="is-article" name="is_article" class="form-check-input">
                        <label for="is-article" class="form-check-label">Is this an article page?</label>
                    </div>
                    <button type="submit" class="btn btn-success">Add Menu Item</button>
                </form>
            </div>
            <div class="col-md-6">
                <h4>Current Menu Items</h4>
                <div id="menu-list" class="mt-3">
                    <?php $menus = getAllMenus(); ?>
                    <?php if (empty($menus)): ?>
                        <div class="alert alert-info">No menu items added yet.</div>
                    <?php else: ?>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>URL</th>
                                    <th>Type</th>
                                    <th>Order</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($menus as $menu): ?>
                                <tr>
                                    <td><?= htmlspecialchars($menu['title']) ?></td>
                                    <td><?= htmlspecialchars($menu['url']) ?></td>
                                    <td><?= $menu['is_article'] ? 'Article' : 'Link' ?></td>
                                    <td><?= $menu['display_order'] ?></td>
                                    <td>
                                        <a href="edit_menu.php?id=<?= $menu['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                        <form method="POST" action="admin.php" style="display: inline;">
                                            <input type="hidden" name="action" value="delete_menu">
                                            <input type="hidden" name="menu_id" value="<?= $menu['id'] ?>">
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

        <!-- Company Info Card -->
        <div class="card" id="company-card">
            <div class="card-header">
                <h3><i>🏢</i> Company Information</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="admin.php">
                    <input type="hidden" name="action" value="update_company">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="company-name" class="form-label">Company Name</label>
                                <input type="text" id="company-name" name="company_name" class="form-control" 
                                       placeholder="Company Name" value="<?= htmlspecialchars($companyInfo['company_name'] ?? '') ?>">
                            </div>
                            <div class="form-group">
                                <label for="company-address" class="form-label">Address</label>
                                <input type="text" id="company-address" name="company_address" class="form-control" 
                                       placeholder="Company Address" value="<?= htmlspecialchars($companyInfo['address'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="company-phone" class="form-label">Phone Number</label>
                                <input type="text" id="company-phone" name="company_phone" class="form-control" 
                                       placeholder="Phone Number" value="<?= htmlspecialchars($companyInfo['phone'] ?? '') ?>">
                            </div>
                            <div class="form-group">
                                <label for="company-email" class="form-label">Email</label>
                                <input type="text" id="company-email" name="company_email" class="form-control" 
                                       placeholder="Email" value="<?= htmlspecialchars($companyInfo['email'] ?? '') ?>">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Company Info</button>
                </form>
            </div>
        </div>
    </main>

    <script>
        // Toggle sidebar on mobile
        function toggleSidebar() {
            document.querySelector('.admin-sidebar').classList.toggle('active');
            document.querySelector('.admin-main').classList.toggle('active');
        }

        // Show alert message
        function showAlert(message, type) {
            const alert = document.createElement('div');
            alert.className = `alert alert-${type}`;
            alert.textContent = message;
            
            const header = document.querySelector('.admin-header');
            header.insertAdjacentElement('afterend', alert);
            
            setTimeout(() => {
                alert.remove();
            }, 3000);
        }

        // Check for URL parameters to show alerts
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('success')) {
                showAlert(urlParams.get('success'), 'success');
            }
        };
        function toggleMenuSelection(show) {
    document.getElementById('menu-selection-group').style.display = show ? 'block' : 'none';
    if (show) {
        document.getElementById('menu-id').required = true;
    } else {
        document.getElementById('menu-id').required = false;
    }
}
    </script>
</body>
</html>