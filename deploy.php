
<?php
/**
 * Render API Deployment Script
 * 
 * This script triggers deployments for your Render service directly from Replit
 */

// Replace these with your actual values
$renderApiKey = 'YOUR_API_KEY';
$serviceId = 'YOUR_SERVICE_ID';

$url = "https://api.render.com/v1/services/{$serviceId}/deploys";
$data = '{}'; // Empty JSON object for default deployment options

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer {$renderApiKey}",
    "Content-Type: application/json"
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode >= 200 && $httpCode < 300) {
    echo "Deployment triggered successfully!\n";
    echo "Response: " . $response . "\n";
} else {
    echo "Error triggering deployment. HTTP Code: {$httpCode}\n";
    echo "Response: " . $response . "\n";
}
?>
