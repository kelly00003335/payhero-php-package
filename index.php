
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
        case 'service_balance':
            $apiResponse = $payHeroAPI->getServiceWalletBalance();
            break;
        case 'payment_balance':
            $apiResponse = $payHeroAPI->getPaymentWalletBalance();
            break;
        case 'transaction_status':
            $reference = $_POST['reference'] ?? '';
            $apiResponse = $payHeroAPI->getTransactionStatus($reference);
            break;
        case 'account_transactions':
            $page = $_POST['page'] ?? 1;
            $per = $_POST['per'] ?? 5;
            $apiResponse = $payHeroAPI->getAccountTransactions($page, $per);
            break;
            
        case 'deposit':
            $amount = $_POST['amount'] ?? 10;
            $phone = $_POST['phone'] ?? '';
            $apiResponse = $payHeroAPI->topUpServiceWallet($amount, $phone);
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PayHero API Test</title>
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
    <h1>PayHero API Test Interface</h1>
    <p>Account ID: 1981</p>

    <div class="card">
        <h3>Get Service Wallet Balance</h3>
        <form method="post">
            <input type="hidden" name="action" value="service_balance">
            <input type="submit" value="Check Balance">
        </form>
    </div>

    <div class="card">
        <h3>Get Payment Wallet Balance</h3>
        <form method="post">
            <input type="hidden" name="action" value="payment_balance">
            <input type="submit" value="Check Balance">
        </form>
    </div>

    <div class="card">
        <h3>Get Transaction Status</h3>
        <form method="post">
            <input type="hidden" name="action" value="transaction_status">
            <label for="reference">Reference:</label>
            <input type="text" id="reference" name="reference" required>
            <input type="submit" value="Check Status">
        </form>
    </div>

    <div class="card">
        <h3>Get Account Transactions</h3>
        <form method="post">
            <input type="hidden" name="action" value="account_transactions">
            <label for="page">Page:</label>
            <input type="number" id="page" name="page" value="1" min="1">
            <label for="per">Per Page:</label>
            <input type="number" id="per" name="per" value="5" min="1">
            <input type="submit" value="Get Transactions">
        </form>
    </div>

    <div class="card">
        <h3>Make a Deposit (Top Up Service Wallet)</h3>
        <form method="post">
            <input type="hidden" name="action" value="deposit">
            <label for="amount">Amount (KES):</label>
            <input type="number" id="amount" name="amount" min="10" value="10" required>
            <label for="phone">Phone Number:</label>
            <input type="text" id="phone" name="phone" placeholder="e.g., 0708344101" required>
            <input type="submit" value="Make Deposit">
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
