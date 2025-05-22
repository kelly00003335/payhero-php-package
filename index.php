
<?php
require_once 'ph-class.php';

$apiUsername = '4SP4Ou69802a38D95V4Z';
$apiPassword = '8Nm2rrXdHDc69kGGWkoiDGnIvCUvwXOu9wwTqPBD';
$payHeroAPI = new PayHeroAPI($apiUsername, $apiPassword);

// Initialize response variable
$apiResponse = '';
$amount = '';
$phone = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'payment':
            $amount = (float)($_POST['amount'] ?? 10); // Convert to float
            $phone = $_POST['phone'] ?? '';
            // Using SendCustomerMpesaStkPush to deposit to Payment Wallet
            // Convert USD to KSH for API call (exchange rate defined in JavaScript)
            $exchangeRate = 130; // Make sure this matches the rate in JavaScript
            $amountInKSH = $amount * $exchangeRate;
            // Using the active payment channel ID
            $channel_id = '2308'; // Active channel ID for Payment Wallet
            $external_reference = 'PAY-' . time(); // Generate a unique reference
            $apiResponse = $payHeroAPI->SendCustomerMpesaStkPush($amountInKSH, $phone, $channel_id, $external_reference);
            break;
        case 'transaction_status':
            $reference = $_POST['reference'] ?? '';
            $apiResponse = $payHeroAPI->getTransactionStatus($reference);
            break;
    }
}

// Parse response for UI display
$responseData = !empty($apiResponse) ? json_decode($apiResponse, true) : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vertex Trading</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        body {
            background-color: #111;
            color: #fff;
            padding: 20px;
        }
        .header {
            padding: 15px 0;
            border-bottom: 1px solid #333;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #fff;
            display: flex;
            align-items: center;
        }
        .header h1 span {
            color: #b3d233;
            margin-left: 8px;
        }
        .container {
            display: flex;
            gap: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .card {
            background-color: #18181b;
            border-radius: 10px;
            padding: 20px;
            flex: 1;
        }
        h2 {
            font-size: 1.5rem;
            margin-bottom: 5px;
        }
        .subtitle {
            color: #888;
            font-size: 0.9rem;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #ccc;
        }
        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 12px;
            background-color: #222;
            border: none;
            border-radius: 5px;
            color: #fff;
            margin-bottom: 20px;
        }
        input[type="text"]::placeholder, input[type="number"]::placeholder {
            color: #666;
        }
        .payment-options {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        .payment-option {
            flex: 1;
            border: 1px solid #333;
            border-radius: 5px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        .payment-option.active {
            border-color: #b3d233;
        }
        .payment-option img {
            height: 40px;
            margin-bottom: 10px;
        }
        .btn {
            width: 100%;
            padding: 14px;
            background-color: #b3d233;
            color: #000;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .btn:hover {
            background-color: #9bba29;
        }
        .btn svg {
            margin-left: 8px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #333;
        }
        .total {
            padding: 15px 0;
            text-align: right;
        }
        .total-amount {
            color: #b3d233;
            font-size: 1.5rem;
            font-weight: bold;
        }
        .total-ksh {
            color: #888;
            font-size: 0.9rem;
        }
        .secure-card {
            margin-top: 20px;
        }
        .secure-text {
            color: #888;
            font-size: 0.9rem;
        }
        .status {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: bold;
        }
        .status.success {
            background-color: #154c34;
            color: #4caf50;
        }
        .status.pending, .status.queued {
            background-color: #3a3000;
            color: #ffc107;
        }
        .status.failed {
            background-color: #4a1c1c;
            color: #f44336;
        }
        .hidden {
            display: none;
        }
        .response-area {
            margin-top: 20px;
            background-color: #222;
            padding: 15px;
            border-radius: 5px;
            font-family: monospace;
            white-space: pre-wrap;
            overflow: auto;
            max-height: 300px;
        }
        #mpesa-logo {
            filter: brightness(1.5);
        }
        .mpesa-text {
            color: #00a651;
            font-weight: bold;
        }
        .dollar-sign {
            position: absolute;
            left: 10px;
            top: 12px;
            color: #666;
        }
        .amount-input-container {
            position: relative;
        }
        .amount-input {
            padding-left: 25px !important;
        }
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Vertex <span>Trading</span></h1>
    </div>

    <div class="container">
        <div class="card">
            <h2>Make a Deposit</h2>
            <p class="subtitle">Choose your preferred payment method below</p>

            <form method="post" id="payment-form">
                <input type="hidden" name="action" value="payment">
                
                <label for="amount">Amount</label>
                <div class="amount-input-container">
                    <span class="dollar-sign">$</span>
                    <input type="number" id="amount" name="amount" class="amount-input" value="<?php echo htmlspecialchars($amount ?: ''); ?>" placeholder="0.00" required>
                </div>

                <label>Select Payment Method</label>
                <div class="payment-options">
                    <div class="payment-option active" data-option="mpesa">
                        <img id="mpesa-logo" src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/15/M-PESA_LOGO-01.svg/1200px-M-PESA_LOGO-01.svg.png" alt="M-Pesa">
                        <div class="mpesa-text">M-Pesa</div>
                    </div>
                    <div class="payment-option" data-option="airtel">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/d/da/Airtel_Africa_logo.svg/250px-Airtel_Africa_logo.svg.png" alt="Airtel Money">
                        <div>Airtel Money</div>
                    </div>
                    <div class="payment-option" data-option="card">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2a/Mastercard-logo.svg/1280px-Mastercard-logo.svg.png" alt="Visa/Mastercard">
                        <div>Visa/Mastercard</div>
                    </div>
                </div>

                <div id="mpesa-form" class="payment-form">
                    <label for="phone">M-Pesa Phone Number</label>
                    <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($phone ?: ''); ?>" placeholder="Enter your M-Pesa registered number" required>
                </div>

                <div id="airtel-form" class="payment-form hidden">
                    <label for="airtel-phone">Airtel Money Phone Number</label>
                    <input type="text" id="airtel-phone" placeholder="Enter your Airtel Money registered number">
                </div>

                <div id="card-form" class="payment-form hidden">
                    <div style="text-align: center; padding: 15px; background-color: #232327; border-radius: 5px; margin-bottom: 15px;">
                        <p>Card payments are coming soon. Please use M-Pesa or Airtel Money for now.</p>
                    </div>
                    <label for="card-number">Card Number</label>
                    <input type="text" id="card-number" placeholder="Enter your card number" disabled>
                    
                    <div style="display: flex; gap: 10px;">
                        <div style="flex: 1;">
                            <label for="card-expiry">Expiry Date</label>
                            <input type="text" id="card-expiry" placeholder="MM/YY" disabled>
                        </div>
                        <div style="flex: 1;">
                            <label for="card-cvv">CVV</label>
                            <input type="text" id="card-cvv" placeholder="123" disabled>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn">
                    Proceed to Payment
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
                    </svg>
                </button>
            </form>

            <?php if (!empty($apiResponse)): ?>
                <?php 
                $jsonResponse = json_decode($apiResponse, true);
                if (isset($jsonResponse['error_message']) && $jsonResponse['error_message'] === 'insufficient balance'): 
                ?>
                <div class="alert alert-error" style="background-color: #4a1c1c; color: #f44336; padding: 10px; border-radius: 5px; margin-top: 15px;">
                    <strong>Error:</strong> Insufficient balance in your M-Pesa account. Please try with a lower amount or add funds to your M-Pesa account.
                </div>
                <?php else: ?>
                <div class="response-area">
                    <?php echo htmlspecialchars($apiResponse); ?>
                </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <div class="card">
            <h2>Deposit Summary</h2>
            
            <div class="summary-row">
                <span>Amount (USD)</span>
                <span id="summary-amount-usd">$0.00</span>
            </div>
            
            <div class="summary-row">
                <span>Amount (KSH)</span>
                <span id="summary-amount-ksh">KSH 0.00</span>
            </div>
            
            <div class="summary-row">
                <span>Processing Fee</span>
                <span id="processing-fee">$0.00</span>
            </div>
            
            <div class="total">
                <div class="total-amount" id="total-amount">$0.00</div>
                <div class="total-ksh" id="total-ksh">KSH 0.00</div>
            </div>

            <?php if ($responseData && isset($responseData['status'])): ?>
            <div class="transaction-status">
                <h3>Transaction Status</h3>
                <p>Status: 
                    <span class="status <?php echo strtolower($responseData['status']); ?>">
                        <?php echo htmlspecialchars($responseData['status']); ?>
                    </span>
                </p>
                <?php if (isset($responseData['reference']) && !empty($responseData['reference'])): ?>
                <form method="post">
                    <input type="hidden" name="action" value="transaction_status">
                    <input type="hidden" name="reference" value="<?php echo htmlspecialchars($responseData['reference']); ?>">
                    <button type="submit" class="btn" style="margin-top: 10px;">Check Status</button>
                </form>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <div class="secure-card">
                <h3>Secure Payments</h3>
                <p class="secure-text">All transactions are encrypted and secure. Your financial information is never stored on our servers.</p>
            </div>
        </div>
    </div>

    <script>
        // Currency conversion rate - 1 USD = 130 KSH
        const exchangeRate = 130; // This value should match the PHP variable
        
        // Update summary when amount changes
        const amountInput = document.getElementById('amount');
        const summaryAmountUSD = document.getElementById('summary-amount-usd');
        const summaryAmountKSH = document.getElementById('summary-amount-ksh');
        const processingFee = document.getElementById('processing-fee');
        const totalAmount = document.getElementById('total-amount');
        const totalKSH = document.getElementById('total-ksh');
        
        function updateSummary() {
            const amount = parseFloat(amountInput.value) || 0;
            const fee = 0; // No processing fee
            const total = amount + fee;
            const kshAmount = (amount * exchangeRate);
            
            summaryAmountUSD.textContent = '$' + amount.toFixed(2);
            summaryAmountKSH.textContent = 'KSH ' + kshAmount.toFixed(2);
            processingFee.textContent = '$' + fee.toFixed(2);
            totalAmount.textContent = '$' + total.toFixed(2);
            totalKSH.textContent = 'KSH ' + (total * exchangeRate).toFixed(2);
        }
        
        amountInput.addEventListener('input', updateSummary);
        
        // Initialize summary
        updateSummary();
        
        // Payment method selection
        const paymentOptions = document.querySelectorAll('.payment-option');
        const paymentForms = document.querySelectorAll('.payment-form');
        
        paymentOptions.forEach(option => {
            option.addEventListener('click', () => {
                // Remove active class from all options
                paymentOptions.forEach(opt => opt.classList.remove('active'));
                
                // Add active class to selected option
                option.classList.add('active');
                
                // Hide all payment forms
                paymentForms.forEach(form => form.classList.add('hidden'));
                
                // Show selected payment form
                const selectedForm = document.getElementById(option.dataset.option + '-form');
                if (selectedForm) {
                    selectedForm.classList.remove('hidden');
                }
                
                // Update form submission based on selected payment method
                if (option.dataset.option === 'mpesa') {
                    // Allow M-Pesa submissions
                    document.getElementById('payment-form').onsubmit = null;
                } else if (option.dataset.option === 'airtel') {
                    // For Airtel, temporarily show a message but prepare for future implementation
                    document.getElementById('payment-form').onsubmit = (e) => {
                        e.preventDefault();
                        alert('Airtel Money integration is available with PayHero. This feature will be enabled soon.');
                        return false;
                    };
                } else {
                    // For card payments
                    document.getElementById('payment-form').onsubmit = (e) => {
                        e.preventDefault();
                        alert('Card payments are not currently supported. Please select M-Pesa for now.');
                        return false;
                    };
                }
            });
        });
    </script>
</body>
</html>
