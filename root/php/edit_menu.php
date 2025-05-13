<?php
require_once 'functions.php';

if (!isset($_GET['id'])) {
    header("Location: admin.php");
    exit();
}

$menuId = (int)$_GET['id'];
$menu = getMenuById($menuId);

if (!$menu) {
    header("Location: admin.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_menu') {
    $title = $_POST['menu_title'];
    $url = $_POST['menu_url'];
    $isArticle = isset($_POST['is_article']) ? 1 : 0;
    $order = (int)$_POST['menu_order'];
    
    if (updateMenu($menuId, $title, $url, $isArticle, $order)) {
        header("Location: admin.php?success=Menu+updated+successfully");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Menu Item</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <?php include 'admin_header.php'; ?>
    
    <main class="admin-main active">
        <div class="card">
            <div class="card-header">
                <h3><i>✏️</i> Edit Menu Item</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="edit_menu.php?id=<?= $menuId ?>">
                    <input type="hidden" name="action" value="update_menu">
                    <div class="form-group">
                        <label for="menu-title" class="form-label">Menu Title</label>
                        <input type="text" id="menu-title" name="menu_title" class="form-control" 
                               value="<?= htmlspecialchars($menu['title']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="menu-url" class="form-label">URL</label>
                        <input type="text" id="menu-url" name="menu_url" class="form-control" 
                               value="<?= htmlspecialchars($menu['url']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="menu-order" class="form-label">Display Order</label>
                        <input type="number" id="menu-order" name="menu_order" class="form-control" 
                               value="<?= $menu['display_order'] ?>" required>
                    </div>
                    <div class="form-group form-check">
                        <input type="checkbox" id="is-article" name="is_article" class="form-check-input" 
                               <?= $menu['is_article'] ? 'checked' : '' ?>>
                        <label for="is-article" class="form-check-label">Is this an article page?</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Menu Item</button>
                    <a href="admin.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </main>
    
    <script src="admin.js"></script>
</body>
</html>