<?php
// secure_login.php

// Fetch form data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Path to the JSON file where agents' data is stored
    $agents_file = 'agents.json';
    
    // Read and decode the JSON file
    if (!file_exists($agents_file)) {
        die('Agents data file not found!');
    }
    
    $agents = json_decode(file_get_contents($agents_file), true);

    // Flag to track if agent is found
    $authenticated = false;

    // Check if username exists and password matches
    foreach ($agents as $index => $agent) {
        if ($agent['agent_id'] === $username && password_verify($password, $agent['password'])) {
            // Authentication successful, set session or cookies
            session_start();
            $_SESSION['agent_id'] = $agent['agent_id'];
            $_SESSION['name'] = $agent['name'];
            $_SESSION['email'] = $agent['email'];
            $_SESSION['profile_picture'] = $agent['profile_picture']; // Store profile picture in session

            // Update the last_login timestamp to the current time
            $agents[$index]['last_login'] = date('Y-m-d\TH:i:s\Z'); // Format as ISO 8601

            // Save the updated data back to the JSON file
            file_put_contents($agents_file, json_encode($agents, JSON_PRETTY_PRINT));

            // Redirect to secure area
            header('Location: ../../spy.portal.html');
            exit();
        }
    }

    // If authentication failed
    $error_message = 'Invalid Agent ID or Access Code. Please try again.';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Failed - Secure Access</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: 'Arial', sans-serif;
        }
        .login-container {
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .login-container h2 {
            color: #d9534f;
        }
        .login-container p {
            color: #6c757d;
        }
        .login-container a {
            display: inline-block;
            margin-top: 1rem;
            color: #007bff;
            text-decoration: none;
        }
        .login-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login Failed</h2>
        <p><?php echo isset($error_message) ? $error_message : ''; ?></p>
        <a href="login.html">Go Back to Login</a>
    </div>
</body>
</html>
