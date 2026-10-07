<?php
session_start();
include 'connection.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:/xampp/htdocs/major-p/PHPMailer-master/src/Exception.php';
require 'C:/xampp/htdocs/major-p/PHPMailer-master/src/PHPMailer.php';
require 'C:/xampp/htdocs/major-p/PHPMailer-master/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $doctor_id = $_SESSION['doctor_id'];
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];

    // ✅ Fetch Doctor's Old Password & Email
    $stmt = $conn->prepare("SELECT password, email FROM doctor WHERE doctor_id = ?");
    $stmt->bind_param("i", $doctor_id);
    $stmt->execute();
    $stmt->bind_result($storedPassword, $email);
    $stmt->fetch();
    $stmt->close();

    if (!$email) {
        echo json_encode(["status" => "error", "message" => "User not found."]);
        exit;
    }

    // ✅ Verify Old Password
    if (!password_verify($currentPassword, $storedPassword)) {
        echo json_encode(["status" => "error", "message" => "Incorrect current password."]);
        exit;
    }

    // ✅ Generate OTP
    $otp = rand(100000, 999999);
    $_SESSION['otp'] = $otp;
    $_SESSION['otp_expiry'] = time() + 120; // OTP valid for 2 minutes
    $_SESSION['new_password'] = $newPassword;

    // ✅ Send OTP via Email
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'codergoals054@gmail.com'; // Your email
        $mail->Password = 'okvb xeyx fmkx mrcg'; // Your email password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        $mail->setFrom('codergoals054@gmail.com', 'Apna Health');
        $mail->addAddress($email);
        $mail->Subject = "Your OTP for Password Reset";
        $mail->Body = "Your OTP is: $otp. It is valid for 2 minutes.";

        $mail->send();
        echo json_encode(["status" => "success", "message" => "OTP sent successfully to your email."]);
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => "Failed to send OTP email. Error: " . $mail->ErrorInfo]);
    }
}
?>
