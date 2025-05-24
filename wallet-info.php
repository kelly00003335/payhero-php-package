
<?php
// Include the configuration file
require_once 'config.php';
// Include the PayHeroAPI class
require_once 'ph-class.php';

// Initialize the API with credentials from config
$payHeroAPI = new PayHeroAPI($apiUsername, $apiPassword);

// Get wallet balances
$serviceWalletResponse = $payHeroAPI->getServiceWalletBalance();
$paymentWalletResponse = $payHeroAPI->getPaymentWalletBalance();

// Decode JSON responses
$serviceWallet = json_decode($serviceWalletResponse, true);
$paymentWallet = json_decode($paymentWalletResponse, true);

echo "==========================================\n";
echo "           PAYHERO WALLET STATUS          \n";
echo "==========================================\n\n";

echo "SERVICE WALLET (used for processing transactions):\n";
if (isset($serviceWallet['id'])) {
    echo "Status: " . ($serviceWallet['wallet_status'] ?? 'Unknown') . "\n";
    echo "Balance: " . ($serviceWallet['available_balance'] ?? 'Unknown') . " " . ($serviceWallet['currency'] ?? 'KES') . "\n";
    
    // Add recommendations based on balance
    $balance = $serviceWallet['available_balance'] ?? 0;
    if ($balance < 100) {
        echo "\n⚠️ WARNING: Your service wallet balance is very low!\n";
        echo "You need to top up your service wallet to process payments.\n";
        echo "Recommended top-up: At least 1,000 KES for testing, or 5,000+ KES for production use.\n";
    }
} else {
    echo "Error retrieving service wallet: " . ($serviceWallet['error_message'] ?? 'Unknown error') . "\n";
}

echo "\nPAYMENT WALLET (where customer deposits go):\n";
if (isset($paymentWallet['data']) && isset($paymentWallet['data'][0]['balance'])) {
    echo "Status: " . ($paymentWallet['data'][0]['wallet_status'] ?? 'Unknown') . "\n";
    echo "Balance: " . $paymentWallet['data'][0]['balance'] . " " . $paymentWallet['data'][0]['currency'] . "\n";
} else {
    echo "Status: Not found or not set up\n";
    echo "Error: " . ($paymentWallet['error_message'] ?? 'Unknown error') . "\n";
    echo "\n⚠️ NOTE: You may need to set up your payment wallet in the PayHero dashboard.\n";
}

echo "\n==========================================\n";
echo "              HOW TO TOP UP               \n";
echo "==========================================\n\n";

echo "1. Log in to your PayHero dashboard at https://app.payhero.co.ke\n";
echo "2. Navigate to Wallets > Service Wallet\n";
echo "3. Click on 'Top Up' and follow the instructions\n";
echo "4. After topping up, payments should process successfully\n\n";

echo "For payment wallet setup (if needed):\n";
echo "1. Log in to your PayHero dashboard\n";
echo "2. Navigate to Wallets > Payment Wallet\n";
echo "3. Follow the instructions to set up your payment wallet\n\n";

echo "Current Channel ID in your code: 2308\n";
echo "Make sure this matches an active payment channel in your PayHero dashboard.\n";
?>
