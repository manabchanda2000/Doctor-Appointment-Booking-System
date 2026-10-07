<?php
session_start();
include '../connection.php';

if (!isset($_SESSION['doctor_id'])) {
    header("Location: doctor-login.php");
    exit();
}

$doctor_id = $_SESSION['doctor_id'];
$question_id = $_GET['question_id'] ?? null;

if (!$question_id) {
    echo "Invalid request.";
    exit();
}

// Get Question
$q_query = "SELECT q.*, p.name AS patient_name FROM question q 
            JOIN patient p ON q.patient_id = p.patient_id 
            WHERE q.question_id = $question_id";
$q_result = mysqli_query($conn, $q_query);
$question = mysqli_fetch_assoc($q_result);

// Get Answers for this question
$a_query = "SELECT answer, answer_time FROM answer 
            WHERE question_id = $question_id 
            ORDER BY answer_time DESC";
$a_result = mysqli_query($conn, $a_query);
?>
<!DOCTYPE html>
<html>

<head>
    <title>View & Reply</title>
    <style>
    body {
        font-family: 'Segoe UI', sans-serif;
        margin: 0;
        padding: 0;
        background: #e3f2fd;
    }

    .container {
        display: flex;
        padding: 40px;
        gap: 30px;
    }

    .left-column {
        width: 70%;
    }

    .right-column {
        width: 30%;
        position: sticky;
        top: 40px;
        height: fit-content;
    }

    .question-box,
    .answer-form {
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
        margin-bottom: 15px;
    }

    .answer-box p {
        margin: 0;
    }

    .answer-box .timestamp {
        font-size: 12px;
        color: #555;
        margin-top: 8px;
    }

    .answer-form h3 {
        margin-top: 0;
        color: #1565c0;
    }

    textarea {
        width: 100%;
        padding: 12px;
        margin-top: 10px;
        border-radius: 10px;
        border: 1px solid #b0bec5;
        resize: vertical;
        font-size: 15px;
        transition: border 0.2s ease-in-out;
        box-sizing: border-box;
    }

    textarea:focus {
        border: 1px solid #2196f3;
        outline: none;
    }

    button {
        margin-top: 15px;
        background: #1e88e5;
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 10px;
        cursor: pointer;
        font-weight: bold;
        font-size: 15px;
        transition: background 0.3s;
    }

    button:hover {
        background: #1565c0;
    }

    h2,
    h3 {
        margin-top: 0;
    }

    @media (max-width: 900px) {
        .container {
            flex-direction: column;
        }

        .left-column,
        .right-column {
            width: 100%;
        }

        .right-column {
            position: static;
            margin-top: 20px;
        }
    }
    </style>
</head>

<body>

    <div class="container">

        <div class="left-column">
            <!-- Question Info -->
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

            <!-- All Previous Answers -->
            <h3 style="color: #1565c0;">Previous Answers</h3>
            <?php if (mysqli_num_rows($a_result) > 0): ?>
            <?php while ($ans = mysqli_fetch_assoc($a_result)): ?>
            <div class="answer-box">
                <p><?= nl2br(htmlspecialchars($ans['answer'])) ?></p>
                <div class="timestamp">Answered on <?= date("F d, Y, h:i A", strtotime($ans['answer_time'])) ?></div>
            </div>
            <?php endwhile; ?>
            <?php else: ?>
            <p>No answers yet.</p>
            <?php endif; ?>
        </div>

        <!-- Fixed Right Column: Submit Answer -->
        <div class="right-column">
            <div class="answer-form">
                <h3>Submit Your Answer</h3>
                <form method="POST" action="doctor-viewAnswer-submit.php">
                    <input type="hidden" name="question_id" value="<?= $question_id ?>">
                    <input type="hidden" name="doctor_id" value="<?= $doctor_id ?>">
                    <textarea name="answer" rows="6" required placeholder="Type your reply..."></textarea>
                    <button type="submit">Submit Answer</button>
                </form>
            </div>
        </div>

    </div>

</body>

</html>