<?php
session_start();
include '../connection.php';

$patient_id = $_SESSION['patient_id'] ?? null;

if (!isset($_SESSION['patient_id'])) {
    header("Location: patient-login.php");
    exit();
}


$conditions = ["patient_id = '$patient_id'"];

// Category filter
if (!empty($_GET['category'])) {
    $category = mysqli_real_escape_string($conn, $_GET['category']);
    $conditions[] = "specialty = '$category'";
}

// Status filter
if (!empty($_GET['status'])) {
    $status = mysqli_real_escape_string($conn, $_GET['status']);
    $conditions[] = "status = '$status'";
}

// Order filter
$order_by = "ORDER BY question_time DESC"; // Default to latest
if (!empty($_GET['order']) && $_GET['order'] === 'asc') {
    $order_by = "ORDER BY question_time ASC";
}

$where_clause = implode(" AND ", $conditions);

// Count Queries
$count_query = "SELECT 
    SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) AS pending,
    SUM(CASE WHEN status = 'Answered' THEN 1 ELSE 0 END) AS answered
    FROM question WHERE patient_id = '$patient_id'";
$count_result = mysqli_query($conn, $count_query);
if ($count_result && mysqli_num_rows($count_result) > 0) {
    $counts = mysqli_fetch_assoc($count_result);
    $pending_count = $counts['pending'];
    $answered_count = $counts['answered'];
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Ask a Question</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
    * {
        padding: 0;
        margin: 0;
        box-sizing: border-box;
        font-family: 'Inter', sans-serif;
    }

    main {
        background: linear-gradient(135deg, #f2f8fc, #f0f8ff);
        padding: 80px 20px 20px 280px;
    }

    /* first section-------------------- */
    .first-sec {
        display: flex;
        align-items: center;
        gap: 20px;
        justify-content: space-between;
        flex-wrap: wrap;
    }

    .count-part {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .count-box {
        flex: 0 0 200px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.12);
        border-radius: 5px;
        padding: 20px;
        background: linear-gradient(135deg, #EFF6FF -4%, #DBEAFE 100%);
    }

    .sf-part {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;

    }

    .filter-group select {
        padding: 10px;
        border: 1px solid #BFDBFE;
        border-radius: 5px;
        outline: none;
    }

    #search-btn,
    #filter-btn {
        background-color: #3B82F6;
        padding: 8px 12px;
        border: none;
        border-radius: 5px;
        color: white;
        cursor: pointer;
    }

    #add-ques {
        text-decoration: none;
        background-color: #3B82F6;
        padding: 8px 12px;
        border: none;
        border-radius: 5px;
        color: white;
    }

    /* second section---------------------- */
    .second-sec {
        margin: 20px 0;
    }

    .second-sec h2 {
        font-size: 20px;
        margin-bottom: 30px;
    }

    .qna-part {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }

    .question-box {
        display: flex;
        flex-direction: column;
        gap: 20px;
        align-items: flex-start;
        background: #fdfdfd;
        border: 1px solid #BFDBFE;
        border-left: 5px solid #1976d2;
        border-radius: 10px;
        box-shadow: 2px 4px 6px rgba(0, 0, 0, 0.13);
        width: 100%;
        padding: 20px;
        margin-bottom: 20px;
    }

    .box-top {
        display: flex;
        align-items: center;
        gap: 15px;
        width: 100%;
    }

    .box-top h3 {
        font-size: 16px;
        flex: 1;
    }

    .box-top #status-back {
        padding: 5px 8px;
        border-radius: 20px;
        background-color: #cdf7cd;
        color: #106610;
        font-size: 14px;
    }

    .box-top #time {
        color: #4B5563;
        font-size: 12px;
    }

    .box-middle p {
        color: #4B5563;
        font-size: 14px;
        line-height: 1.4;
        text-align: justify;
    }

    .box-bottom {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .box-bottom #cat-back {
        padding: 4px 6px;
        background-color: #c1d6f7;
        color: #1c52a9;
        border-radius: 20px;
        font-size: 14px;
    }

    .box-bottom #view-btn {
        border: 1px solid #3B82F6;
        background-color: white;
        padding: 6px 12px;
        color: #3B82F6;
        text-decoration: none;
        border-radius: 5px;
    }

    /* modal box------------------------ */
    .modal {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
    }

    .modal-content {
        background: #fff;
        margin: 5% auto;
        padding: 30px;
        border-radius: 12px;
        max-width: 500px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        position: relative;
        animation: zoomIn 0.3s ease;
    }

    @keyframes zoomIn {
        from {
            transform: scale(0.7);
        }

        to {
            transform: scale(1);
        }
    }

    .close-btn {
        position: absolute;
        top: 12px;
        right: 16px;
        font-size: 24px;
        color: #888;
        cursor: pointer;
    }

    .question-form label {
        font-weight: 600;
        display: block;
        margin-top: 12px;
        color: #444;
    }

    .question-form input[type="text"],
    .question-form select,
    .question-form textarea {
        width: 100%;
        padding: 10px;
        margin-top: 6px;
        border-radius: 6px;
        border: 1px solid #ccc;
        font-size: 15px;
    }

    .question-form button {
        margin-top: 20px;
        padding: 12px 20px;
        background: #3B82F6;
        color: #fff;
        font-weight: 600;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        width: 100%;
        transition: 0.2s;
    }

    .question-form button:hover {
        background: #2563eb;
    }
    </style>
</head>

<body>

    <!-- Include header and sidebar -->
    <?php include 'patient-header.php'; ?>

    <main>
        <section class="first-sec">
            <div class="count-part">
                <div class="count-box">
                    <strong><?= $pending_count ?? 0 ?></strong>
                    <p>Questions Pending</p>
                </div>
                <div class="count-box">
                    <strong><?= $answered_count ?? 0 ?></strong>
                    <p>Questions Answered</p>
                </div>
            </div>
            <form method="GET" class="sf-part">
                <div class="filter-group">
                    <select name="category">
                        <option value="" <?= empty($_GET['category']) ? 'selected' : '' ?>>All Category</option>
                        <option value="Skin" <?= ($_GET['category'] ?? '') === 'Skin' ? 'selected' : '' ?>>Skin</option>
                        <option value="Heart" <?= ($_GET['category'] ?? '') === 'Heart' ? 'selected' : '' ?>>Heart
                        </option>
                        <option value="Dental" <?= ($_GET['category'] ?? '') === 'Dental' ? 'selected' : '' ?>>Dental
                        </option>
                        <option value="Diabetes" <?= ($_GET['category'] ?? '') === 'Diabetes' ? 'selected' : '' ?>>
                            Diabetes</option>
                        <option value="Mental Health"
                            <?= ($_GET['category'] ?? '') === 'Mental Health' ? 'selected' : '' ?>>Mental Health
                        </option>
                    </select>
                </div>

                <div class="filter-group">
                    <select name="status">
                        <option value="" <?= empty($_GET['status']) ? 'selected' : '' ?>>All Question</option>
                        <option value="Pending" <?= ($_GET['status'] ?? '') === 'Pending' ? 'selected' : '' ?>>Pending
                        </option>
                        <option value="Answered" <?= ($_GET['status'] ?? '') === 'Answered' ? 'selected' : '' ?>>
                            Answered</option>
                    </select>
                </div>

                <div class="filter-group">
                    <select name="order">
                        <option value="desc" <?= ($_GET['order'] ?? 'desc') === 'desc' ? 'selected' : '' ?>>Latest
                        </option>
                        <option value="asc" <?= ($_GET['order'] ?? '') === 'asc' ? 'selected' : '' ?>>Oldest</option>
                    </select>
                </div>

                <button type="submit" id="filter-btn">Apply Filter</button>
                <a href="#" id="add-ques" onclick="openModal()">Ask Question</a>
            </form>

        </section>

        <section class="second-sec">
            <h2>My Asked Questions</h2>
            <div class="qna-part">
                <?php
                $query = "SELECT * FROM question WHERE $where_clause $order_by";
                $result = mysqli_query($conn, $query);
                ?>
                <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($q = mysqli_fetch_assoc($result)): ?>
                <article class="question-box">
                    <div class="box-top">
                        <h3><?= htmlspecialchars($q['question']) ?>?</h3>
                        <span id="status-back">
                            <data><?= htmlspecialchars($q['status']) ?></data>
                        </span>
                        <span id="time"><?= date("F d, Y, h:i A", strtotime($q['question_time'])) ?></span>
                    </div>
                    <div class="box-middle">
                        <p><?= nl2br(htmlspecialchars($q['description'])) ?></p>
                    </div>
                    <div class="box-bottom">
                        <span id="cat-back"><data><?= htmlspecialchars($q['specialty']) ?></data></span>
                        <span>
                            <a href="patient-viewAnswer.php?question_id=<?= $q['question_id'] ?>" id="view-btn">View Answer</a>
                            <a href="javascript:void(0);" onclick="openEditModal('<?= $q['question_id'] ?>', '<?= $q['specialty'] ?>', `<?= addslashes($q['question']) ?>`, `<?= addslashes($q['description']) ?>`)" id="view-btn">Edit</a>
                            <a href="patient-deleteQuestion.php?question_id=<?= $q['question_id'] ?>" id="view-btn" onclick="return confirm('Are you sure you want to delete this question?');">Delete</a>
                        </span>

                    </div>
                </article>
                <?php endwhile; ?>
                <?php else: ?>
                <div
                    style="padding: 20px; background: #f9f9f9; border-radius: 8px; color: #555; text-align: center; width: 100%; border: 1px solid #555;">
                    <strong>No questions found.</strong><br>
                    You haven’t asked any questions yet.
                </div>
                <?php endif; ?>

            </div>
        </section>
        <!-- Modal Box -->
        <div id="questionModal" class="modal">
            <div class="modal-content">
                <span class="close-btn" onclick="closeModal()">&times;</span>
                <h2>Ask Health Question</h2>

                <form method="POST" action="patient-question-submit.php" class="question-form">
                    <p><label>Patient ID: <?= $_SESSION['patient_id'] ?></label></p>
                    <input type="hidden" name="patient_id" value="<?= $_SESSION['patient_id'] ?>">

                    <label for="category">Category:</label>
                    <select name="specialty" required>
                        <option value="">-- Choose Category --</option>
                        <option value="Skin">Skin</option>
                        <option value="Heart">Heart</option>
                        <option value="Dental">Dental</option>
                        <option value="Diabetes">Diabetes</option>
                        <option value="Mental Health">Mental Health</option>
                    </select>

                    <label for="title">Question Title:</label>
                    <input type="text" name="question" required placeholder="Type your main question">

                    <label for="description">Short Description (Optional):</label>
                    <textarea name="description" rows="4" placeholder="Write something more if you want..."></textarea>

                    <button type="submit">Submit Question</button>
                </form>
            </div>
        </div>

        <!-- Edit Question Modal -->
        <div id="editQuestionModal" class="modal">
            <div class="modal-content">
                <span class="close-btn" onclick="closeEditModal()">&times;</span>
                <h2>Edit Health Question</h2>
                <form id="editQuestionForm" method="POST" action="patient-questionEdit-submit.php" class="question-form">
                    <input type="hidden" name="question_id" id="edit_question_id">

                    <div class="form-group">
                        <label>Patient ID:</label>
                        <input type="text" value="<?= $_SESSION['patient_id'] ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Category:</label>
                        <select name="specialty" id="edit_category" required>
                            <option value="Skin">Skin</option>
                            <option value="Heart">Heart</option>
                            <option value="Dental">Dental</option>
                            <option value="Diabetes">Diabetes</option>
                            <option value="Mental Health">Mental Health</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Question Title:</label>
                        <input type="text" name="question" id="edit_title" required>
                    </div>

                    <div class="form-group">
                        <label>Short Description (Optional):</label>
                        <textarea name="description" id="edit_description" rows="3"></textarea>
                  </div>

                    <button type="submit" id="update-btn">Update</button>
                </form>
            </div>
        </div>

    </main>
    <script>
    function openModal() {
        document.getElementById('questionModal').style.display = 'block';
    }

    function closeModal() {
        document.getElementById('questionModal').style.display = 'none';
    }

    function openEditModal(questionId, category, question, description) {
    document.getElementById('edit_question_id').value = questionId;
    document.getElementById('edit_category').value = category;
    document.getElementById('edit_title').value = question;
    document.getElementById('edit_description').value = description;

    document.getElementById('editQuestionModal').style.display = 'block';
}


    function closeEditModal() {
        document.getElementById('editQuestionModal').style.display = 'none';
    }

    // Combined modal close listener
    window.onclick = function(e) {
        const questionModal = document.getElementById('questionModal');
        const editQuestionModal = document.getElementById('editQuestionModal');

        if (e.target === questionModal) {
            closeModal();
        }
        if (e.target === editQuestionModal) {
            closeEditModal();
        }
    }
    </script>
    <?php include 'patient-footer.php'; ?>

</body>

</html>