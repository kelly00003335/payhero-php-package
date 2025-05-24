
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

// Display results in a formatted way
echo "===== WALLET BALANCES =====\n\n";

echo "SERVICE WALLET (used for processing transactions):\n";
if (isset($serviceWallet['data']) && isset($serviceWallet['data'][0]['balance'])) {
    echo "Balance: " . $serviceWallet['data'][0]['balance'] . " " . $serviceWallet['data'][0]['currency'] . "\n";
} else {
    echo "Error retrieving service wallet balance. Response: " . print_r($serviceWallet, true) . "\n";
}

echo "\nPAYMENT WALLET (where deposits go):\n";
if (isset($paymentWallet['data']) && isset($paymentWallet['data'][0]['balance'])) {
    echo "Balance: " . $paymentWallet['data'][0]['balance'] . " " . $paymentWallet['data'][0]['currency'] . "\n";
} else {
    echo "Error retrieving payment wallet balance. Response: " . print_r($paymentWallet, true) . "\n";
}
