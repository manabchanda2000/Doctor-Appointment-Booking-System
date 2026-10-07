<?php
session_start();
include '../connection.php';
// Store from POST if available

$patient_lat = $_SESSION['latitude'] ?? null;
$patient_long = $_SESSION['longitude'] ?? null;

// Get filter parameters from GET request
$q = trim($_GET['q'] ?? '');
$specialization = trim($_GET['specialization'] ?? '');
$rating = trim($_GET['rating'] ?? '');
$experience = trim($_GET['experience'] ?? '');
$days = $_GET['days'] ?? [];
$time_from = trim($_GET['time_from'] ?? '');
$time_to = trim($_GET['time_to'] ?? '');

// Build WHERE clause
$where = [];
if ($q !== '') {
    $like = "%$q%";
    $where[] = "(d.name LIKE '$like' OR c.clinic_name LIKE '$like' OR c.area LIKE '$like' OR c.city LIKE '$like' OR c.state LIKE '$like' OR c.pincode LIKE '$like')";
}
if ($specialization !== '') {
    $where[] = "d.specialization = '$specialization'";
}
if ($rating !== '') {
    $where[] = "d.rating >= $rating";
}
if ($experience !== '') {
    $where[] = "d.experience >= $experience";
}
if (!empty($days)) {
    $day_conditions = [];
    foreach ($days as $day) {
        $day_conditions[] = "c.days_available LIKE '%$day%'";
    }
    $where[] = '(' . implode(' OR ', $day_conditions) . ')';
}
if ($time_from !== '' && $time_to !== '') {
    $where[] = "(c.opening_time <= '$time_from' AND c.closing_time >= '$time_to')";
}

$where_clause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

// Distance calculation if location is set
if ($patient_lat && $patient_long) {
    $distance_query = ", (6371 * ACOS(COS(RADIANS($patient_lat)) 
                         * COS(RADIANS(c.latitude)) 
                         * COS(RADIANS(c.longitude) - RADIANS($patient_long)) 
                         + SIN(RADIANS($patient_lat)) 
                         * SIN(RADIANS(c.latitude)))) AS distance";
    $order_by = "ORDER BY distance ASC, c.clinic_id DESC";
} else {
    $distance_query = "";
    $order_by = "ORDER BY c.clinic_id DESC";
}

// Final SQL
$sql = "SELECT d.doctor_id, d.name AS doctor_name, d.specialization,
               d.experience, d.rating, d.total_reviews, d.profile_img,
               d.emergency, d.availability,
               c.clinic_id, c.clinic_name, c.area, c.city, c.state, c.pincode,
               c.days_available, c.opening_time, c.closing_time, c.fees,
               c.latitude, c.longitude
               $distance_query
        FROM doctor_clinic c
        JOIN doctor d ON d.doctor_id = c.doctor_id
        $where_clause
        $order_by";

$res = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Search</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
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

    .first-sec {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        padding: 10px;
        gap: 20px;
    }

    .heading-part {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
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
        justify-content: space-between;
        gap: 10px;
        width: 100%;
        flex-wrap: wrap;
    }

    .filter-group {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .filter-group label {
        font-size: 12px;
    }

    .filter-group select,
    input[type="time"] {
        padding: 10px;
        outline: none;
        border: 1px solid #BFDBFE;
        border-radius: 5px;
    }


    #search-btn,
    #filter-btn,
    #add-btn {
        text-decoration: none;
        background-color: #2563EB;
        padding: 8px 12px;
        border: none;
        border-radius: 5px;
        color: white;
        cursor: pointer;
        font-size: 14px;
    }

    /* second section------------------------- */
    .second-sec {
        display: flex;
        align-items: flex-start;
        justify-content: center;
        gap: 20px;
        flex-wrap: wrap;
        margin: 20px 0;
        /* padding-right: 230px; */
    }

    .doc-card {
        flex: 0 0 350px;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
        padding: 15px;
        background-color: #fdfdfd;
        box-shadow: 2px 4px 6px rgba(0, 0, 0, 0.15);
        border-radius: 5px;
    }

    .doc-card-top {
        display: flex;
        align-items: center;
        gap: 10px;
        width: 100%;
    }

    .doc-card-top img {
        height: 50px;
        width: 50px;
        border-radius: 50%;
        object-fit: fill;
    }

    .doc-profile {
        flex: 1;
    }

    .doc-profile h3 {
        font-size: 16px;
    }

    #specialization {
        font-size: 14px;
        color: #3B82F6;
        margin: 5px 0;
    }

    .rating {
        font-size: 12px;
    }

    .rating i {
        color: #FACC15;
    }

    #status {
        padding: 6px 10px;
        background-color: #cdf7cd;
        color: #046e04;
        border-radius: 20px;
        font-size: 12px;
    }

    .doc-card-middle {
        background-color: #ebeced;
        padding: 10px;
        width: 100%;
        border-radius: 5px;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
        width: 100%;
    }

    .doc-card-middle div {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 14px;
        color: #4B5563;
    }

    .doc-card-bottom {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        width: 100%;
    }

    .doc-card-bottom a {
        flex: 0 0 150px;
        text-decoration: none;
        padding: 10px 12px;
        border-radius: 10px;
        font-size: 12px;
        text-align: center;
        transition: all .3s ease;
    }

    .doc-card-bottom a:hover {
        background-color: #3B82F6;
        color: white;
    }

    .book {
        background-color: #1B9AF5;
        color: white;
    }

    .view {
        border: 1px solid #1B9AF5;
        color: #1B9AF5;
    }

    /* Responsive Design */
    /* @media (max-width: 1024px) {
            .container {
                flex-direction: column;
                padding: 20px;
            }

            .doctor-cards,
            .filter-container {
                width: 100%;
            }

            .card {
                width: 100%;
                padding: 20px;
            }

            .profile-image {
                width: 100px;
                height: 100px;
                margin-right: 15px;
            }

            .buttons {
                flex-direction: column;
                align-items: flex-start;
                margin-left: 0;
            }
        } */
    </style>
</head>

<body>

    <!-- Include the header -->
    <?php include 'patient-header.php'; ?>
    <main>
        <section class="first-sec">
            <form class="sf-part" method="GET">
                <div class="search-bar">
                    <i class="fas fa-search"></i>
                    <input type="search" name="q" id="search-bar"
                        placeholder="Dr name, Clinic name, area, city, state, pin......"
                        value="<?= htmlspecialchars($q) ?>">
                    <button type="submit" id="search-btn">Search</button>
                </div>
                <div class="filter">
                    <div class="filter-group">
                        <select name="specialization">
                            <option value="">All Specialties</option>
                            <option value="Cardiology" <?= $specialization === 'Cardiology' ? 'selected' : '' ?>>
                                Cardiology</option>
                            <option value="Dermatology" <?= $specialization === 'Dermatology' ? 'selected' : '' ?>>
                                Dermatology</option>
                            <option value="Neurology" <?= $specialization === 'Neurology' ? 'selected' : '' ?>>Neurology
                            </option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <?php
                $days_of_week = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                foreach ($days_of_week as $day) {
                    $checked = in_array($day, $days) ? 'checked' : '';
                    echo "<label>$day</label> <input type='checkbox' name='days[]' value='$day' $checked>";
                }
                ?>
                    </div>
                    <div class="filter-group">
                        <label for="time_from">From:</label>
                        <input type="time" name="time_from" value="<?= htmlspecialchars($time_from) ?>">
                        <label for="time_to">To:</label>
                        <input type="time" name="time_to" value="<?= htmlspecialchars($time_to) ?>">
                    </div>
                    <div class="filter-group">
                        <select name="rating">
                            <option value="">All Ratings</option>
                            <option value="4" <?= $rating === '4' ? 'selected' : '' ?>>4+ Stars</option>
                            <option value="3" <?= $rating === '3' ? 'selected' : '' ?>>3+ Stars</option>
                            <option value="2" <?= $rating === '2' ? 'selected' : '' ?>>2+ Stars</option>
                            <option value="1" <?= $rating === '1' ? 'selected' : '' ?>>1+ Stars</option>
                            <option value="0" <?= $rating === '0' ? 'selected' : '' ?>>0+ Stars</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <select name="experience">
                            <option value="">All Experience</option>
                            <option value="0" <?= $experience === '0' ? 'selected' : '' ?>>Fresher</option>
                            <option value="2" <?= $experience === '2' ? 'selected' : '' ?>>2+ years</option>
                            <option value="5" <?= $experience === '5' ? 'selected' : '' ?>>5+ years</option>
                            <option value="10" <?= $experience === '10' ? 'selected' : '' ?>>10+ years</option>
                            <option value="15" <?= $experience === '15' ? 'selected' : '' ?>>15+ years</option>
                        </select>
                    </div>
                    <button type="submit" id="filter-btn">Apply Filter</button>
                </div>
            </form>
        </section>

        <marquee behavior="scroll" direction="left"
            style="font-size: 20px; font-weight: bold; color: white; background-color: #4CAF50; padding: 10px; margin: 15px 0;">
            Turn on location to see nearby doctors!
        </marquee>

        <section class="second-sec">
            <?php if($res->num_rows==0): ?>
            <p style="text-align:center;width:100%;">No doctor / clinic found.</p>
            <?php else:
            while($d = $res->fetch_assoc()):
                $img = $d['profile_img'] ? "../upload/doctor/".$d['profile_img'] : "../img/doc-img2.png"; ?>
            <!-- single card -->
            <div class="doc-card">
                <div class="doc-card-top">
                    <img src="<?= $img ?>" alt="Doctor">
                    <div class="doc-profile">
                        <h3><?= htmlspecialchars($d['doctor_name']) ?></h3>
                        <p id="specialization"><?= htmlspecialchars($d['specialization']) ?></p>
                        <div class="rating">
                            <i class="fas fa-star"></i>
                            <span id="ratings"><?= $d['rating'] ?> (<?= $d['total_reviews'] ?> reviews)</span>
                        </div>
                    </div>
                    <span id="status"><strong><?= ucfirst($d['availability']) ?></strong></span>
                </div>

                <div class="doc-card-middle">
                    <div><i class="fas fa-briefcase-medical"></i>
                        <p><?= $d['experience'] ?> years experience</p>
                    </div>
                    <div><i class="fas fa-hospital"></i>
                        <p><?= htmlspecialchars($d['clinic_name']) ?></p>
                    </div>
                    <div><i class="fas fa-map-marker-alt"></i>
                        <p><?= htmlspecialchars($d['area'] . ', ' . $d['city'] . ', ' . $d['state'] . ' - ' . $d['pincode']) ?>
                        </p>
                    </div>
                    <div><i class="fa-solid fa-indian-rupee-sign"></i>
                        <p><?= $d['fees'] ?> per consultation</p>
                    </div>
                    <div><i class="fas fa-clock"></i>
                        <p>Available Days: <?= $d['days_available'] ?></p>
                    </div>
                    <div><i class="fas fa-clock"></i>
                        <p>
                        <p>
                            <?= date("g:i A", strtotime($d['opening_time'])) ?> -
                            <?= date("g:i A", strtotime($d['closing_time'])) ?>
                        </p>

                    </div>
                    <div><i class="fa-solid fa-truck-medical"></i>
                        <p>Emergency: <?= ucfirst($d['emergency']) ?></p>
                    </div>
                </div>

                <div class="doc-card-bottom">
                    <a href="patient-bookAppointment.php?did=<?= $d['doctor_id'] ?>&cid=<?= $d['clinic_id'] ?>" class="book">Book Appointment</a>
                    <a href="view-doctorDetails.php?did=<?= $d['doctor_id'] ?>&cid=<?= $d['clinic_id'] ?>" class="view">View
                        Details</a>
                </div>

            </div>
            <?php endwhile; endif; ?>
        </section>

    </main>

    <?php include 'patient-footer.php'; ?>
    <script>
    function sendLocationToServer(lat, long) {
        fetch('store_location.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `latitude=${lat}&longitude=${long}`
            })
            .then(response => response.text())
            .then(data => {
                console.log('Location stored:', data);
                // Optional: Reload data after setting location
                // loadDoctorList(); 
            })
            .catch(err => console.error('Error storing location:', err));
    }

    function getUserLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const lat = position.coords.latitude;
                    const long = position.coords.longitude;
                    console.log('Location fetched:', lat, long);
                    sendLocationToServer(lat, long);
                },
                function(error) {
                    console.warn('Location error:', error.message);
                    fetch('unset_location.php'); // optional: unset session if failed
                }
            );
        } else {
            console.warn('Geolocation is not supported by this browser.');
        }
    }

    getUserLocation();
    </script>





</body>

</html>