 
<?php
require 'vendor/autoload.php'; // Composer autoload

use Google\Cloud\Vision\V1\ImageAnnotatorClient;

function process_payment_screenshot($image_path) {
    $imageAnnotator = new ImageAnnotatorClient();
    $image = file_get_contents($image_path);
    $response = $imageAnnotator->documentTextDetection($image);
    $text = $response->getTextAnnotations();
    
    // Example logic to extract payment amount
    $amount = extract_amount_from_text($text[0]->getDescription());
    return $amount;
}

function extract_amount_from_text($text) {
    preg_match('/\d+\.\d{2}/', $text, $matches);
    return isset($matches[0]) ? $matches[0] : 0;
}
