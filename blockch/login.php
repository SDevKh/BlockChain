<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$conn = require_once 'db_connect.php';

// Initialize variables for form data and errors
$email = $password = "";
$errors = [];

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input
    if (empty($_POST["email"])) {
        $errors[] = "Email is required";
    } else {
        $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format";
        }
    }

    if (empty($_POST["password"])) {
        $errors[] = "Password is required";
    } else {
        $password = trim($_POST["password"]);
    }

    // If no errors, proceed with login
    if (empty($errors)) {
        $sql = "SELECT id, password FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $hashed_password);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                // Login successful
                $_SESSION['user_id'] = $id;
                header("Location: index.php");
                exit();
            } else {
                $errors[] = "Invalid email or password";
            }
        } else {
            $errors[] = "Invalid email or password";
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BlockVerse</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .error-message {
            color: red;
            margin-bottom: 10px;
        }
        .form-box {
            display: block;
            margin: 50px auto;
            max-width: 500px;
            padding: 20px;
            background: rgb(157 0 255 / 50%);;
            border-radius: 10px;
        }
        body {
        background-color:rgb(0, 0, 0);
    }
        .error-message {
            color: red;
            margin-bottom: 10px;
        }
        h2{
            color: white;
        }
        .success-message {
            color: green;
            margin-bottom: 10px;
        }
        form input{
            padding: 2vh;
            margin: 3vh;
            border-radius: 1vw;
            border: none;
        }
        form{
            margin: 6vh;
            display: inline-grid;
            width: 77%;
        }
        .modal signup{
            display: block;
            margin: 50px auto;
        }
        .radio{
            display: flex;
            margin-top: 10px;
            margin-left: 30%;
            color: white;
        }
        p{
            color: white;
            text-decoration: none;
            place-self: anchor-center;
        }
        p a{
            text-decoration: none;
            color: aquamarine
        }
        button{
            width: 26vw;
            justify-self: center;
            background: none;
            border: none;
        }
    </style>
</head>
<body>
    <div class="form-box">
        <div class="form-value">
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <h2>Login</h2>
                
                <?php if (!empty($errors)): ?>
                    <div class="error-message">
                        <?php foreach ($errors as $error): ?>
                            <p><?php echo $error; ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <div class="inputbox">
                    <ion-icon name="mail-outline"></ion-icon>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                    <label for="">Email</label>
                </div>
                <div class="inputbox">
                    <ion-icon name="lock-closed-outline"></ion-icon>
                    <input type="password" name="password" required>
                    <label for="">Password</label>
                </div>
                <div class="forget">
                    <label for=""><input type="checkbox" name="remember">Remember me <a href="#">Forget Password</a></label>
                </div>
                <button type="submit" class="loginbtn">Login</button>
                <div class="register">
                    <p>Don't have account <a href="register.php">Register</a></p>
                </div>
            </form>
            <p><a href="index.php" style="color: white;">Back to Home</a></p>
        </div>
    </div>
    
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>
