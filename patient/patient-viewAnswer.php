<?php
session_start();
include '../connection.php';

$question_id = $_GET['question_id'] ?? null;

if (!$question_id) {
    echo "Invalid request.";
    exit();
}

// Get Question Details (with patient name)
$q_query = "SELECT q.*, p.name AS patient_name FROM question q
            JOIN patient p ON q.patient_id = p.patient_id
            WHERE q.question_id = $question_id";
$q_result = mysqli_query($conn, $q_query);
$question = mysqli_fetch_assoc($q_result);

// Get Answers with doctor info
$a_query = "SELECT a.answer, a.answer_time, d.name AS doctor_name, d.profile_img 
            FROM answer a
            JOIN doctor d ON a.doctor_id = d.doctor_id
            WHERE a.question_id = $question_id
            ORDER BY a.answer_time DESC";
$a_result = mysqli_query($conn, $a_query);
?>

<!DOCTYPE html>
<html>

<head>
    <title>View Question & Answers</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
            background: #e3f2fd;
        }

        .container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .question-box {
            background: #ffffff;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }

        .question-box h2 {
            color: #1565c0;
        }

        .question-box p {
            margin: 10px 0;
            line-height: 1.6;
        }

        .answer-box {
            background: #dbeeff;
            border-left: 5px solid #1976d2;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            display: flex;
            gap: 15px;
            align-items: flex-start;
        }

        .doctor-photo {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #1976d2;
        }

        .answer-content {
            flex: 1;
        }

        .answer-content p {
            margin: 0;
        }

        .doctor-name {
            font-weight: bold;
            color: #0d47a1;
        }

        .timestamp {
            font-size: 12px;
            color: #555;
            margin-top: 6px;
        }

        h2,
        h3 {
            margin-top: 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Question -->
        <div class="question-box">
            <h2>Question Details</h2>
            <p><strong>Patient Name:</strong> <?= htmlspecialchars($question['patient_name']) ?></p>
            <p><strong>Patient ID:</strong> <?= htmlspecialchars($question['patient_id']) ?></p>
            <p><strong>Category:</strong> <?= htmlspecialchars($question['specialty']) ?></p>
            <p><strong>Status:</strong> <?= htmlspecialchars($question['status']) ?></p>
            <p><strong>Asked on:</strong> <?= date("F d, Y, h:i A", strtotime($question['question_time'])) ?></p>
            <hr>
            <p><strong>Question:</strong><br><?= nl2br(htmlspecialchars($question['question'])) ?></p>
            <p><strong>Description:</strong><br><?= nl2br(htmlspecialchars($question['description'])) ?></p>
        </div>

        <!-- Answers -->
        <h3 style="color: #1565c0;">Doctor Answers</h3>
        <?php if (mysqli_num_rows($a_result) > 0): ?>
            <?php while ($ans = mysqli_fetch_assoc($a_result)): ?>
                <div class="answer-box">
                    <img src="../upload/doctor/<?= htmlspecialchars($ans['profile_img']) ?>" class="doctor-photo" alt="Doctor">
                    <div class="answer-content">
                        <div class="doctor-name"><?= htmlspecialchars($ans['doctor_name']) ?></div>
                        <p><?= nl2br(htmlspecialchars($ans['answer'])) ?></p>
                        <div class="timestamp">Answered on <?= date("F d, Y, h:i A", strtotime($ans['answer_time'])) ?></div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No answers have been provided yet.</p>
        <?php endif; ?>
    </div>
</body>

</html>
