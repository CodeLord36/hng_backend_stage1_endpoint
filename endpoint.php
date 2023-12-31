<?php
header('Content-Type: application/json');

// Function to validate UTC time within +/-2 minutes
function validateUtcTime($time) {
    $currentUtcTime = new DateTime('now', new DateTimeZone('UTC'));
    $providedTime = new DateTime($time, new DateTimeZone('UTC'));

    $diff = $currentUtcTime->getTimestamp() - $providedTime->getTimestamp();

    return abs($diff) <= 120; // +/- 2 minutes in seconds
}

// Check if slack_name and track parameters are provided in the URL
if (isset($_GET['slack_name']) && isset($_GET['track'])) {
    $slackName = $_GET['slack_name'];
    $track = $_GET['track'];
    
    // Get the current day of the week
    $currentDay = date('l');
    
    // Get the current UTC time
    $currentUtcTime = gmdate('Y-m-d\TH:i:s\Z');
    
    // Define your GitHub repository information
    $githubFileUrl = 'https://github.com/CodeLord36/hng_backend_stage1_endpoint/blob/main/endpoint.php';
    
    $githubRepoUrl = 'https://github.com/CodeLord36/hng_backend_stage1_endpoint';
    
    // Validate UTC time
    $isValidUtcTime = validateUtcTime($currentUtcTime);
    
    // Prepare the response data
    $response = [
        'slack_name' => $slackName,
        'current_day' => $currentDay,
        'utc_time' => $isValidUtcTime ? $currentUtcTime : null,
        'track' => $track,
        'github_file_url' => $githubFileUrl,
        'github_repo_url' => $githubRepoUrl,
        'status_code' => 200,
    ];

    
} else {
    // If parameters are missing, return an error response
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Missing slack_name and/or track parameters']);
}

// Create HTML content
$htmlContent = <<<HTML
    Static HTML Page
    Slack name: $slackName
    Current day: $currentDay
    UTC time: $currentUtcTime
    Track: $track
    Status Code: 200
HTML;

echo $htmlContent;


?>