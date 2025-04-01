<?php
// Include database connection
$conn = require_once 'db_connect.php';

// Initialize variables for form data and errors
$name = $mobile = $email = $password = $gender = "";
$errors = [];

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input
    if (empty($_POST["name"])) {
        $errors[] = "Name is required";
    } else {
        $name = trim($_POST["name"]);
        // Validate name format
        if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
            $errors[] = "Only letters and white space allowed in name";
        }
    }

    if (empty($_POST["mobile"])) {
        $errors[] = "Mobile number is required";
    } else {
        $mobile = trim($_POST["mobile"]);
        // Validate mobile number format
        if (!preg_match("/^[0-9]{10}$/", $mobile)) {
            $errors[] = "Invalid mobile number format";
        }
    }

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
        // Validate password strength if needed
        if (strlen($password) < 6) {
            $errors[] = "Password must be at least 6 characters long";
        }
    }

    if (isset($_POST["gender"])) {
        $gender = $_POST["gender"];
    }

    // If no errors, proceed with registration
    if (empty($errors)) {
        $check_sql = "SELECT id FROM users WHERE email = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows > 0) {
            $errors[] = "Email already exists. Please use a different email or login.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (name, mobile, email, password, gender) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $name, $mobile, $email, $hashed_password, $gender);
            
            if ($stmt->execute()) {
                // Registration successful
                $success = "Registration successful! You can now login.";
                // Redirect to login page after 2 seconds
                header("refresh:2;url=index.php");
            } else {
                $errors[] = "Error: " . $stmt->error;
            }
            
            $stmt->close();
        }
        
        $check_stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration - BlockVerse</title>
    <link rel="stylesheet" href="style.css">
    <style>
    body {
        background-color:rgb(0, 0, 0);
    }
        .error-message {
            color: red;
            margin-bottom: 10px;
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
    <div class="modal-signup" style="display: block; margin: 50px auto; max-width: 500px;">
        <h1 class="head">Signup</h1>
        
        <?php if (!empty($errors)): ?>
            <div class="error-message">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($success)): ?>
            <div class="success-message">
                <p><?php echo $success; ?></p>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="text" name="name" placeholder="Name" value="<?php echo htmlspecialchars($name); ?>" required>
            <input type="text" name="mobile" placeholder="Mobile No." value="<?php echo htmlspecialchars($mobile); ?>" required>
            <input type="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($email); ?>">
            <input type="password" name="password" placeholder="Password" required>
            <div class="radio">
                <input style="margin: 0 10px;" type="radio" name="gender" value="Male" <?php if ($gender == "Male") echo "checked"; ?>>Male
                <input style="margin: 0 10px;" type="radio" name="gender" value="Female" <?php if ($gender == "Female") echo "checked"; ?>>Female
            </div>
            <button><input type="submit" value="Register"></button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
        <p><a href="index.php">Back to Home</a></p>
    </div>
</body>
</html>
