
<?php
require_once 'ph-class.php';

// Get API credentials
$apiUsername = '4SP4Ou69802a38D95V4Z';
$apiPassword = '8Nm2rrXdHDc69kGGWkoiDGnIvCUvwXOu9wwTqPBD';
$payHeroAPI = new PayHeroAPI($apiUsername, $apiPassword);

// Test parameters
$testPhone = '0708344101'; // Use a test phone number
$testAmount = 10; // Small amount for testing
$channel_id = '2308'; // Channel ID used in index.php
$external_reference = 'TEST-' . time(); // Generate a unique reference

echo "=== PayHero Payment Test ===\n\n";

// Step 1: Check service wallet balance
echo "Step 1: Checking service wallet balance...\n";
$serviceWalletBalance = $payHeroAPI->getServiceWalletBalance();
echo "Service Wallet Balance Response: " . $serviceWalletBalance . "\n\n";

// Step 2: Check payment wallet balance
echo "Step 2: Checking payment wallet balance...\n";
$paymentWalletBalance = $payHeroAPI->getPaymentWalletBalance();
echo "Payment Wallet Balance Response: " . $paymentWalletBalance . "\n\n";

// Step 3: Test M-Pesa STK Push (commented out by default to avoid actual charges)
echo "Step 3: Testing M-Pesa STK Push (test mode)...\n";
echo "Would send KSH " . ($testAmount * 130) . " to phone " . $testPhone . "\n";
echo "To actually initiate payment, uncomment the lines below.\n\n";

// Uncomment these lines to actually test a payment
// $stkPushResponse = $payHeroAPI->SendCustomerMpesaStkPush($testAmount, $testPhone, $channel_id, $external_reference);
// echo "M-Pesa STK Push Response: " . $stkPushResponse . "\n\n";

// Step 4: Simulate checking transaction status
echo "Step 4: Simulating transaction status check...\n";
echo "To check an actual transaction status, replace 'YOUR-REFERENCE-ID' with a real transaction reference.\n\n";

// Uncomment this line to check an actual transaction
// $transactionStatus = $payHeroAPI->getTransactionStatus('YOUR-REFERENCE-ID');
// echo "Transaction Status Response: " . $transactionStatus . "\n\n";

echo "=== Test Complete ===\n";
echo "To perform an actual payment test, modify this script to uncomment the payment sections.\n";
?>
