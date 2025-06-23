<?php
include('config.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>World Heritage Explorer</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary-color: #2A5D67;
      --accent-color: #FF7F50;
      --light-bg: #f8fafc;
      --card-shadow: rgba(0, 0, 0, 0.1);
    }
    
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    
    body {
      font-family: 'Poppins', sans-serif;
      background-color: var(--light-bg);
      color: #444;
      line-height: 1.6;
    }
    
    .navbar {
      background-color: #34495e;
      padding: 15px 40px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      color: white;
    }
    
    .navbar .logo {
      font-size: 1.8em;
      font-weight: bold;
      color: #ecf0f1;
      text-decoration: none;
    }
    
    .navbar ul {
      list-style: none;
      display: flex;
      gap: 25px;
    }
    
    .navbar ul li a {
      color: #ecf0f1;
      text-decoration: none;
      font-size: 1em;
      transition: color 0.3s;
    }
    
    .navbar ul li a:hover {
      color: #1abc9c;
    }
    
    header {
      background: linear-gradient(135deg, var(--primary-color), #1E4045);
      color: white;
      padding: 4rem 2rem;
      text-align: center;
      box-shadow: 0 4px 15px var(--card-shadow);
      margin-bottom: 2rem;
    }
    
    .header-title {
      font-weight: 600;
      font-size: 2.5rem;
      margin-bottom: 0.5rem;
      transition: transform 0.3s ease;
    }
    
    .header-title:hover {
      transform: scale(1.02);
    }
    
    .container {
      max-width: 1300px;
      margin: 0 auto;
      padding: 0 20px 4rem;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 2rem;
    }
    
    .card {
      background: white;
      border-radius: 16px;
      overflow: hidden;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      position: relative;
      cursor: pointer;
    }
    
    .card:hover {
      transform: translateY(-8px);
      box-shadow: 0 12px 25px var(--card-shadow);
    }
    
    .card img {
      width: 100%;
      height: 220px;
      object-fit: cover;
      border-bottom: 4px solid var(--accent-color);
      transition: transform 0.3s ease;
    }
    
    .card:hover img {
      transform: scale(1.03);
    }
    
    .card-content {
      padding: 1.5rem;
    }
    
    .card-title {
      color: var(--primary-color);
      font-weight: 600;
      margin-bottom: 0.75rem;
      font-size: 1.3rem;
    }
    
    .card-description {
      font-weight: 300;
      font-size: 0.95rem;
      line-height: 1.4;
      margin-bottom: 1rem;
      display: -webkit-box;
      -webkit-line-clamp: 3;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }
    
    .card-location {
      display: flex;
      align-items: center;
      color: var(--accent-color);
      font-weight: 500;
      font-size: 0.9rem;
    }
    
    .card-location::before {
      content: '📍';
      margin-right: 8px;
    }
    
    .empty-state {
      text-align: center;
      padding: 4rem;
      color: #666;
      font-size: 1.2rem;
    }
    
    @media (max-width: 768px) {
      header { padding: 3rem 1rem; }
      .header-title { font-size: 2rem; }
    }
    
    @media (max-width: 480px) {
      .card img { height: 200px; }
      .card-content { padding: 1rem; }
    }
  </style>
</head>
<body>

  <nav class="navbar">
    <a href="homepage.php" class="logo">Sampada Navigator</a>
    <ul>
      <li><a href="homepage.php">Home</a></li>
      <li><a href="MyBookings.php">Bookings</a></li>
      <li><a href="profile.php">Profile</a></li>
      <li><a href="logout.php">Logout</a></li>
    </ul>
  </nav>

  <header>
    <h1 class="header-title">World Heritage Explorer</h1>
    <p>Discover Cultural Treasures of Humanity</p>
  </header>

  <div class="container">
    <?php
      $query = "SELECT * FROM heritage";
      $result = mysqli_query($conn, $query);

      if ($result && mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
              // Ensure the image path is properly concatenated. Adjust if $row['image'] already contains uploads/
              $imageSrc = (strpos($row['image'], 'uploads/') === 0) ? $row['image'] : 'uploads/' . $row['image'];
              echo "
                  <a href='bookguide.php?heritage_id={$row['heritage_id']}' style='text-decoration: none; color: inherit;'>
                      <article class='card'>
                          <img src='{$imageSrc}' alt='" . htmlspecialchars($row['name']) . "' class='card-image'>
                          <div class='card-content'>
                              <h3 class='card-title'>" . htmlspecialchars($row['name']) . "</h3>
                              <p class='card-description'>" . htmlspecialchars($row['description']) . "</p>
                              <div class='card-location'>" . htmlspecialchars($row['location']) . "</div>
                          </div>
                      </article>
                  </a>
              ";
          }
      } else {
          echo "<div class='empty-state'>No heritage sites found. Please check back later!</div>";
      }

      mysqli_close($conn);
    ?>
  </div>

</body>
</html>
