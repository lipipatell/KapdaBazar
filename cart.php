<?php
session_start();
require_once "config.php";

// Initialize cart
if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

// Add to cart
if (isset($_GET['add_to_cart'])) {
  $id = $_GET['add_to_cart'];
  $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + 1;
  header("Location: cart.php");
  exit();
}

// Remove from cart
if (isset($_GET['remove_from_cart'])) {
  $id = $_GET['remove_from_cart'];
  unset($_SESSION['cart'][$id]);
  header("Location: cart.php");
  exit();
}

// Handle submit
$showSuccessPopup = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_order'])) {
  $_SESSION['cart'] = []; // Clear cart
  $showSuccessPopup = true; // Show success message
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Your Cart - KapdaBazar</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
    .navbar-brand {
      font-weight: bold;
      font-size: 1.5rem;
      color: #dc3545;
    }
</style>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="index.php">KapdaBazar</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php#about">About</a></li>
        <li class="nav-item"><a class="nav-link" href="gallery.php">Shop Now</a></li>
        <li class="nav-item"><a class="nav-link active" href="cart.php">Cart</a></li>
        <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container py-4">
  <h2 class="text-center mb-4">🛒 Your Shopping Cart</h2>

  <?php if (!empty($_SESSION['cart'])): $total = 0; ?>
    <form method="POST">
      <table class="table table-bordered table-hover">
        <thead class="table-dark">
          <tr><th>Product</th><th>Qty</th><th>Price</th><th>Remove</th></tr>
        </thead>
        <tbody>
          <?php foreach ($_SESSION['cart'] as $id => $qty):
            $res = $conn->query("SELECT * FROM products WHERE id=$id");
            if ($row = $res->fetch_assoc()):
              $lineTotal = $row['price'] * $qty;
              $total += $lineTotal;
          ?>
            <tr>
              <td><?php echo $row['name']; ?></td>
              <td><?php echo $qty; ?></td>
              <td>₹<?php echo $lineTotal; ?></td>
              <td><a href="?remove_from_cart=<?php echo $id; ?>" class="btn btn-sm btn-danger">Remove</a></td>
            </tr>
          <?php endif; endforeach; ?>
        </tbody>
        <tfoot>
          <tr class="table-secondary"><th colspan="2">Total</th><th colspan="2">₹<?php echo $total; ?></th></tr>
        </tfoot>
      </table>

      <!-- Submit & Back to Shop Buttons -->
      <div class="text-center">
        <button type="submit" name="submit_order" class="btn btn-primary">Submit Order</button>
        <a href="gallery.php" class="btn btn-outline-secondary ms-3">Back to Shop</a>
      </div>
    </form>
  <?php else: ?>
    <p class="text-center">Your cart is empty. <a href="gallery.php">Shop Now</a></p>
  <?php endif; ?>
</div>

<!-- Success Popup -->
<?php if ($showSuccessPopup): ?>
<script>
  alert("Order added successfully!");
  window.location.href = "gallery.php"; // Redirect after popup
</script>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
