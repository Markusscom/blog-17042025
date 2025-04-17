<?php
$host = 'localhost'; 
$dbname = 'blog_17042025';
$username = 'bloguser_17042025';
$password = 'password';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Savienojums izveidots veiksmīgi!";
} catch (PDOException $e) {
    echo "Savienojuma kļūda: " . $e->getMessage();
}

$sql = "SELECT posts.id AS post_id, posts.title, posts.content, comments.id AS comment_id, comments.comment_text 
        FROM posts
        LEFT JOIN comments ON posts.id = comments.post_id";

$stmt = $pdo->query($sql);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
print_r($rows);
?>
