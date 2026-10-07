<?php
session_start();
include '../connection.php';

if (!isset($_SESSION['patient_id'])) {
    header("Location: patient-login.php");
    exit();
}

$patient_id = $_SESSION['patient_id'];

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$sort = isset($_GET['sort']) && $_GET['sort'] === 'oldest' ? 'ASC' : 'DESC';

// Build WHERE clause
$where = "a.patient_id = '$patient_id' AND a.status = 'Completed'";
if (!empty($search)) {
    $where .= " AND (
        d.name LIKE '%$search%' OR 
        cl.clinic_name LIKE '%$search%'
    )";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Records</title>
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

    /* first section--------------------- */
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

    .filter-group label {
        font-size: 12px;
    }

    .filter-group input[type="date"] {
        padding: 10px;
        border: 1px solid #BFDBFE;
        border-radius: 5px;
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
        margin: 20px 0;
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
        text-align: center;
        border-bottom: 1px solid #ddd;
        white-space: nowrap;
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

    td img {
        height: 30px;
        width: 30px;
        border-radius: 50%;
        object-fit: fill;
    }

    td h3 {
        font-size: 14px;
    }

    .action {
        text-decoration: none;
        color: #1B9AF5;
        margin-right: 5px;
        font-size: 16px;
    }

    tbody span {
        display: block;
    }

    /* Responsive Design */
    /* @media (max-width: 1024px) {
            .main-content {
                margin: 90px 0 0 0;
                padding: 20px;
            }

            .search-container {
                flex-direction: column;
                gap: 15px;
            }
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 15px;
            }

            th,
            td {
                font-size: 14px;
                padding: 10px;
            }

            button,
            input,
            select {
                width: 100%;
                padding: 12px;
            }
        } */
    </style>
</head>

<body>

    <!-- Include the header -->
    <?php include 'patient-header.php'; ?>
    <main>
        <section class="first-sec">
            <form method="GET" class="sf-part">
                <div class="search-bar">
                    <i class="fas fa-search"></i>
                    <input type="text" id="search-bar" name="search" placeholder="Search by Doctor, Clinic....."
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
                            <td>Sl No</td>
                            <th>Doctor</th>
                            <th>Gender/Age</th>
                            <th>Contact</th>
                            <th>Last Visit</th>
                            <th>Clinic</th>
                            <td>Location</td>
                            <td>Prescription</td>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT 
                        a.appointment_date, a.appointment_id, a.status,
                        d.name AS doctor_name, d.gender AS doctor_gender, d.profile_img, d.specialization, d.email, d.phone, d.dob,
                        cl.clinic_name, cl.area, cl.city, cl.state, cl.pincode,
                        p.prescription
                      FROM appointment a
                      JOIN doctor d ON a.doctor_id = d.doctor_id
                      JOIN doctor_clinic cl ON a.clinic_id = cl.clinic_id
                      LEFT JOIN prescription p ON a.appointment_id = p.appointment_id
                      WHERE $where
                      ORDER BY a.appointment_date $sort";
            

                        $result = mysqli_query($conn, $query);
                        $sl = 1;

                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $dob = new DateTime($row['dob']);
                                $today = new DateTime();
                                $age = $today->diff($dob)->y;
                                $img = !empty($row['profile_img']) ? $row['profile_img'] : 'user.jpg';
                        
                                echo "<tr>
                                        <td>{$sl}</td>
                                        <td>
                                            <img src='../upload/doctor/{$img}' alt='Doctor Image'>
                                            <h3>{$row['doctor_name']}</h3>
                                            <span>{$row['specialization']}</span>
                                        </td>
                                        <td>{$row['doctor_gender']} / {$age}</td>
                                        <td>
                                            <span>{$row['email']}</span>
                                            <span>{$row['phone']}</span>
                                        </td>
                                        <td>" . date("M d, Y", strtotime($row['appointment_date'])) . "</td>
                                        <td><span>{$row['clinic_name']}</span></td>
                                        <td><span>{$row['area']} {$row['city']} <br> {$row['state']} - {$row['pincode']}</span></td>";
                        
                                // Conditional check for prescription availability
                                if (!empty($row['prescription'])) {
                                    echo "<td>
                                            <a href='../upload/patient/{$row['prescription']}' target='_blank' class='action download'>
                                                <i class='fa-solid fa-download'></i>
                                            </a>
                                          </td>";
                                } else {
                                    echo "<td>No Prescription</td>";
                                }
                        
                                echo "<td>
                                        <a href='../doctor/view-patientRecord.php?appointment_id={$row['appointment_id']}' class='action view'>
                                            <i class='fa-solid fa-eye'></i>
                                        </a>
                                      </td>
                                    </tr>";
                                $sl++;
                            }
                        } else {
                            echo "<tr><td colspan='9' style='text-align:center;'>No Records Found</td></tr>";
                        }
                        
                        ?>
                    </tbody>

                </table>
            </div>
        </section>
    </main>
    <?php include 'patient-footer.php';?>
    <!-- <script>
        function viewDetails(doctor) {
            alert('Viewing details for ' + doctor);
        }

        function filterResults() {
            alert('Filter function to be implemented.');
        }
    </script> -->

</body>

</html>