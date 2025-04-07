<?php
require_once 'session.php';
require_once 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BlockVerse Wallet</title>
    <link rel="stylesheet" href="style4.css?v=1.1">
</head>
<body>
    <nav class="header">
        <div class="logo">
          <h1>Blockverse</h1>
        </div>
        <div class="header-item">
          <a class="home-btn" href="index.php">Home</a>
        </div>
        <div class="btn">
          <?php if (isLoggedIn()): ?>
            <span style="margin-right: 10px; color: white;">
              <?php echo htmlspecialchars(getUserName()); ?>, Balance: ₹<?php echo htmlspecialchars(getUserBalance()); ?>
            </span>
            <a href="index.php" class="login-btn">Back</a>
          <?php else: ?>
            <a href="register.php" class="signup-btn">Signup</a>
            <a href="login.php" class="login-btn">Login</a>
          <?php endif; ?>
        </div>
      </nav>
    <section class="wallet-section">
        <h1>Your Crypto Wallet</h1>
        <div class="wallet-balance">
            <h2>Balance: 0.00 BTC</h2> 
            <button id="refresh-balance">Refresh Balance</button>
        </div>
        <div class="transactions">
            <h2>Transactions</h2>
            <ul id="transaction-list">
                </ul>
        </div>
        <div class="send-crypto">
            <h2>Send Crypto</h2>
            <input type="text" id="recipient-address" placeholder="Recipient Address">
            <input type="number" id="amount" placeholder="Amount (BTC)">
            <button id="send-button">Send</button>
        </div>
        <div class="receive-crypto">
            <h2>Receive Crypto</h2>
            <span style="margin-right: 10px; color: Black;">
              Your Address: <?php echo htmlspecialchars(getUserAddress()); ?>
            </span>
            <button id="copy-address">Copy Address</button>
        </div>
    </section>
    <footer>
        <p>&copy; 2025 BlockVerse. All rights reserved.</p>
    </footer>
    <script src="wallet.js"></script>
</body>
</html>
