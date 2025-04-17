<?php
class Post {
    public $id;
    public $title;
    public $content;
    public $comments = [];

    public function __construct($id, $title, $content) {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
    }

    public function addComment($comment_id, $comment_text) {
        $this->comments[] = ['id' => $comment_id, 'comment_text' => $comment_text];
    }
}

class Comment {
    public $id;
    public $comment_text;

    public function __construct($id, $comment_text) {
        $this->id = $id;
        $this->comment_text = $comment_text;
    }
}

$host = 'localhost'; 
$dbname = 'blog_17042025';
$username = 'bloguser_17042025';
$password = 'password';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Savienojuma kļūda: " . $e->getMessage();
}

$sql = "SELECT posts.id AS post_id, posts.title, posts.content, comments.id AS comment_id, comments.comment_text 
        FROM posts
        LEFT JOIN comments ON posts.id = comments.post_id";

$stmt = $pdo->query($sql);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$posts = [];

foreach ($rows as $row) {
    $post_id = $row['post_id'];

    if (!isset($posts[$post_id])) {
        $posts[$post_id] = new Post($post_id, $row['title'], $row['content']);
    }

    if ($row['comment_id']) {
        $posts[$post_id]->addComment($row['comment_id'], $row['comment_text']);
    }
}

echo "<ul>";
foreach ($posts as $post) {
    echo "<li>";
    echo "<strong>" . htmlspecialchars($post->title) . "</strong><br>";
    echo "<p>" . nl2br(htmlspecialchars($post->content)) . "</p>";

    if (!empty($post->comments)) {
        echo "<ul>";
        foreach ($post->comments as $comment) {
            echo "<li>" . htmlspecialchars($comment['comment_text']) . "</li>";
        }
        echo "</ul>";
    }

    echo "</li>";
}
echo "</ul>";
?>
