<?php
session_start();
require_once "config.php";

// Initialize cart and wishlist
if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
if (!isset($_SESSION['wishlist'])) $_SESSION['wishlist'] = [];

// Add to cart
if (isset($_GET['add_to_cart'])) {
  $id = $_GET['add_to_cart'];
  $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + 1;
  header("Location: gallery.php");
  exit();
}

// Remove from cart
if (isset($_GET['remove_from_cart'])) {
  $id = $_GET['remove_from_cart'];
  unset($_SESSION['cart'][$id]);
  header("Location: gallery.php");
  exit();
}

// Add to wishlist
if (isset($_GET['add_to_wishlist'])) {
  $id = $_GET['add_to_wishlist'];
  $_SESSION['wishlist'][$id] = true;
  header("Location: gallery.php");
  exit();
}

// Checkout
if (isset($_GET['checkout'])) {
  $total = 0;
  foreach ($_SESSION['cart'] as $id => $qty) {
    $res = $conn->query("SELECT * FROM products WHERE id=$id");
    if ($row = $res->fetch_assoc()) {
      $total += $row['price'] * $qty;
    }
  }
  $message = "Thank you! Your order for ₹$total has been placed.";
  echo "<script>alert('$message');</script>";
  $_SESSION['cart'] = [];
  header("Refresh:1; url=gallery.php");
  exit();
}

$search = $_GET['search'] ?? '';

$sql = "SELECT * FROM products 
        WHERE status='active' 
        AND (name LIKE '%$search%' OR category LIKE '%$search%')";

$result = $conn->query($sql);

if (!$result) {
    die("SQL Error: " . $conn->error);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>KapdaBazar - Gallery</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f0f2f5;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .navbar-brand {
      font-weight: bold;
      font-size: 1.5rem;
      color: #dc3545 !important;
    }
    .card-img-top {
      height: 200px;
      object-fit: cover;
    }
    .btn-custom {
      border-radius: 5px;
    }
    h2 {
      margin-top: 1rem;
      color: #dc3545;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
  <div class="container">
    <a class="navbar-brand" href="index.php">KapdaBazar</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarContent">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php#about">About</a></li>
        <li class="nav-item"><a class="nav-link active" href="gallery.php">Shop Now</a></li>
        <li class="nav-item"><a class="nav-link" href="cart.php">Cart</a></li> <!-- ✅ Added Cart -->
        <li class="nav-item"><a class="nav-link" href="index.php#contact">Contact</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container py-4">
  <h2 class="text-center mb-4">🛍️ Product Gallery</h2>

  <form method="GET" class="mb-4">
    <div class="input-group">
      <input type="text" name="search" class="form-control" placeholder="Search products..." value="<?php echo htmlspecialchars($search); ?>">
      <button class="btn btn-outline-secondary">Search</button>
    </div>
  </form>

  <div class="row">
  

    <?php
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
    while ($row = $result->fetch_assoc()):
      $img = $imageMap[$row['name']] ?? "default.jpg";
    ?>
      <div class="col-md-3 mb-4">
        <div class="card h-100 shadow-sm">
          <img src="images/<?php echo $img; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row['name']); ?>">
          <div class="card-body">
            <h5 class="card-title"><?php echo $row['name']; ?></h5>
            <p class="card-text">₹<?php echo $row['price']; ?></p>
            <p class="small text-muted">Category: <?php echo $row['category']; ?> | Tag: <?php echo $row['tag']; ?></p>
            <a href="cart.php?add_to_cart=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary btn-custom">Add to Cart</a>
            <a href="?add_to_wishlist=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger btn-custom">❤️ Wishlist</a>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  </div>

  <hr>


  <hr>
  <h4>❤️ Your Wishlist</h4>
  <?php if (!empty($_SESSION['wishlist'])): ?>
    <ul class="list-group">
      <?php foreach ($_SESSION['wishlist'] as $id => $_):
        $res = $conn->query("SELECT * FROM products WHERE id=$id");
        if ($row = $res->fetch_assoc()):
      ?>
        <li class="list-group-item d-flex justify-content-between">
          <?php echo $row['name']; ?> <span>₹<?php echo $row['price']; ?></span>
        </li>
      <?php endif; endforeach; ?>
    </ul>
  <?php else: echo "<p>No items in wishlist.</p>"; endif; ?>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
