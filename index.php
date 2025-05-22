<?php
require_once 'ph-class.php';

$apiUsername = '4SP4Ou69802a38D95V4Z';
$apiPassword = '8Nm2rrXdHDc69kGGWkoiDGnIvCUvwXOu9wwTqPBD';
$payHeroAPI = new PayHeroAPI($apiUsername, $apiPassword);

// Initialize response variable
$apiResponse = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'deposit':
            $amount = (float)($_POST['amount'] ?? 10); // Convert to float
            $phone = $_POST['phone'] ?? '';
            $apiResponse = $payHeroAPI->topUpServiceWallet($amount, $phone);
            break;
        case 'payment':
            $amount = (float)($_POST['amount'] ?? 10); // Convert to float
            $phone = $_POST['phone'] ?? '';
            // Using SendCustomerMpesaStkPush to deposit to Payment Wallet
            // Using a generic channel_id and reference - update these as needed
            $channel_id = '234'; // Replace with your actual channel ID
            $external_reference = 'PAY-' . time(); // Generate a unique reference
            $apiResponse = $payHeroAPI->SendCustomerMpesaStkPush($amount, $phone, $channel_id, $external_reference);
            break;
        case 'transaction_status':
            $reference = $_POST['reference'] ?? '';
            $apiResponse = $payHeroAPI->getTransactionStatus($reference);
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PayHero Deposit</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .card {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 15px;
        }
        button, input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="text"], input[type="number"] {
            padding: 8px;
            margin: 5px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .response {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            margin-top: 20px;
            white-space: pre-wrap;
        }
    </style>
</head>
<body>
    <h1>PayHero Deposit</h1>

    <div class="card">
        <h3>Make a Deposit (Payment Wallet)</h3>
        <form method="post">
            <input type="hidden" name="action" value="payment">
            <label for="amount">Amount (KES):</label>
            <input type="number" id="amount" name="amount" value="10" required>
            <label for="phone">Phone Number:</label>
            <input type="text" id="phone" name="phone" placeholder="e.g., 0708344101" required>
            <input type="submit" value="Make Deposit">
        </form>
    </div>
    
    <div class="card">
        <h3>Check Transaction Status</h3>
        <form method="post">
            <input type="hidden" name="action" value="transaction_status">
            <label for="reference">Reference:</label>
            <input type="text" id="reference" name="reference" placeholder="e.g., 2bbfced6-9003-4c59-a270-ec5396c2b3d4" value="2bbfced6-9003-4c59-a270-ec5396c2b3d4" required>
            <input type="submit" value="Check Status">
        </form>
    </div>

    <?php if (!empty($apiResponse)): ?>
    <div class="response">
        <h3>API Response:</h3>
        <pre><?php echo htmlspecialchars($apiResponse); ?></pre>
    </div>
    <?php endif; ?>
</body>
</html>