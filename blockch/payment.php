<?php
$required = ['name', 'email', 'phone', 'amount'];
$missing = [];
foreach ($required as $field) {
    if (empty($_POST[$field])) {
        $missing[] = $field;
    }
}
if (!empty($missing)) {
    die("Missing required parameters: " . implode(', ', $missing));
}

$name   = $_POST['name'];
$email  = $_POST['email'];
$phone  = $_POST['phone'];

// Validate and sanitize amount with detailed error messages
$amount = $_POST['amount'];
if (!is_numeric($amount)) {
    die("Amount must be a number (received: " . htmlspecialchars($amount) . ")");
}
$amount = (float)$amount;
if ($amount <= 0) {
    die("Amount must be greater than 0 (received: $amount)");
}
if ($amount > 1000000) { // Example upper limit
    die("Amount cannot exceed 1,000,000");
}

$url = "https://api.cashfree.com/pg/orders";
$clientID = "518580dba75d8a1ce0223d4db0085815";
$secret = "acc7aa78e28d56ec441b8226c5091ae6ba35288f";
$id = date('y'.'m'.'d'. 'H'.'i'.'s');

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'x-client-id: '.$clientID,
    'x-client-secret: '.$secret,
    'x-api-version: 2022-01-01'
));

$data = <<< JSON
{
    "order_id": "order_$id",
    "order_amount": "$amount",
    "order_currency": "INR",
    "customer_details": {
        "customer_id": "$id",
        "customer_name": "$name",
        "customer_email": "$email",
        "customer_phone": "$phone"
    }
}
JSON;
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
$response = curl_exec($ch);
$decode = json_decode($response);
$link = $decode->payment_link;

header("Location: $link");

?>
