<?php
require_once 'functions.php';

if (!isset($_GET['url'])) {
    header("Location: index.php");
    exit();
}

$url = $_GET['url'];
$article = getArticleByUrl($url);

if (!$article) {
    header("HTTP/1.0 404 Not Found");
    include '404.php';
    exit();
}
// Function to get an article by its URL
function getArticleByUrl($url) {
    $query = "SELECT * FROM articles WHERE url = '$url' LIMIT 1";
    $result = mysqli_query($GLOBALS['db'], $query);
    return mysqli_fetch_assoc($result);
}
// In your article.php or wherever you handle menu clicks
if (isset($_GET['menu_id'])) {
    $menu_id = (int)$_GET['menu_id'];
    $menu = getMenuById($menu_id);
    $articles = getArticlesByMenu($menu_id);
    
    // Display the menu page with its articles
    include 'menu_page.php';
    exit();
}


$logoUrl = getLogoUrl();
$footerNote = getFooterContent();
$socialLinks = getAllSocialMedia();
$companyInfo = getCompanyInfo();
$menus = getAllMenus();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($article['title']) ?> | <?= htmlspecialchars($companyInfo['company_name'] ?? 'Your Site') ?></title>
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
            --border-radius: 0.375rem;
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

        header {
            position: fixed;
            top: 0;
            width: 100%;
            background: var(--secondary-color);
            color: white;
            padding: 15px 0;
            z-index: 1000;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header .logo img {
            height: 50px;
            margin-left: 20px;
        }

        header nav ul {
            list-style: none;
            display: flex;
            margin-right: 20px;
        }

        header nav ul li {
            margin: 0 15px;
            position: relative;
        }

        header nav ul li a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
            padding: 5px 0;
        }

        header nav ul li a:hover {
            color: #4CAF50;
        }

        .article-container {
            max-width: 800px;
            margin: 100px auto 50px;
            padding: 30px;
            background: white;
            border-radius: var(--border-radius);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .article-header {
            margin-bottom: 30px;
            text-align: center;
        }
        
        .article-title {
            font-size: 2.2rem;
            color: var(--secondary-color);
            margin-bottom: 20px;
            line-height: 1.2;
        }
        
        .article-meta {
            color: #6c757d;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }
        
        .article-image {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }
        
        .article-content {
            line-height: 1.8;
            font-size: 1.1rem;
        }
        
        .article-content p {
            margin-bottom: 1.5rem;
        }
        
        footer {
            background: var(--secondary-color);
            color: white;
            padding: 40px 20px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }
        
        footer .footer-section {
            flex: 1;
            min-width: 250px;
            margin-bottom: 20px;
        }
        
        footer h3 {
            color: #4CAF50;
            margin-bottom: 20px;
            font-size: 1.2rem;
        }
        
        footer p, footer a {
            color: #ddd;
            margin-bottom: 10px;
            display: block;
        }
        
        footer a:hover {
            color: white;
        }
        
        .copyright {
            text-align: center;
            width: 100%;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: 20px;
        }
        
        @media (max-width: 768px) {
            .article-container {
                margin: 80px 15px 30px;
                padding: 20px;
            }
            
            .article-title {
                font-size: 1.8rem;
            }
            
            header {
                flex-direction: column;
                padding: 10px 0;
            }
            
            header .logo {
                margin-bottom: 10px;
                margin-left: 0;
            }
            
            header nav ul {
                margin-right: 0;
                flex-wrap: wrap;
                justify-content: center;
            }
            
            header nav ul li {
                margin: 5px 10px;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <a href="index.php"><img src="<?= htmlspecialchars($logoUrl) ?>" alt="Company Logo"></a>
        </div>
        <nav>
            <ul>
                <?php foreach ($menus as $menu): ?>
                    <li><a href="<?= htmlspecialchars($menu['url']) ?>"><?= htmlspecialchars($menu['title']) ?></a></li>
                <?php endforeach; ?>
            </ul>
        </nav>
    </header>

    <div class="article-container">
        <div class="article-header">
            <h1 class="article-title"><?= htmlspecialchars($article['title']) ?></h1>
            <div class="article-meta">
                Published on <?= date('F j, Y', strtotime($article['created_at'])) ?>
            </div>
            <?php if (!empty($article['image_url'])): ?>
                <img src="<?= htmlspecialchars($article['image_url']) ?>" alt="<?= htmlspecialchars($article['title']) ?>" class="article-image">
            <?php endif; ?>
        </div>
        
        <div class="article-content">
            <?= nl2br(htmlspecialchars($article['content'])) ?>
        </div>
    </div>
    
    <footer>
        <div class="footer-section">
            <h3>About Us</h3>
            <p><?= htmlspecialchars($companyInfo['company_name'] ?? 'Our Company') ?></p>
            <p><?= htmlspecialchars($companyInfo['address'] ?? '') ?></p>
        </div>
        
        <div class="footer-section">
            <h3>Contact</h3>
            <p>Phone: <?= htmlspecialchars($companyInfo['phone'] ?? '') ?></p>
            <p>Email: <?= htmlspecialchars($companyInfo['email'] ?? '') ?></p>
        </div>
        
        <div class="footer-section">
            <h3>Follow Us</h3>
            <?php foreach ($socialLinks as $platform => $url): ?>
                <a href="<?= htmlspecialchars($url) ?>" target="_blank"><?= htmlspecialchars(ucfirst($platform)) ?></a>
            <?php endforeach; ?>
        </div>
        
        <div class="copyright">
            <p><?= htmlspecialchars($footerNote) ?></p>
            <p>&copy; <?= date('Y') ?> <?= htmlspecialchars($companyInfo['company_name'] ?? 'Your Site') ?>. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
