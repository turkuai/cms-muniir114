<?php
require_once 'config.php';

function getLogoUrl() {
    global $conn;
    $sql = "SELECT logo_url FROM logo_settings ORDER BY updated_at DESC LIMIT 1";
    $result = $conn->query($sql);
    return $result->num_rows > 0 ? $result->fetch_assoc()['logo_url'] : '';
}

function updateLogoUrl($url) {
    global $conn;
    $url = $conn->real_escape_string($url);
    $sql = "INSERT INTO logo_settings (logo_url) VALUES ('$url')";
    return $conn->query($sql);
}

function getFooterContent() {
    global $conn;
    $sql = "SELECT content FROM footer_content ORDER BY updated_at DESC LIMIT 1";
    $result = $conn->query($sql);
    return $result->num_rows > 0 ? $result->fetch_assoc()['content'] : '';
}

function updateFooterContent($content) {
    global $conn;
    $content = $conn->real_escape_string($content);
    $sql = "INSERT INTO footer_content (content) VALUES ('$content')";
    return $conn->query($sql);
}

function getAllSocialMedia() {
    global $conn;
    $sql = "SELECT * FROM social_media ORDER BY platform_name";
    $result = $conn->query($sql);
    $socials = [];
    while($row = $result->fetch_assoc()) {
        $socials[$row['platform_name']] = $row['profile_url'];
    }
    return $socials;
}

function addSocialMedia($name, $url) {
    global $conn;
    $name = $conn->real_escape_string($name);
    $url = $conn->real_escape_string($url);
    $sql = "INSERT INTO social_media (platform_name, profile_url) VALUES ('$name', '$url') 
            ON DUPLICATE KEY UPDATE profile_url = VALUES(profile_url)";
    return $conn->query($sql);
}

function deleteSocialMedia($name) {
    global $conn;
    $name = $conn->real_escape_string($name);
    $sql = "DELETE FROM social_media WHERE platform_name = '$name'";
    return $conn->query($sql);
}

function getAllArticles() {
    global $conn;
    $sql = "SELECT * FROM articles ORDER BY created_at DESC";
    $result = $conn->query($sql);
    $articles = [];
    while($row = $result->fetch_assoc()) {
        $articles[] = $row;
    }
    return $articles;
}

function addArticle($title, $content, $image = '', $display_location = 'home', $menu_id = null) {
    global $conn;
    $title = $conn->real_escape_string($title);
    $content = $conn->real_escape_string($content);
    $image = $conn->real_escape_string($image);
    $display_location = $conn->real_escape_string($display_location);
    $menu_id = $menu_id ? (int)$menu_id : 'NULL';
    
    $sql = "INSERT INTO articles (title, content, image_url, display_location, menu_id) 
            VALUES ('$title', '$content', '$image', '$display_location', $menu_id)";
    return $conn->query($sql);
}

function updateArticle($id, $title, $content, $image = '') {
    global $conn;
    $title = $conn->real_escape_string($title);
    $content = $conn->real_escape_string($content);
    $image = $conn->real_escape_string($image);
    $sql = "UPDATE articles SET title = '$title', content = '$content', image_url = '$image' WHERE id = $id";
    return $conn->query($sql);
}

function deleteArticle($id) {
    global $conn;
    $sql = "DELETE FROM articles WHERE id = $id";
    return $conn->query($sql);
}

function getCompanyInfo() {
    global $conn;
    $sql = "SELECT * FROM company_info ORDER BY updated_at DESC LIMIT 1";
    $result = $conn->query($sql);
    return $result->num_rows > 0 ? $result->fetch_assoc() : [];
}

function updateCompanyInfo($name, $address, $phone, $email) {
    global $conn;
    $name = $conn->real_escape_string($name);
    $address = $conn->real_escape_string($address);
    $phone = $conn->real_escape_string($phone);
    $email = $conn->real_escape_string($email);
    $sql = "INSERT INTO company_info (company_name, address, phone, email) 
            VALUES ('$name', '$address', '$phone', '$email')
            ON DUPLICATE KEY UPDATE 
            company_name = VALUES(company_name),
            address = VALUES(address),
            phone = VALUES(phone),
            email = VALUES(email)";
    return $conn->query($sql);
}
// Add these new functions to your existing functions.php

// Menu Management Functions
function getAllMenus() {
    global $conn;
    $sql = "SELECT * FROM menus ORDER BY display_order";
    $result = $conn->query($sql);
    $menus = [];
    while($row = $result->fetch_assoc()) {
        $menus[] = $row;
    }
    return $menus;
}

function getMenuById($id) {
    global $conn;
    $sql = "SELECT * FROM menus WHERE id = $id";
    $result = $conn->query($sql);
    return $result->num_rows > 0 ? $result->fetch_assoc() : null;
}

function addMenu($title, $url, $is_article = 0, $display_order = 0) {
    global $conn;
    $title = $conn->real_escape_string($title);
    $url = $conn->real_escape_string($url);
    $is_article = (int)$is_article;
    $display_order = (int)$display_order;
    
    $sql = "INSERT INTO menus (title, url, is_article, display_order) 
            VALUES ('$title', '$url', $is_article, $display_order)";
    return $conn->query($sql);
}

function updateMenu($id, $title, $url, $is_article, $display_order) {
    global $conn;
    $title = $conn->real_escape_string($title);
    $url = $conn->real_escape_string($url);
    $is_article = (int)$is_article;
    $display_order = (int)$display_order;
    
    $sql = "UPDATE menus SET 
            title = '$title', 
            url = '$url', 
            is_article = $is_article, 
            display_order = $display_order 
            WHERE id = $id";
    return $conn->query($sql);
}

function deleteMenu($id) {
    global $conn;
    $sql = "DELETE FROM menus WHERE id = $id";
    return $conn->query($sql);
}

// Article-specific functions
function getArticleByUrl($url) {
    global $conn;
    $url = $conn->real_escape_string($url);
    $sql = "SELECT * FROM articles WHERE id = (SELECT article_id FROM menus WHERE url = '$url' AND is_article = 1)";
    $result = $conn->query($sql);
    return $result->num_rows > 0 ? $result->fetch_assoc() : null;
}
function getArticleById($id) {
    global $conn;
    $sql = "SELECT * FROM articles WHERE id = $id";
    $result = $conn->query($sql);
    return $result->num_rows > 0 ? $result->fetch_assoc() : null;
}

function getMenuByArticleId($articleId) {
    global $conn;
    $sql = "SELECT * FROM menus WHERE url LIKE '%article.php?url=$articleId' OR url LIKE '%?id=$articleId'";
    $result = $conn->query($sql);
    return $result->num_rows > 0 ? $result->fetch_assoc() : null;
}
function getArticlesByMenu($menu_id) {
    global $conn;
    $sql = "SELECT * FROM articles WHERE menu_id = $menu_id ORDER BY created_at DESC";
    $result = $conn->query($sql);
    $articles = [];
    while($row = $result->fetch_assoc()) {
        $articles[] = $row;
    }
    return $articles;
}
function getHomepageArticles() {
    global $conn;
    $sql = "SELECT * FROM articles WHERE display_location = 'home' ORDER BY created_at DESC";
    $result = $conn->query($sql);
    $articles = [];
    while($row = $result->fetch_assoc()) {
        $articles[] = $row;
    }
    return $articles;
}

?>