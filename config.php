
<?php
// PayHero API Credentials - Using environment variables with fallback
$apiUsername = getenv('PAYHERO_API_USERNAME') ?: '4SP4Ou69802a38D95V4Z';
$apiPassword = getenv('PAYHERO_API_PASSWORD') ?: '8Nm2rrXdHDc69kGGWkoiDGnIvCUvwXOu9wwTqPBD';

// Exchange rate - should match the JavaScript rate
$exchangeRate = getenv('EXCHANGE_RATE') ?: 130;
