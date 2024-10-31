<?php
// Update User Details - update_agent.php

// Path to the JSON file
$agents_file = 'agents.json';

// Fetch form data if the request is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $agent_id = trim($_POST['agent_id']);
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Load the existing agents data
    $agents = json_decode(file_get_contents($agents_file), true);
    $agent_found = false;

    // Loop through agents to find and update the specific agent
    foreach ($agents as &$agent) {
        if ($agent['agent_id'] === $agent_id) {
            $agent_found = true;

            // Update name and email
            $agent['name'] = $name;
            $agent['email'] = $email;

            // If password is provided, hash it and update
            if (!empty($password)) {
                $agent['password'] = password_hash($password, PASSWORD_DEFAULT);
            }

            // Handle Profile Picture Upload
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

                // Check the file size (limit it to 2MB)
                if ($_FILES['profile_picture']['size'] > 2 * 1024 * 1024) {
                    die('File size exceeds the limit of 2MB.');
                }

                // Move the uploaded file to the target directory
                if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $file_path)) {
                    // Update profile picture URL only if a new file was uploaded
                    $agent['profile_picture_url'] = $file_path;
                } else {
                    die('Error uploading the profile picture.');
                }
            }
            break;
        }
    }

    if (!$agent_found) {
        die('Agent not found!');
    }

    // Save updated agents data back to the JSON file
    if (file_put_contents($agents_file, json_encode($agents, JSON_PRETTY_PRINT))) {
        echo 'User details updated successfully!';
    } else {
        die('Error saving user data.');
    }
}
?>
