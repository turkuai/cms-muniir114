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

function addArticle($title, $content, $image = '') {
    global $conn;
    $title = $conn->real_escape_string($title);
    $content = $conn->real_escape_string($content);
    $image = $conn->real_escape_string($image);
    $sql = "INSERT INTO articles (title, content, image_url) VALUES ('$title', '$content', '$image')";
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
?>