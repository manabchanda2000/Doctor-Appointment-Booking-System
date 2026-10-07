<?php
session_start();
include '../connection.php';

if (!isset($_SESSION['doctor_id'])) {
    header("Location: doctor-login.php");
    exit();
}

$doctor_id = $_SESSION['doctor_id'];

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$sort = isset($_GET['sort']) && $_GET['sort'] === 'oldest' ? 'ASC' : 'DESC';

// Build WHERE clause
$where = "a.doctor_id = '$doctor_id' AND a.status = 'Completed'";
if (!empty($search)) {
    $where .= " AND (
        p.name LIKE '%$search%' OR 
        cl.clinic_name LIKE '%$search%'
    )";
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Records Management</title>
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

    /* first section------------------ */
    .second-sec {
        display: flex;
        flex-direction: column;
        margin: 20px 0;
        gap: 10px;
    }

    .sf-part {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
        width: 100%;
        flex-wrap: wrap;
    }

    .search-bar {
        width: 100%;
        display: flex;
        align-items: center;
        gap: 10px;
        background-color: #fdfdfd;
        padding: 10px;
        border-radius: 5px;
        font-size: 14px;
        border: 1px solid #BFDBFE;
    }

    .search-bar #search-bar {
        flex: 1;
        padding: 5px;
        outline: none;
        border: none;
        background-color: transparent;
    }

    .filter {
        display: flex;
        align-items: center;
        /* justify-content: space-between; */
        gap: 10px;
        width: 100%;
        flex-wrap: wrap;
    }

    .filter-group {
        display: flex;
        align-items: center;
        gap: 5px;
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

    /* second section------------------ */
    .table {
        overflow-y: auto;
        overflow-x: auto;
        max-height: 600px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    thead {
        background-color: #3B82F6;
        color: white;
        position: sticky;
        top: 0;
    }

    th,
    td {
        padding: 12px;
        border-bottom: 1px solid #ddd;
        white-space: nowrap;
        text-align: center;
    }

    td {
        font-size: 14px;
    }

    tbody tr {
        background-color: #fdfdfd;
        transition: all .3s ease;
    }

    tbody tr:nth-child(even) {
        background-color: #f1f1f1;
    }

    tbody tr:hover {
        background-color: #e1eefc;
    }

    .table img {
        height: 30px;
        width: 30px;
        border-radius: 50%;
        object-fit: fill;
    }

    .table span {
        display: block;
    }

    .table a {
        text-decoration: none;
        color: #3B82F6;
        font-size: 18px;
    }
    </style>
</head>

<body>
    <?php include 'doctor-header.php' ?>
    <main>
        <section class="first-sec">
            <form method="GET" class="sf-part">
                <div class="search-bar">
                    <i class="fas fa-search"></i>
                    <input type="text" id="search-bar" name="search" placeholder="Search by Patient, Clinic....."
                        value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    <button type="submit" id="search-btn">Search</button>
                </div>
                <div class="filter">
                    <div class="filter-group">
                        <select name="sort">
                            <option value="newest"
                                <?php if(isset($_GET['sort']) && $_GET['sort'] == 'newest') echo 'selected'; ?>>Sort by:
                                Newest</option>
                            <option value="oldest"
                                <?php if(isset($_GET['sort']) && $_GET['sort'] == 'oldest') echo 'selected'; ?>>Sort by:
                                Oldest</option>
                        </select>
                    </div>
                    <button type="submit" id="filter-btn">Apply Filter</button>
                </div>
            </form>
        </section>


        <section class="second-sec">
            <div class="table">
                <table>
                    <thead>
                        <tr>
                            <th>Sl no</th>
                            <th>Patient</th>
                            <th>Age/Gender</th>
                            <th>Contact</th>
                            <th>Last Visit</th>
                            <th>Clinic</th>
                            <th>Prescription</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT 
                        a.appointment_date, a.appointment_id,
                        p.patient_id, p.name AS patient_name, p.profile_img, p.gender, p.dob, p.email, p.phone,
                        cl.clinic_name
                        FROM appointment a
                        JOIN patient p ON a.patient_id = p.patient_id
                        JOIN doctor_clinic cl ON a.clinic_id = cl.clinic_id
                        WHERE $where
                        ORDER BY a.appointment_date $sort";
                    

                        $result = mysqli_query($conn, $query);
                        $sl = 1;

                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                // Calculate age
                                $dob = new DateTime($row['dob']);
                                $today = new DateTime();
                                $age = $today->diff($dob)->y;

                                // Profile image fallback
                                $img = !empty($row['profile_img']) ? $row['profile_img'] : 'user.jpg';

                                echo "<tr>
                                    <td>{$sl}</td>
                                    <td>
                                        <img src='../upload/patient/{$img}' alt='Patient Image'>
                                        <h4>{$row['patient_name']}</h4>
                                        <span>{$row['patient_id']}</span>
                                    </td>
                                    <td>{$age} / {$row['gender']}</td>
                                    <td>
                                        <span>{$row['email']}</span>
                                        <span>{$row['phone']}</span>
                                    </td>
                                    <td>" . date("M d, Y, h:i A", strtotime($row['appointment_date'])) . "</td>
                                    <td>{$row['clinic_name']}</td>
                                    <td><a href='#'><i class='fa-solid fa-file-arrow-down'></i></a></td>
                                    <td><a href='view-patientRecord.php?appointment_id={$row['appointment_id']}' title='View Details'><i class='fa-solid fa-eye'></i></a></td>
                                </tr>";
                                $sl++;
                            }
                        } else {
                            echo "<tr><td colspan='8' style='text-align:center;'>No Patient Records Found</td></tr>";
                        }
                        ?>
                    </tbody>

                </table>
            </div>
        </section>
    </main>
    <?php include 'doctor-footer.php'; ?>
</body>

</html>