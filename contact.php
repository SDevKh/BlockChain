<?php
// Include database connection
$conn = require_once 'db_connect.php';

// Initialize variables for form data and errors
$name = $email = $phone = $message = "";
$errors = [];
$success = false;

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input
    if (empty($_POST["name"])) {
        $errors[] = "Name is required";
    } else {
        $name = trim($_POST["name"]);
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

    if (empty($_POST["phone"])) {
        $errors[] = "Phone number is required";
    } else {
        $phone = trim($_POST["phone"]);
        // Validate phone number format (basic validation)
        if (!preg_match("/^[0-9]{10}$/", $phone)) {
            $errors[] = "Invalid phone number format";
        }
    }

    if (empty($_POST["message"])) {
        $errors[] = "Message is required";
    } else {
        $message = trim($_POST["message"]);
    }

    // If no errors, proceed with saving the contact message
    if (empty($errors)) {
        // Prepare and execute the SQL statement
        $sql = "INSERT INTO contact_messages (name, email, phone, message) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $name, $email, $phone, $message);
        
        if ($stmt->execute()) {
            // Message saved successfully
            $success = true;
            $name = $email = $phone = $message = ""; // Clear form fields
        } else {
            $errors[] = "Error: " . $stmt->error;
        }
        
        $stmt->close();
    }
}

// Return JSON response if it's an AJAX request
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    header('Content-Type: application/json');
    if ($success) {
        echo json_encode(['success' => true, 'message' => 'Your message has been sent successfully!']);
    } else {
        echo json_encode(['success' => false, 'errors' => $errors]);
    }
    exit;
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - BlockVerse</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .error-message {
            color: red;
            margin-bottom: 10px;
        }
        .success-message {
            color: green;
            margin-bottom: 10px;
        }
        .modal-content {
            display: block;
            margin: 50px auto;
            max-width: 800px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="modal-content">
        <h1 class="head">Get in Touch</h1>
        <p style="width: 80%; font-family: Cinzel; font-weight: 900;">We'd love to hear from you. Whether you have a question, a project in mind, or just want to say hello, please don't hesitate to reach out.</p>
        
        <?php if (!empty($errors)): ?>
            <div class="error-message">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="success-message">
                <p>Your message has been sent successfully! We'll get back to you soon.</p>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="text" name="name" placeholder="Name" value="<?php echo htmlspecialchars($name); ?>" required>
            <input type="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($email); ?>" required>
            <input type="text" name="phone" placeholder="Phone Number" value="<?php echo htmlspecialchars($phone); ?>" required>
            <textarea name="message" placeholder="Message" required><?php echo htmlspecialchars($message); ?></textarea>
            <button type="submit" class="button-contact">Send Message</button>
        </form>
    
        <div class="contact-info">
            <h1 class="head">Contact Information</h1>
            <p>Blockverse@gmail.com</p>
            <p>+91 2481632641</p>
            <p>Navi Mumbai, Bharati Vidyapeeth</p>
        </div>
        
        <p><a href="index.php">Back to Home</a></p>
    </div>
    
    <script>
        // You can add JavaScript for form validation here if needed
    </script>
</body>
</html>
