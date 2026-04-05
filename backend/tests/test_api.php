<?php

require_once __DIR__ . '/../config.php';

$baseUrl = API_BASE_URL;
$baseUrl = getenv('API_BASE_URL') ?: API_BASE_URL;

$passed = 0;
$failed = 0;

echo PHP_EOL;
echo "API TEST RUN" . PHP_EOL;
echo "Base URL: $baseUrl" . PHP_EOL;
echo PHP_EOL;

$tests = [
    [
        "name" => "Zones List (Validate All)",
        "endpoint" => "/zones",
        "check" => function($data) {
            $count = count($data);
            echo "Info   : Found $count zones" . PHP_EOL;

            if ($count === 0) {
                echo "Error  : No zones found" . PHP_EOL;
                return false;
            }

            if ($count > 10) {
                echo "Error  : More than expected (max 10 zones)" . PHP_EOL;
                return false;
            }

            foreach ($data as $index => $zone) {
                if (!isset($zone['id'], $zone['name'], $zone['type'], $zone['status'])) {
                    echo "Error  : Zone at index $index missing required fields" . PHP_EOL;
                    return false;
                }
            }

            return true;
        }
    ],
    [
        "name" => "Zone Detail (ID 1)",
        "endpoint" => "/zones/1",
        "check" => function($data) {
            return isset(
                $data['id'],
                $data['name'],
                $data['description'],
                $data['max_capacity'],
                $data['hourly_rate_eur'],
                $data['latitude'],
                $data['longitude']
            );
        }
    ],
    [
        "name" => "Zone Not Found (ID 999)",
        "endpoint" => "/zones/999",
        "expected_status" => 404
    ]
];

//
// 🔍 RUN BASE TESTS
//
foreach ($tests as $test) {
    echo "Test   : {$test['name']}" . PHP_EOL;

    $url = $baseUrl . $test['endpoint'];
    $context = stream_context_create(["http" => ["ignore_errors" => true]]);
    $response = @file_get_contents($url, false, $context);

    if ($response === false) {
        echo "Result : FAILED — Cannot connect to $url" . PHP_EOL . PHP_EOL;
        $failed++;
        continue;
    }

    if (!isset($http_response_header[0])) {
        echo "Result : FAILED — No HTTP response" . PHP_EOL . PHP_EOL;
        $failed++;
        continue;
    }

    preg_match('/HTTP\/\d\.\d (\d+)/', $http_response_header[0], $matches);
    $status = intval($matches[1]);

    if (isset($test['expected_status'])) {
        if ($status === $test['expected_status']) {
            echo "Result : PASSED (HTTP $status)" . PHP_EOL . PHP_EOL;
            $passed++;
        } else {
            echo "Result : FAILED (HTTP $status)" . PHP_EOL . PHP_EOL;
            $failed++;
        }
        continue;
    }

    $data = json_decode($response, true);

    if (!is_array($data)) {
        echo "Result : FAILED — Invalid JSON response" . PHP_EOL . PHP_EOL;
        $failed++;
        continue;
    }

    if ($test['check']($data)) {
        echo "Result : PASSED" . PHP_EOL . PHP_EOL;
        $passed++;
    } else {
        echo "Result : FAILED — Data validation error" . PHP_EOL . PHP_EOL;
        $failed++;
    }
}

//
//LOOP TEST: Validate all zone detail endpoints
//
echo "DETAIL LOOP TEST" . PHP_EOL;

for ($i = 1; $i <= 10; $i++) {
    $url = "$baseUrl/zones/$i";
    echo "Test   : Zone Detail ID $i" . PHP_EOL;

    $context = stream_context_create(["http" => ["ignore_errors" => true]]);
    $response = @file_get_contents($url, false, $context);

    if ($response === false) {
        echo "Result : FAILED — Cannot connect" . PHP_EOL . PHP_EOL;
        $failed++;
        continue;
    }

    $data = json_decode($response, true);

    if (!isset(
        $data['id'],
        $data['name'],
        $data['description'],
        $data['latitude'],
        $data['longitude']
    )) {
        echo "Result : FAILED — Invalid structure" . PHP_EOL . PHP_EOL;
        $failed++;
        continue;
    }

    echo "Result : PASSED" . PHP_EOL . PHP_EOL;
    $passed++;
}

//
// SUMMARY
//
echo "TEST SUMMARY" . PHP_EOL;
echo "Passed Tests : $passed" . PHP_EOL;
echo "Failed Tests : $failed" . PHP_EOL;

if ($failed === 0) {
    echo "Result       : SUCCESS — All tests passed." . PHP_EOL;
} else {
    echo "Result       : FAILURE — Some tests did not pass." . PHP_EOL;
}

echo PHP_EOL;