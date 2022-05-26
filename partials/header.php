<header>
  <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
  <div class="space"></div>
  <div class="title">
    <h3>Denver Jensen's Hangman</h3>
  </div>
  <div class="user" style=''>
    <?php if (isset($_SESSION['username']) && !empty($_SESSION['username']) && $_SESSION['username'] !== '') : ?>
      <span><?php echo $_SESSION['username']; ?></span>
      <div style='padding-top: 8px;'>
        <a href="logout.php">Logout</a>
      </div>
    <?php endif; ?>
  </div>




</header>