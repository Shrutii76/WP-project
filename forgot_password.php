<?php


include 'db.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// require 'vendor/autoload.php'; // If using Composer
 require 'PHPMailer-master/src/PHPMailer.php'; // If manually downloaded 
 require 'PHPMailer-master/src/SMTP.php';
require 'PHPMailer-master/src/Exception.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);

    // Check if email exists in database
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Generate a unique token
        $token = bin2hex(random_bytes(50));
        
        // Insert token into database
        $sql = "INSERT INTO password_resets (email, token) VALUES (?, ?) ON DUPLICATE KEY UPDATE token=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $email, $token, $token);
        $stmt->execute();

        // Reset Password Link
        $reset_link = "http://localhost/wp_project/reset_password.php?token=" . $token;

        // Send Email using PHPMailer
        $mail = new PHPMailer(true);
        
        try {
            $mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'quizwhiz572@gmail.com';
$mail->Password = 'rpjy wzsh labq orzh'; // App Password from Google
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = 587;


            $mail->setFrom('quizwhiz572@gmail.com', 'QuizWhiz');
            $mail->addAddress($email);

            $mail->Subject = 'Password Reset Request';
            $mail->Body = "Click the link below to reset your password:\n\n$reset_link";
            $mail->isHTML(true);

            if ($mail->send()) {
                echo "Check your email for the reset link.";
            } else {
                echo "Failed to send email.";
            }
        } catch (Exception $e) {
            echo "Error sending email: {$mail->ErrorInfo}";
        }
    } else {
        echo "No user found with this email.";
    }
}
?>
