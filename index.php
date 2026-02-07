<?php
session_start();
require_once "config.php";

// Fetch a few featured products (limit to 4)
$featured = $conn->query("SELECT * FROM products WHERE status='active' ORDER BY RAND() LIMIT 4");

// Image mapping
$imageMap = [
  "White Shirt" => "shirt.jpeg",
  "Casual Shirt" => "shirt4.jpeg",
  "Formal Shirt" => "shirt3.jpeg",
  "Tee" => "tshirt.jpeg",
  "Butterfly Tee" => "tshirt3.webp",
  "Pastel T-Shirt" => "tshirt4.webp",
  "Pink Dress" => "dress2.jpg",
  "Floral Dress" => "dress3.jpg",
  "Off-Shoulder Dress" => "dress4.jpg",
  "Casual Summer Dress" => "dress.jpg",
  "Pants" => "pent2.jpeg",
  "Cargo Pants" => "pent.webp",
  "Jeans Pants" => "pent3.png",
  "Comfy Lounge Pants" => "pent4.jpeg"
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>KapdaBazar - Home</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f0f2f5;
    }
    .navbar-brand {
      font-weight: bold;
      font-size: 1.5rem;
      color: #dc3545;
    }
    .hero-section {
      background: url('images/banner.png') no-repeat center center/cover;
      height: 75vh;
      color: white;
      text-shadow: 2px 2px 5px #000;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      text-align: center;
    }
    .hero-section h1 { font-size: 3rem; }
    .hero-section p { font-size: 1.2rem; }
    .btn-hero {
      margin: 10px;
      padding: 10px 20px;
      font-size: 1.1rem;
      border-radius: 25px;
    }
    footer {
      background-color: #343a40;
      color: #ffffff;
      padding: 20px 0;
    }
    .featured-title {
      font-weight: bold;
      text-align: center;
      margin: 60px 0 20px;
    }
    .card-img-top {
      height: 220px;
      object-fit: cover;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
  <div class="container">
    <a class="navbar-brand" href="#">KapdaBazar</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarContent">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php#about">About</a></li>
        <li class="nav-item"><a class="nav-link" href="gallery.php">Shop Now</a></li>
        <li class="nav-item"><a class="nav-link" href="cart.php">Cart</a></li> <!-- ✅ Added Cart -->
        <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- Hero Section -->
<div class="hero-section">
  <h1>Welcome to KapdaBazar</h1>
  <p>Your one-stop shop for stylish clothes!</p>
  <div>
    <a href="gallery.php" class="btn btn-danger btn-hero">Browse Gallery</a>
    <a href="#about" class="btn btn-outline-light btn-hero">Learn More</a>
  </div>
</div>

<!-- About Section -->
<section class="container py-5" id="about">
  <div class="row align-items-center bg-white shadow p-4 rounded">
    <div class="col-md-6 mb-3 mb-md-0">
      <img src="images/about.jpg" class="img-fluid rounded" alt="About KapdaBazar">
    </div>
    <div class="col-md-6">
      <h2 class="text-center mb-4">About Us</h2>
      <p class="lead text-center">KapdaBazar is your favorite destination for trendy shirts, dresses, pants, and tees. We offer high-quality products with smooth online shopping and fast delivery.</p>
    </div>
  </div>
</section>

<!-- Featured Products -->
<div class="container">
  <h2 class="featured-title">🌟 Featured Products</h2>
  <div class="row">
    <?php while($row = $featured->fetch_assoc()):
      $img = $imageMap[$row['name']] ?? "default.jpg";
    ?>
      <div class="col-md-3 mb-4">
        <div class="card h-100 shadow-sm">
          <a href="gallery.php">
            <img src="images/<?php echo $img; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row['name']); ?>">
          </a>
          <div class="card-body text-center">
            <h5 class="card-title mb-1"><?php echo $row['name']; ?></h5>
            <p class="text-muted small">₹<?php echo $row['price']; ?> | <?php echo $row['category']; ?></p>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</div>

<!-- Contact Section -->
<section class="container py-5" id="contact">
  <h2 class="text-center mb-4">Contact Us</h2>

  <form class="row g-3" id="contactForm">
    <div class="col-md-6">
      <label class="form-label">Name</label>
      <input type="text" class="form-control" name="name" placeholder="Your Name" required>
    </div>
    <div class="col-md-6">
      <label class="form-label">Email</label>
      <input type="email" class="form-control" name="email" placeholder="your@example.com" required>
    </div>
    <div class="col-12">
      <label class="form-label">Message</label>
      <textarea class="form-control" name="message" rows="4" placeholder="Write your message here..." required></textarea>
    </div>
    <div class="col-12 text-center">
      <button type="submit" class="btn btn-primary mt-3">Send Message</button>
    </div>
  </form>
</section>

<!-- Thank You Popup Script -->
<script>
  document.getElementById('contactForm').addEventListener('submit', function(e) {
    e.preventDefault(); // prevent actual form submission
    alert("Thank you for contacting us!");
    this.reset(); // clear form
  });
</script>

<!-- Footer -->
<footer class="text-center mt-5">
  <div class="container">
    <p>&copy; <?php echo date("Y"); ?> KapdaBazar. All rights reserved.</p>
    <p>Follow us on:
      <a href="#" class="text-light mx-2">Facebook</a> |
      <a href="#" class="text-light mx-2">Instagram</a> |
      <a href="#" class="text-light mx-2">Twitter</a>
    </p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
