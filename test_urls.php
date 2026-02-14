<?php
$urls = [
    'http://127.0.0.1:8001/',
    'http://127.0.0.1:8001/products',
    'http://127.0.0.1:8001/login',
    'http://127.0.0.1:8001/register',
    'http://127.0.0.1:8001/cart',
];

$errorPatterns = [
    'ErrorException',
    'Stack trace',
    'Whoops',
    'syntax error',
    'ParseError',
    'Undefined variable',
    'Fatal error',
    'UnexpectedValueException',
    'BadMethodCallException',
    'Illuminate\\',
    'Server Error',
    'Exception in',
    'error:',
];

echo str_repeat('=', 70) . "\n";
echo "  URL Health Check\n";
echo str_repeat('=', 70) . "\n\n";

$allPassed = true;

foreach ($urls as $url) {
    echo "URL: $url\n";
    echo str_repeat('-', 70) . "\n";

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HEADER, false);

    $body = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($curlError) {
        echo "  STATUS:  FAIL (curl error: $curlError)\n\n";
        $allPassed = false;
        continue;
    }

    // HTTP status
    if ($httpCode === 200) {
        echo "  HTTP:    200 OK\n";
    } else {
        echo "  HTTP:    $httpCode (UNEXPECTED)\n";
        $allPassed = false;
    }

    // Check for PHP error strings
    $foundErrors = [];
    foreach ($errorPatterns as $pattern) {
        if (stripos($body, str_replace('\\', '\', $pattern)) !== false) {
            $foundErrors[] = $pattern;
        }
    }

    if (empty($foundErrors)) {
        echo "  ERRORS:  None detected\n";
        echo "  RESULT:  PASS\n";
    } else {
        echo "  ERRORS:  Found -> " . implode(', ', $foundErrors) . "\n";
        echo "  RESULT:  FAIL\n";
        $allPassed = false;

        // Extract a snippet around the first error for context
        $firstErr = str_replace('\\', '\', $foundErrors[0]);
        $pos = stripos($body, $firstErr);
        $start = max(0, $pos - 100);
        $snippet = substr($body, $start, 300);
        $snippet = strip_tags($snippet);
        $snippet = preg_replace('/\s+/', ' ', $snippet);
        echo "  SNIPPET: ..." . trim($snippet) . "...\n";
    }

    $bodyLen = strlen($body);
    echo "  SIZE:    $bodyLen bytes\n\n";
}

echo str_repeat('=', 70) . "\n";
echo $allPassed ? "  ALL PAGES PASSED\n" : "  SOME PAGES FAILED - SEE ABOVE\n";
echo str_repeat('=', 70) . "\n";
