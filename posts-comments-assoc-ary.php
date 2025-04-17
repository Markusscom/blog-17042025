<?php
$host = 'localhost'; 
$dbname = 'blog_17042025';
$username = 'bloguser_17042025';
$password = 'password';

// Pieslēgšanās datubāzei
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Savienojuma kļūda: " . $e->getMessage();
}

// SQL vaicājums
$sql = "SELECT p.id AS post_id, p.title, p.content, c.id AS comment_id, c.comment_text 
        FROM posts p
        LEFT JOIN comments c ON p.id = c.post_id";

$stmt = $pdo->query($sql);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$posts = [];

foreach ($rows as $row) {
    $post_id = $row['post_id'];

    if (!isset($posts[$post_id])) {
        $posts[$post_id] = [
            'id' => $post_id,
            'title' => $row['title'],
            'content' => $row['content'],
            'comments' => []
        ];
    }

    if ($row['comment_id']) {
        $posts[$post_id]['comments'][] = [
            'id' => $row['comment_id'],
            'comment_text' => $row['comment_text']
        ];
    }
}

echo "<ul>";
foreach ($posts as $post) {
    echo "<li>";
    echo "<strong>" . htmlspecialchars($post['title']) . "</strong><br>";
    echo "<p>" . nl2br(htmlspecialchars($post['content'])) . "</p>";

    if (!empty($post['comments'])) {
        echo "<ul>";
        foreach ($post['comments'] as $comment) {
            echo "<li>" . htmlspecialchars($comment['comment_text']) . "</li>";
        }
        echo "</ul>";
    }

    echo "</li>";
}
echo "</ul>";
?>