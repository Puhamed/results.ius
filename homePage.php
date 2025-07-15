<?php
session_start();

// Initialize articles data in session to simulate simple persistence
if (!isset($_SESSION['articles'])) {
    $_SESSION['articles'] = [
        [
            'id' => 1,
            'title' => 'New Research Lab Inaugurated at the College',
            'summary' => 'The college has inaugurated a state-of-the-art research laboratory to encourage innovation.',
            'content' => 'The new research lab is equipped with latest instruments and encourages collaboration among students and faculty to foster a research-driven environment.',
            'author' => 'Admin',
            'category' => 'Research',
            'publishedAt' => '2024-06-15',
            'imageUrl' => 'https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/73bfd378-4362-4c0a-8072-f8638f4e503d.png',
            'readTime' => 4,
            'views' => 120,
        ],
        [
            'id' => 2,
            'title' => 'Annual Sports Meet to be Held Next Month',
            'summary' => 'Get ready for the yearly sports extravaganza with exciting events and competitions.',
            'content' => 'The annual sports meet will feature a variety of athletic events, encouraging participation from all students. Registrations open now.',
            'author' => 'Sports Dept.',
            'category' => 'Events',
            'publishedAt' => '2024-06-10',
            'imageUrl' => 'https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/4af3e03e-112d-4a71-b859-3a475b09df50.png',
            'readTime' => 3,
            'views' => 95,
        ],
        [
            'id' => 3,
            'title' => 'Guest Lecture on Artificial Intelligence',
            'summary' => 'Join us for a guest lecture by industry expert on AI trends and applications.',
            'content' => 'The lecture will cover recent advancements in AI, its impact on various industries, and opportunities for students.',
            'author' => 'Computer Science Dept.',
            'category' => 'Lectures',
            'publishedAt' => '2024-06-20',
            'imageUrl' => 'https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/3a9892ab-f4ed-4b1c-b19f-57051769b2f6.png',
            'readTime' => 5,
            'views' => 140,
        ],
    ];
}

// Extract articles and categories
$articles = $_SESSION['articles'];

// Extract unique categories with article counts
$categoryCounts = [];
foreach ($articles as $article) {
    $cat = $article['category'];
    if (!isset($categoryCounts[$cat])) {
        $categoryCounts[$cat] = 0;
    }
    $categoryCounts[$cat]++;
}
// Sort categories alphabetically
ksort($categoryCounts);

// Sort articles by publishedAt descending (latest first)
usort($articles, function ($a, $b) {
    return strtotime($b['publishedAt']) - strtotime($a['publishedAt']);
});

// Trending articles based on views
$trendingArticles = $articles;
usort($trendingArticles, function ($a, $b) {
    return $b['views'] - $a['views'];
});
$trendingArticles = array_slice($trendingArticles, 0, 5);

// Handle category filter from query string
$selectedCategories = [];
if (isset($_GET['categories'])) {
    $selectedCategories = explode(',', $_GET['categories']);
}
// Filter articles by selected categories (if any)
$filteredArticles = [];
if ($selectedCategories) {
    foreach ($articles as $article) {
        if (in_array($article['category'], $selectedCategories)) {
            $filteredArticles[] = $article;
        }
    }
} else {
    $filteredArticles = $articles;
}

// Pagination - simple page and page size
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$articlesPerPage = 5;
$totalArticles = count($filteredArticles);
$totalPages = ceil($totalArticles / $articlesPerPage);
$startIndex = ($page - 1) * $articlesPerPage;
$paginatedArticles = array_slice($filteredArticles, $startIndex, $articlesPerPage);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="stylesheet" type="text/css" href="style.css">

  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>College News - Home</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Material+Icons" rel="stylesheet" />
</head>
<body>
<header role="banner">
  <div class="header-inner container">
    <a href="index.php" class="logo" aria-label="College Home">College News</a>
    <nav role="navigation" aria-label="Primary navigation">
      <a href="index.php" aria-current="page">Home</a>
      <a href="manage-news.php">Manage News</a>
    </nav>
    <div class="search-bar" role="search">
      <input type="search" id="searchInput" placeholder="Search news..." aria-label="Search news" />
      <span class="material-icons" aria-hidden="true">search</span>
    </div>
  </div>
</header>
<main class="container" role="main">
  <section class="intro-section" aria-labelledby="intro-title">
    <h1 id="intro-title">Welcome to Our College</h1>
    <p>Our college is committed to excellence in education, research, and community engagement. Discover the latest news, events, and updates that make our institution vibrant and dynamic.</p>
  </section>

  <section class="articles-feed" aria-label="Latest news articles" id="articlesFeed">
    <?php if (count($paginatedArticles) === 0): ?>
      <p>No news articles found for selected categories.</p>
    <?php else: ?>
      <?php foreach ($paginatedArticles as $article): ?>
        <article class="card" tabindex="0" aria-labelledby="article-title-<?php echo $article['id']; ?>" aria-describedby="article-summary-<?php echo $article['id']; ?>">
          <img src="<?php echo htmlspecialchars($article['imageUrl']); ?>" alt="<?php echo htmlspecialchars($article['title']); ?> image" loading="lazy" />
          <div class="card-content">
            <span class="category-pill"><?php echo htmlspecialchars($article['category']); ?></span>
            <h3 id="article-title-<?php echo $article['id']; ?>"><?php echo htmlspecialchars($article['title']); ?></h3>
            <p id="article-summary-<?php echo $article['id']; ?>" class="summary"><?php echo htmlspecialchars($article['summary']); ?></p>
            <p class="meta">By <?php echo htmlspecialchars($article['author']); ?> &bull; <?php echo date('M j, Y', strtotime($article['publishedAt'])); ?> &bull; <?php echo intval($article['readTime']); ?> min read</p>
          </div>
        </article>
      <?php endforeach; ?>
      <nav class="pagination" role="navigation" aria-label="Pagination navigation">
        <form method="get" action="index.php" id="paginationForm">
          <?php foreach ($selectedCategories as $cat): ?>
            <input type="hidden" name="categories[]" value="<?php echo htmlspecialchars($cat); ?>" />
          <?php endforeach; ?>
          <button type="submit" name="page" value="<?php echo max(1, $page - 1); ?>" <?php echo $page <= 1 ? 'disabled' : ''; ?> aria-label="Previous page">
            <span class="material-icons" aria-hidden="true">chevron_left</span>
            Prev
          </button>
          <span aria-live="polite" style="padding: 0 12px; font-weight: 600;">Page <?php echo $page; ?> of <?php echo $totalPages; ?></span>
          <button type="submit" name="page" value="<?php echo min($totalPages, $page + 1); ?>" <?php echo $page >= $totalPages ? 'disabled' : ''; ?> aria-label="Next page">
            Next
            <span class="material-icons" aria-hidden="true">chevron_right</span>
          </button>
        </form>
      </nav>
    <?php endif; ?>
  </section>
  <aside class="trending" aria-label="Trending news articles">
    <h2>Trending</h2>
    <div class="trending-list">
      <?php foreach ($trendingArticles as $tarticle): ?>
      <a href="#article-title-<?php echo $tarticle['id']; ?>" class="trending-item" tabindex="0">
        <img src="<?php echo htmlspecialchars($tarticle['imageUrl']); ?>" alt="<?php echo htmlspecialchars($tarticle['title']); ?> trending image" loading="lazy" />
        <div class="trending-details">
          <h4><?php echo htmlspecialchars($tarticle['title']); ?></h4>
          <p><?php echo htmlspecialchars($tarticle['summary']); ?></p>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
  </aside>
</main>
<script>
  // Client-side search filtering (simple filter on displayed articles)
  const searchInput = document.getElementById('searchInput');
  const articlesFeed = document.getElementById('articlesFeed');

  searchInput.addEventListener('input', function() {
    const query = this.value.toLowerCase();

    const articles = articlesFeed.querySelectorAll('article.card');
    articles.forEach(article => {
      const title = article.querySelector('h3').textContent.toLowerCase();
      const summary = article.querySelector('p.summary').textContent.toLowerCase();

      if (title.includes(query) || summary.includes(query)) {
        article.style.display = '';
      } else {
        article.style.display = 'none';
      }
    });
  });
</script>
</body>
</html>

