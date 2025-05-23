<?php
require_once 'functions.php';

// Check if user is logged in
session_start();
if (isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit();
}

$article = [];
if (isset($_GET['id'])) {
    $articleId = (int)$_GET['id'];
    $article = getArticleById($articleId); // You'll need to add this function to functions.php
    
    if (!$article) {
        header("Location: admin.php");
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $articleId = (int)$_POST['article_id'];
    $title = $_POST['article_title'];
    $content = $_POST['article_content'];
    $image = $_POST['article_image'];
    
    updateArticle($articleId, $title, $content, $image);
    header("Location: admin.php?success=Article+updated+successfully");
    exit();
}

// Add this function to your functions.php if not already present
/*
function getArticleById($id) {
    global $conn;
    $sql = "SELECT * FROM articles WHERE id = $id";
    $result = $conn->query($sql);
    return $result->num_rows > 0 ? $result->fetch_assoc() : null;
}
*/
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Article</title>
    <style>
        /* Use the same styles as admin.php */
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
        }

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

        .container {
            max-width: 800px;
            margin: 90px auto 20px;
            padding: 20px;
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
        }
    </style>
</head>
<body>
    <!-- Admin Header -->
    <header class="admin-header">
        <h1><i>📝</i> Edit Article</h1>
        <nav>
            <a href="admin.php" style="color: white; text-decoration: none;">Back to Admin</a>
        </nav>
    </header>

    <div class="container">
        <form method="POST" action="edit_article.php">
            <input type="hidden" name="article_id" value="<?= $article['id'] ?>">
            
            <div class="form-group">
                <label for="article-title" class="form-label">Article Title</label>
                <input type="text" id="article-title" name="article_title" class="form-control" 
                       placeholder="Enter title" value="<?= htmlspecialchars($article['title']) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="article-content" class="form-label">Content</label>
                <textarea id="article-content" name="article_content" class="form-control" 
                          placeholder="Enter content" required><?= htmlspecialchars($article['content']) ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="article-image" class="form-label">Image URL</label>
                <input type="text" id="article-image" name="article_image" class="form-control" 
                       placeholder="Enter image URL" value="<?= htmlspecialchars($article['image_url']) ?>">
            </div>
            
            <button type="submit" class="btn btn-primary">Update Article</button>
            <a href="admin.php" class="btn" style="margin-left: 10px;">Cancel</a>
            
            <?php if (isset($article['id'])): ?>
            <div class="form-group" style="margin-top: 30px;">
                <h3>Menu Link</h3>
                <?php
                // Check if this article has a menu link
                $menuLink = getMenuByArticleId($article['id']); // Add this function to functions.php
                if ($menuLink): ?>
                    <p>This article is linked to menu: <strong><?= htmlspecialchars($menuLink['title']) ?></strong></p>
                    <a href="edit_menu.php?id=<?= $menuLink['id'] ?>" class="btn btn-warning">Edit Menu Link</a>
                <?php else: ?>
                    <p>This article is not currently linked in the menu.</p>
                    <a href="admin.php#menus" class="btn btn-success">Create Menu Link</a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>