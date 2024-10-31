<?php
// secure_area.php

// Start the session
session_start();

// Initialize response
$response = [
    'is_logged_in' => false,
    'message' => 'User is not logged in.'
];

// Check if the user is authenticated
if (isset($_SESSION['agent_id']) && isset($_SESSION['name'])) {
    // User is logged in, update the response

    // Path to the JSON file where agents' data is stored
    $agents_file = 'agents.json';

    // Read and decode the JSON file
    $agents = json_decode(file_get_contents($agents_file), true);

    // Find the logged-in agent by agent_id
    foreach ($agents as $agent) {
        if ($agent['agent_id'] === $_SESSION['agent_id']) {
            $last_login = isset($agent['last_login']) ? $agent['last_login'] : 'No login history';
            $profile_url = isset($agent['profile_picture_url']) ? $agent['profile_picture_url'] : 'No profile URL available';
            $profile_picture = isset($agent['profile_picture_url']) ? $agent['profile_picture_url'] : 'No profile picture available'; // Add profile picture
            break;
        }
    }

    // Update the response with user information
    $response['is_logged_in'] = true;
    $response['message'] = 'Welcome Agent, ' . $_SESSION['name'] . '!';
    $response['agent_id'] = $_SESSION['agent_id'];
    $response['name'] = $_SESSION['name'];
    $response['email'] = $_SESSION['email'];
    $response['last_login'] = $last_login; // Add last login timestamp
    $response['profile_url'] = $profile_url; // Add profile URL
    $response['profile_picture'] = $profile_picture; // Add profile picture URL
} else {
    // Optionally handle other scenarios (e.g., expired session)
    $response['message'] = 'Session has expired or user is not authenticated.';
}

// Return the response as JSON
echo json_encode($response);
?>
