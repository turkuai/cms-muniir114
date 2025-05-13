<?php
require_once 'php/functions.php';

// Fetch data from database
$logoUrl = getLogoUrl();
$footerNote = getFooterContent();
$socialLinks = getAllSocialMedia();
$articles = getAllArticles();
$companyInfo = getCompanyInfo();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMS Example</title>
    <style>
        /* General Reset and Styling */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            padding-top: 50px;
        }

        header {
            position: fixed;
            top: 0;
            width: 100%;
            background: #333;
            color: white;
            padding: 15px 0;
            z-index: 1000;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        header .logo img {
            width: 100px;
            margin-left: 20px;
        }

        header nav ul {
            list-style: none;
            display: flex;
            justify-content: flex-end;
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
            transition: color 0.3s ease, transform 0.3s ease;
        }

        header nav ul li a:hover {
            color: #10e763;
            transform: translateY(-5px);
        }

        /* Main Section Styling */
        main {
            margin-top: 100px;
            padding: 20px;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Articles Grid Container */
        .articles-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 30px;
            margin-top: 20px;
        }

        /* Article Card Styling */
        .article-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .article-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        .article-image-container {
            width: 100%;
            height: 200px;
            overflow: hidden;
        }

        .article-image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .article-card:hover .article-image-container img {
            transform: scale(1.05);
        }

        .article-content {
            padding: 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .article-title {
            font-size: 1.5rem;
            margin-bottom: 15px;
            color: #2c3e50;
        }

        .article-text {
            color: #555;
            margin-bottom: 20px;
            flex-grow: 1;
        }

        /* Footer Styling */
        footer {
            background: #333;
            color: white;
            padding: 40px 20px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin-top: 40px;
        }

        footer .company-info {
            max-width: 48%;
        }

        footer .company-info p {
            font-size: 1rem;
            margin-bottom: 15px;
        }

        footer nav {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-end;
            gap: 20px;
        }

        footer nav ul {
            list-style: none;
            padding: 0;
        }

        footer nav ul li {
            margin: 10px 0;
        }

        footer nav ul li a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        footer nav ul li a:hover {
            color: #32ce1d;
        }

        footer p {
            font-size: 0.9rem;
            margin-top: 20px;
            text-align: center;
            width: 100%;
        }

        /* Social Media Link Styling */
        #social-media-links {
            list-style-type: none;
            padding: 0;
            margin-top: 20px;
        }

        #social-media-links li {
            margin: 5px 0;
        }

        #social-media-links li a {
            color: #ddd;
            text-decoration: none;
            font-size: 1rem;
            transition: color 0.3s ease;
        }

        #social-media-links li a:hover {
            color: #10e763;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .articles-grid {
                grid-template-columns: 1fr;
            }
            
            footer {
                flex-direction: column;
                gap: 20px;
            }
            
            footer .company-info {
                max-width: 100%;
            }
            
            footer nav {
                justify-content: flex-start;
            }
        }

        /* No Articles Message */
        .no-articles {
            text-align: center;
            padding: 40px;
            background: white;
            border-radius: 10px;
            grid-column: 1 / -1;
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <!-- Header Section -->
<header>
    <div class="logo">
        <img id="logoImage" src="<?= htmlspecialchars($logoUrl) ?>" alt="Company Logo">
    </div>
    <nav>
        <ul>
            <?php $menus = getAllMenus(); ?>
            <?php foreach ($menus as $menu): ?>
                <li><a href="<?= htmlspecialchars($menu['url']) ?>"><?= htmlspecialchars($menu['title']) ?></a></li>
            <?php endforeach; ?>
        </ul>
    </nav>
</header>

    <main>
        <!-- Container for dynamically added articles -->
        <div id="articles" class="articles-grid">
    <?php 
    // Get homepage articles
    // Replace your current article display code with this:
$homepage_articles = getHomepageArticles();

if (!empty($homepage_articles)): ?>
    <?php foreach ($homepage_articles as $article): ?>
        <article class="article-card">
            <div class="article-image-container">
                <img src="<?= htmlspecialchars($article['image_url'] ?: 'https://via.placeholder.com/400x200?text=No+Image') ?>" 
                     alt="<?= htmlspecialchars($article['title']) ?>">
            </div>
            <div class="article-content">
                <h2 class="article-title"><?= htmlspecialchars($article['title']) ?></h2>
                <p class="article-text">
                    <?= htmlspecialchars(substr($article['content'], 0, 150)) ?>
                    <?= strlen($article['content']) > 150 ? '...' : '' ?>
                </p>
                <a href="article.php?id=<?= $article['id'] ?>" class="read-more">Read More</a>
            </div>
        </article>
    <?php endforeach; ?>
<?php else: ?>
    <div class="no-articles">
        <h2>No Articles Available</h2>
        <p>Check back later for updates or add new articles in the admin panel.</p>
    </div>
<?php endif; ?>
    </main>
    
    <!-- Footer Section -->
    <footer>
        <div class="company-info">
            <?php if ($footerNote): ?>
                <p id="footer-note"><?= htmlspecialchars($footerNote) ?></p>
            <?php endif; ?>
            
            <?php if (!empty($companyInfo)): ?>
                <div id="company-info-details">
                    <p><strong><?= htmlspecialchars($companyInfo['company_name']) ?></strong></p>
                    <p><?= htmlspecialchars($companyInfo['address']) ?></p>
                    <p>Phone: <?= htmlspecialchars($companyInfo['phone']) ?></p>
                    <p>Email: <?= htmlspecialchars($companyInfo['email']) ?></p>
                </div>
                
                <p id="copyright">
                    &copy; <?= date('Y') ?> <?= htmlspecialchars($companyInfo['company_name']) ?>, All rights reserved.
                </p>
            <?php endif; ?>
        </div>

        <!-- Navigation links -->
        <nav>
            <ul>
                <li><a href="#home">HOME</a></li>
                <li><a href="#about">ABOUT</a></li>
                <li><a href="#blog">BLOG</a></li>
            </ul>
        </nav>

        <!-- Social Media Links -->
        <nav>
            <ul id="social-media-links">
                <?php foreach ($socialLinks as $platform => $url): ?>
                    <li>
                        <a href="<?= htmlspecialchars($url) ?>" target="_blank">
                            <?= htmlspecialchars(ucfirst($platform)) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>
    </footer>

    <script>
        // Remove localStorage-related JavaScript
        // Keep any animations/interactions you need
        document.querySelectorAll('.article-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
                this.style.boxShadow = '0 10px 20px rgba(0, 0, 0, 0.15)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = '';
                this.style.boxShadow = '0 5px 15px rgba(0, 0, 0, 0.1)';
            });
        });
    </script>
</body>
</html>