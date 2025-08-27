<?php
include 'db.php';
 
$category = isset($_GET['cat']) ? $_GET['cat'] : '';
if ($category) {
    $query = "SELECT * FROM news WHERE category = ? ORDER BY publish_date DESC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $category);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM news ORDER BY publish_date DESC");
}
 
// Fetch categories for navigation
$categories_query = "SELECT DISTINCT category FROM news";
$categories_result = $conn->query($categories_query);
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NewsWave - <?php echo htmlspecialchars($category); ?></title>
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
            max-width: 1200px;
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
 
        .news-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }
 
        .news-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
 
        .news-card:hover {
            transform: translateY(-5px);
        }
 
        .news-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
 
        .news-card-content {
            padding: 15px;
        }
 
        .news-card h3 {
            margin-bottom: 10px;
            color: #b22222;
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
        <h1>NewsWave - <?php echo htmlspecialchars($category); ?></h1>
    </header>
 
    <nav>
        <a href="index.php">Home</a>
        <?php while ($cat = $categories_result->fetch_assoc()): ?>
            <a href="category.php?cat=<?php echo urlencode($cat['category']); ?>">
                <?php echo htmlspecialchars($cat['category']); ?>
            </a>
        <?php endwhile; ?>
    </nav>
 
    <div class="container">
        <h2><?php echo htmlspecialchars($category); ?> News</h2>
        <div class="news-grid">
            <?php while ($news = $result->fetch_assoc()): ?>
                <div class="news-card">
                    <img src="<?php echo htmlspecialchars($news['image_url']); ?>" alt="News Image">
                    <div class="news-card-content">
                        <h3><?php echo htmlspecialchars($news['title']); ?></h3>
                        <p><?php echo substr(htmlspecialchars($news['content']), 0, 100) . '...'; ?></p>
                        <a href="article.php?id=<?php echo $news['id']; ?>" onclick="redirectToArticle(<?php echo $news['id']; ?>)">Read More</a>
                    </div>
                </div>
            <?php endwhile; ?>
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
