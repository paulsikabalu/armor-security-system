<?php
// Register Agent - register_agent.php

// Fetch form data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $agent_id = trim($_POST['agent_id']);
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validate that passwords match
    if ($password !== $confirm_password) {
        die('Passwords do not match!');
    }

    // Check if the agent ID already exists (optional)
    $agents_file = 'agents.json';
    $agents = json_decode(file_get_contents($agents_file), true);

    // Check for duplicate agent ID
    foreach ($agents as $agent) {
        if ($agent['agent_id'] === $agent_id) {
            die('Agent ID already exists!');
        }
    }

    // Check for duplicate email
    foreach ($agents as $agent) {
        if ($agent['email'] === $email) {
            die('Email address already exists!');
        }
    }

    // Hash password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Profile Picture Handling
    $profile_picture_url = ''; // Default value if no picture is uploaded

    // Check if a profile picture was uploaded
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        // Define the upload directory
        $upload_dir = 'uploads/users/';
        $file_name = basename($_FILES['profile_picture']['name']);
        $file_path = $upload_dir . $file_name;

        // Allowed file types
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];

        // Check if the file type is allowed
        if (!in_array($_FILES['profile_picture']['type'], $allowed_types)) {
            die('Invalid file type. Only JPEG, PNG, and GIF are allowed.');
        }

        // Check the file size (for example, limit it to 2MB)
        if ($_FILES['profile_picture']['size'] > 2 * 1024 * 1024) {
            die('File size exceeds the limit of 2MB.');
        }

        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $file_path)) {
            // Set the URL of the uploaded file (relative URL for later use)
            $profile_picture_url = $file_path;
        } else {
            die('Error uploading the profile picture.');
        }
    }

    // New agent data
    $new_agent = [
        'agent_id' => $agent_id,
        'name' => $name,
        'email' => $email,
        'password' => $hashed_password,
        'profile_picture_url' => $profile_picture_url, // Include the profile picture URL
        'created_at' => date('Y-m-d\TH:i:s\Z'), // Store current time as ISO 8601 format
        'last_login' => '', // Initialize last login as empty
    ];

    // Add new agent to the array
    $agents[] = $new_agent;

    // Save updated agents data back to the JSON file
    if (file_put_contents($agents_file, json_encode($agents, JSON_PRETTY_PRINT))) {
        // Redirect to login page (or wherever you want)
        header('Location: secure_login.html');
        exit();
    } else {
        die('Error saving agent data.');
    }
}
?>
