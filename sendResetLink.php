<?php
require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

// Load environment variables
$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/.env');

// Configure the mailer transport
$transport = Transport::fromDsn($_ENV['MAILER_DSN']);
$mailer = new Mailer($transport);

// Get the email from POST request
$emailAddress = $_POST['email'];

// Create the email
$email = (new Email())
    ->from('4929210@gmail.com') // Replace with your sending email
    ->to($emailAddress)          // Use the email from the form
    ->subject('Password Reset Request')
    ->text('Click on this link to reset your password: [link_here]')
    ->html('<p>Click on this <a href="[link_here]">link</a> to reset your password.</p>');

// Send the email
try {
    $mailer->send($email);
    echo "A password reset link has been sent to your email address.";
} catch (Exception $e) {
    echo "Failed to send reset link: " . $e->getMessage();
}
?>
