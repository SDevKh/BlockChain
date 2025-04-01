<?php
require_once 'session.php';
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['register'])) {
        $name = $_POST['name'];
        $mobile = $_POST['mobile'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $gender = $_POST['gender'];

        $stmt = $pdo->prepare("INSERT INTO users (name, mobile, email, password, gender) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $mobile, $email, $password, $gender]);
        header('Location: login.php');
    } elseif (isset($_POST['login'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Debugging: Log user_id being set
            error_log("Setting session user_id: " . $user['id']);
            $_SESSION['user_id'] = $user['id'];

            header('Location: index.php');
        } else {
            $error = "Invalid email or password";
        }
    } elseif (isset($_POST['contact'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $message = $_POST['message'];

        $stmt = $pdo->prepare("INSERT INTO contacts (name, email, phone, message) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $email, $phone, $message]);
        header('Location: index.php');
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BlockVerse</title>
    <link rel="stylesheet" href="style.css?v=1.1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400..900&family=Exo:ital,wght@0,100..900;1,100..900&family=Fredoka:wght@300..700&family=Mulish:ital,wght@0,200..1000;1,200..1000&family=Rowdies:wght@300;400;700&display=swap" rel="stylesheet">
</head>
<body>
<nav class="header">
  <div class="logo">
    <h1>BlockVerse</h1>
  </div>
  <div class="header-item">
    <a class="home-btn" href="wallet.php">Wallet</a>
    <button id="product-btn" class="product-btn">Product</button>
    <button id="contact-btn">Contact Us</button>
  </div>

  <div class="btn">
    <?php if (isLoggedIn()): ?>
      <span style="margin-right: 10px; color: white;">
        Welcome <?php echo htmlspecialchars(getUserName()); ?>
      </span>
      <a href="logout.php" class="login-btn">Logout</a>
    <?php else: ?>
      <a href="register.php" class="signup-btn">Signup</a>
      <a href="login.php" class="login-btn">Login</a>
    <?php endif; ?>
  </div>
</nav>

<section class="home">
  <div class="main-sec">
    <div class="ball"><img src="ics/bitcoin.png"> </div>
    <div class="left">
      <div class="txt">
        <p style="font-size: 2vw; background: -webkit-linear-gradient(#fb007e, #e400ff); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 900;">Get New Solution</p>
        <p style="font-size: 11vw; font-family: Kanit;">Blockchain</p>
        <div class="inline">
        <button class="button1">Technology for Business</button>
        <p>Empowering the future with trasparent, secure, and decentralized technology.<button id="button" class="button">Get Started Today &nbsp;&nbsp     ➔</button></p>
        </div>
      </div>
    </div>
  </div>
</section>
<section class="pay-sec">
  <div class="txt">
    <h2>ABOUT BLOCKCHAIN</h2>
    <h1 style="font-size: 4vw;">Why Blockchain?</h1>
    <P>Decentralized, secure, and transparent - The Future of technology.</P>
  </div>
  <div class="product">
    <div id="product-modal" class="prodmodal">
      <span class="close1">&times;</span>
      <div class="products1">
      <div class="liq">
      <h2>Liquid Network</h2>
       <p> Bitcoin layer-2 for digital asset issuance.</p>
       <a style="color: rgb(255, 255, 255); margin-top: 5vh;" href="pricing.html" class="product-btn">
        Get Started
    </a>
      </div>
        <div class="amp">
        <h2>Blockstream AMP</h2>
        <p>An API to issue and manage digital assets on the Liquid Network.</p>
        <a style="color: rgb(255, 255, 255);" href="Blockversepricing.html" class="product-btn">
          Get Started
      </a>
      </div>
        <div class="core">
        <h2> Lightning</h2>
        <p>Our own implementation of the Lightning protocol.</p>
        <a style="color: rgb(255, 255, 255);"href="Blockversepricing.html" class="product-btn">
          Get Started
      </a>
      </div>
      </div>
      <div class="products2">
        <div class="feed">
       <h2> Cryptocurrency Data Feed</h2>
        <p>Real-time and historical cryptocurrency trade data.</p>
        <a style="color: rgb(255, 255, 255);"href="Blockversepricing.html" class="product-btn">
          Get Started
      </a>
      </div>
        <div class="satellite">
        <h2>Blockstream Satellite</h2>
        <p>The Bitcoin blockchain, delivered from space.</p>
        <a style="color: rgb(255, 255, 255);"href="Blockversepricing.html" class="product-btn">
          Get Started
      </a>
      </div>
        <div class="elements">
        <h2>Elements</h2>
        <p>An open-source, sidechain-capable blockchain platform.</p>
        <a style="color: rgb(255, 255, 255);"href="Blockversepricing.html" class="product-btn">
          Get Started
      </a>
      </div>

    </div>
    </div>
  </div>
  <div id="signup-modal" class="modal2">
    <div class="modal-signup">
    <span class="close2">&times;</span>
    <h1 class="head">Signup</h1>
    <form action="index.php" method="POST">
            <input type="text" name="name" placeholder="Name" required>
            <input type="text" name="mobile" placeholder="Mobile No." required>
            <input type="email" name="email" placeholder="Email">
            <input type="password" name="password" placeholder="Password" required>
            <div class="radio">
            <input style="margin: 0 10px;" type="radio" name="gender" value="Male">Male
            <input style="margin: 0 10px;" type="radio" name="gender" value="Female">Female
          </div>
            <input type="hidden" name="register" value="1">
            <input type="submit" value="Register">
     </form>
       </div>
  </div>
  <section id="login-modal" class="modal3">
    <div class="form-box">
        <div class="form-value">
          <span class="close3">&times;</span>
            <form action="index.php" method="POST">
                <h2>Login</h2>
                <div class="inputbox">
                    <ion-icon name="mail-outline"></ion-icon>
                    <input type="email" name="email" autocomplete="email" required>

                    <label for="">Email</label>
                </div>
                <div class="inputbox">
                    <ion-icon name="lock-closed-outline"></ion-icon>
                    <input type="password" name="password" autocomplete="current-password" required>

                    <label for="">Password</label>
                </div>
                <div class="forget">
                    <label for=""><input type="checkbox" name="remember">Remember me <a href="#">Forget Password</a></label>
                   

                </div>
                <input type="hidden" name="login" value="1">
                <button type="submit" class="loginbtn">Login</button>
                <div class="register">
                    <p>Don't have account <a href="register.php">Register</a></p>
                </div>
            </form>
        </div>
    </div>
</section>
  <div id="contact-modal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <h1 class="head">Get in Touch</h1>
      <p style="width: 80%; font-family: Cinzel; font-weight: 900;">We'd love to hear from you. Whether you have a question, a project in mind, or just want to say hello, please don't hesitate to reach out.</p>
      <form action="index.php" method="POST">
        <input type="text" name="name" placeholder="Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="phone" placeholder="Phone Number" required>
        <textarea name="message" placeholder="Message" required></textarea>
        <input type="hidden" name="contact" value="1">
        <button type="submit" class="button-contact">Send Message</button>
      </form>
  
      <div class="contact-info">
        <h1 class="head">Contact Information</h1>
        <p>Blockverse@gmail.com</p>
        <p>+91 2481632641</p>
        <p>Navi Mumbai, Bharati Vidyapeeth</p>
      </div>
    </div>
  </div>
  <div class="main-box">

    <div class="box reward">
      <h1 class="head">Flexibility</h1>
      <p style="font-family: Fredoka;">We have worked over 400 comapnies for blockchain devlopment for their solutions.</p>
      <ul style="margin: 2vw; white-space-collapse: preserve-breaks;">
        <li style="font-family: Fredoka;">Blockchain solutions for the business</li>
        <li style="font-family: Fredoka;">Crypto space with its remarkable journey</li>
        <li style="font-family: Fredoka;">Stay tuned for blockchin news</li>
      </ul>
        <img class="pic1" src="ics/Picsart_24-09-29_15-03-57-091.png">
    </div>
    <div style="display: grid;"class="box-col">
      <div class="box22">
        <p class="head">Secure & Safe</p>
        <p style="margin: 1vw; font-family: Fredoka;">Blockchain is secure and safe due to its decentralized structure, cryptographic encryption, and immutebale transaction records.</p>
          <img class="pic3" src="ics/Picsart_24-09-29_15-08-32-290.png">
      </div>
      <div class="box">
        <p class="head">Transparent</p>
        <p style="margin: 1vw; font-family: Fredoka;">Blockchain is trasparent because all trasactions are publicly recorded on a distributed ledger, allowing anyone to verify and track them.</p>
          <img class="pic2" src="ics/Picsart_24-09-29_15-07-22-929.png">
      </div>
    </div>
  </div>
</section>

<section class="cus-sec">
  <div class="swiper mySwiper">
    <div class="swiper-wrapper">
      <img class="bit-img" src="ics/Picsart_24-09-29_17-43-27-365.png">
      <div class="swiper-slide">
        <div class="txt">
          <p class="head">Feauters</p>
          <p style="font-size: 3vw;">BlockChain Spreads Trust Everywhere</p>
          <p style="font-family: Rowdies"> Experience unparalleled trust, safety, and security with our blockchain technology, ensuring your data and transactions are protected with the highest standards.</p>
         <div class="rats"> <div class="rat">
            <p style="font-size: 1vw; font-family: Rowdies; background: -webkit-linear-gradient(#2dc000, #9c9c00); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 900;"><span>63M</span></p>
            <p>Blockchain users</p>
          </div>
          <div class="rat2">
            <p style="font-size: 1vw; background: -webkit-linear-gradient(#2dc000, #9c9c00); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 900;"><span>24%</span></p>
            <p>Blockchain Companies</p>
          </div>
        </div>
          </div>
        </div>
      </div>

      <div class="swiper-slide2">
        <div class="txt">
          <p class="head">Possibilities</p>
          <p style="font-size: 3vw; font-family: Fredoka;">What does it means for your business?</p>
          <p style="font-family: Rowdies;">Leveraging blockchain technology means our business operates with enhanced transparency, security, and efficiency, fostering greater trust with our clients and partners while safeguarding their valuable data and transactions.</p>
          <div class="card1">
            <img style="height: 34vh; margin-left: 10vw;" src="ics/bitcoin.png">
            <div style="margin: 3vw 0 0 20vh;" class="cardleft">
            <p style="color: white; margin-bottom: 1vh; font-family: Kanit;">Feauters</p>
            <p style="font-size: 3vw; margin-bottom: 1vh; font-family: Fredoka;">Blockchain is versatile and easy to adapt.</p>
            <p style="margin-bottom: 1vh; font-family: Fredoka;">Blockchain's versatility and adaptability enable seamless integration into diverse industries and applications, allowing our business to innovate and evolve with ease, while providing a future-proof foundation for growth and scalability.</p>
            <button class="button2" id="button2" style="padding: 1vh 21px 7px 24px; margin-top: 1vh; border-radius: 40px; border: none; cursor: pointer; box-shadow: 0px 4px 3px 2px;">Get Started</button>
          </div>
          </div>
        </div>

      </div>
      <div class="card">
        <h3>Technology</h3>
        <h1 style="font-family: Kanit">How does blockchain work for business?</h1>
        <div style="display: flex;" class="imgsec">
    <p style="font-family: Fredoka">Blockchain technology empowers our business by providing a decentralized, tamper-proof ledger that enables secure, transparent, and efficient transactions, supply chain management, and data storage, ultimately driving cost savings, increased productivity, and enhanced customer trust.</p>

          <div class="box12"><img src="ics/nft.png"></div>
        </div>
        <button class="button3" id="button3" style="border: none; cursor: pointer; margin-top: 2vh;box-shadow: 0px 4px 3px 2px;">Get started &nbsp; &nbsp; ➔</button>
      </div>
      <div class="swiper-slide3">
        <div class="txtq">
          <p class="head">Frequently Asked Questions</p>
          <h1>Popular questions asked about Blockchain</h1>
          <p style="width: 81vw; margin-left: 10%;">Get the answers to your burning blockchain questions: How does it work? What are its benefits? Is it secure? And more. Explore our FAQ section to uncover the truth about this revolutionary technology and its potential to transform your business.</p>
            <div class="star">
              <div class="question-ans">
              <p class="question">What is Blockchain?<span class="arrow">➔</span>
                <p class="answer">Blockchain: a decentralized, digital ledger that records transactions and data across a network of computers, ensuring transparency, security, and trust, without the need for intermediaries, revolutionizing the way we conduct business and exchange value.</p></p>
            </div>
            <div class="question-ans">
              <p class="question">How does blockchain work?<span class="arrow">➔</span></p>
              <p class="answer">"Blockchain is a decentralized network of computers that verifies and records transactions in a tamper-proof, transparent, and secure manner, using cryptography and consensus mechanisms to ensure the integrity of the data."</p>
            </div>
            <div class="question-ans">
              <p class="question">What is the difference between blockchain and cryptocurrency<span class="arrow">➔</span></p>
              <p class="answer">Blockchain is the underlying technology that enables the existence of cryptocurrency, but they are not the same thing: blockchain is a decentralized, distributed ledger that records transactions, while cryptocurrency is a digital or virtual currency that uses blockchain technology to secure and verify transactions.</p>
            </div>
            <div class="question-ans">
              <p class="question">Is blockchain secure?<span class="arrow">➔</span></p>
              <p class="answer">Blockchain technology is considered secure due to its decentralized and distributed nature, use of advanced cryptography, and consensus mechanisms, making it resistant to tampering, censorship, and single-point failures, but it's not completely immune to potential vulnerabilities and risks.</p>
            </div>
            </div>
        </div>
      </div>

      <div class="swiper-slide4">
        <div class="text">
          <h1 class="head2">Be the part of the future</h1>
          <p style="width: 43vw; font-family: Cinzel; font-weight: 900;" >WE work for future technology with ease and transparency.</p>

          <button id="button4" class="button4">Get Started</button>
        </div>
        <div style="margin-top: -20vh;" class="leftimgg">
          <img style="margin-left: 0vw; position: absolute; width: 46vw;" src="ics/nft (2).png">
        </div>
      </div>
    </div>
  </div>
  </div>
</section>

<section class="app-sec">
  <div class="txt">
    <p class="head">Building the future, one block at a time. Stay decentralized, stay empowered.</p>
  </div>
</section>

<footer class="foot-sec">
  <div class="main-box">
    <div class="box1">
      <h1>BlockVerse</h1>
      <div class="social">
        <i class="fa-brands fa-square-instagram"></i>
        <i class="fa-brands fa-facebook"></i>
        <i class="fa-brands fa-youtube"></i>
      </div>
    </div>

    <div class="box2">
      <h1>Quick Link</h1>
      <div class="btn">
        <a class="home-btn" href="index.php">Home</a>
        <a class="home-btn" href="#">Product</a>
        <a class="home-btn" href="privacy.html">Privacy Policy</a>
        <a class="home-btn" href="contact.php">Contact Us</a>
      </div>
    </div>

    <div class="box2">
      <h1>Get with us</h1>
      <div class="btn">
        <a class="home-btn" href="blog.html">BLOG</a>
        <a class="home-btn" href="aboutus.html">About  Us</a>
        <a class="home-btn" href="#">Carrers</a>
        <a class="home-btn" href="#">Our Projects</a>
      </div>
    </div>

    <div class="box4">
      <h1>Contact Us</h1>
      <div class="add">
        <p>blockverse@gmail.com</p>
        <p>+91 000001111</p>
      </div>
    </div>
  </div>
  <div class="copy">
    <p>Design By <span style="font-family: Cinzel; font-size: 2vw;">Dev, Om, Bhavesh</span></p>
  </div>
</footer>
</body>
<script type="module" src="libs/ionicons/ionicons.esm.js"></script>
<script nomodule src="libs/ionicons/ionicons.js"></script>
<script src="script.js"></script>
</html>
