<?php
include 'db.php';
 
$article_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$query = "SELECT * FROM news WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $article_id);
$stmt->execute();
$article = $stmt->get_result()->fetch_assoc();
 
// Fetch related articles
$related_query = "SELECT * FROM news WHERE category = ? AND id != ? ORDER BY publish_date DESC LIMIT 3";
$related_stmt = $conn->prepare($related_query);
$related_stmt->bind_param("si", $article['category'], $article_id);
$related_stmt->execute();
$related_result = $related_stmt->get_result();
 
// Fetch categories for navigation
$categories_query = "SELECT DISTINCT category FROM news";
$categories_result = $conn->query($categories_query);
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NewsWave - <?php echo htmlspecialchars($article['title']); ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
 
        body {
            background-color: #f4f4f4;
            color: #333;
        }
 
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
 
        header {
            background-color: #b22222;
            color: white;
            padding: 20px;
            text-align: center;
        }
 
        header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }
 
        nav {
            background-color: #333;
            padding: 10px;
        }
 
        nav a {
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            margin: 0 10px;
            transition: background-color 0.3s;
        }
 
        nav a:hover {
            background-color: #555;
            border-radius: 5px;
        }
 
        .article-content {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin: 20px 0;
        }
 
        .article-content img {
            width: 100%;
            max-height: 400px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 20px;
        }
 
        .article-content h2 {
            color: #b22222;
            margin-bottom: 20px;
        }
 
        .article-content p {
            line-height: 1.6;
            margin-bottom: 15px;
        }
 
        .related-news {
            margin: 20px 0;
        }
 
        .related-news h3 {
            color: #b22222;
            margin-bottom: 20px;
        }
 
        .related-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
 
        .related-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
 
        .related-card:hover {
            transform: translateY(-5px);
        }
 
        .related-card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }
 
        .related-card-content {
            padding: 10px;
        }
 
        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 20px;
        }
 
        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }
 
            header h1 {
                font-size: 1.8em;
            }
 
            nav a {
                display: block;
                margin: 5px 0;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>NewsWave</h1>
    </header>
 
    <nav>
        <a href="index.php">Home</a>
        <?php while ($category = $categories_result->fetch_assoc()): ?>
            <a href="category.php?cat=<?php echo urlencode($category['category']); ?>">
                <?php echo htmlspecialchars($category['category']); ?>
            </a>
        <?php endwhile; ?>
    </nav>
 
    <div class="container">
        <div class="article-content">
            <h2><?php echo htmlspecialchars($article['title']); ?></h2>
            <img src="<?php echo htmlspecialchars($article['image_url']); ?>" alt="Article Image">
            <p><?php echo nl2br(htmlspecialchars($article['content'])); ?></p>
            <p><strong>Published: </strong><?php echo $article['publish_date']; ?></p>
        </div>
 
        <div class="related-news">
            <h3>Related News</h3>
            <div class="related-grid">
                <?php while ($related = $related_result->fetch_assoc()): ?>
                    <div class="related-card">
                        <img src="<?php echo htmlspecialchars($related['image_url']); ?>" alt="Related News">
                        <div class="related-card-content">
                            <h4><?php echo htmlspecialchars($related['title']); ?></h4>
                            <a href="article.php?id=<?php echo $related['id']; ?>" onclick="redirectToArticle(<?php echo $related['id']; ?>)">Read More</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
 
    <footer>
        <p>&copy; 2025 NewsWave. All rights reserved.</p>
    </footer>
 
    <script>
        function redirectToArticle(id) {
            window.location.href = 'article.php?id=' + id;
        }
    </script>
</body>
</html>
<?php $conn->close(); ?>
