<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emergency Requests Dashboard</title>
    <style>
        /* Reset and Body Styling */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f8fb; 
            color: #2e4450; 
            line-height: 1.6;
            padding: 0;
        }

        /* Sidebar and Header Fix */
        main {
            margin-left: 250px; /* Same width as the sidebar */
            padding: 30px;
            transition: 0.3s;
        }

        header {
            position: fixed;
            top: 0;
            left: 250px; /* Align with sidebar */
            right: 0;
            background: #16c2d5; 
            color: white;
            padding: 20px 30px;
            z-index: 1000;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        header h1 {
            font-size: 26px;
        }

        header div span {
            font-size: 24px;
            margin-left: 15px;
            cursor: pointer;
            transition: 0.3s;
        }

        header div span:hover {
            color: #89dee2;
        }

        /* Dashboard Stats */
        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin: 20px 0;
            transition: 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .statistics {
            display: flex;
            justify-content: space-between;
            text-align: center;
        }

        .statistics div {
            flex: 1;
            padding: 20px;
            margin: 0 10px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 5px 12px rgba(0, 0, 0, 0.05);
            transition: 0.3s;
        }

        .statistics div:hover {
            background: #16c2d5;
            color: white;
        }

        .statistics h2 {
            font-size: 20px;
            margin-bottom: 10px;
        }

        .statistics p {
            font-size: 28px;
            font-weight: bold;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        table, th, td {
            border: 1px solid #e1e1e1;
        }

        th, td {
            padding: 15px;
            text-align: left;
        }

        th {
            background: #16c2d5;
            color: white;
        }

        tr:hover {
            background: #f1faff;
        }

        .btn {
            padding: 8px 18px;
            border: none;
            border-radius: 8px;
            color: white;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn:hover {
            opacity: 0.85;
        }

        .accept {
            background-color: #28a745; /* Green */
        }

        .update {
            background-color: #007bff; /* Blue */
        }

        .pending {
            background-color: #ffc107; /* Yellow */
            color: #2e4450;
            padding: 5px 12px;
            border-radius: 6px;
        }

        .in-progress {
            background-color: #ff6347; /* Tomato */
            color: white;
            padding: 5px 12px;
            border-radius: 6px;
        }

        /* Search Input */
        #search {
            width: 100%;
            padding: 12px;
            margin: 20px 0;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
        }

        #search:focus {
            outline: none;
            border-color: #16c2d5;
            box-shadow: 0 0 8px rgba(22, 194, 213, 0.5);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            main {
                margin-left: 0;
            }

            header {
                left: 0;
                width: 100%;
            }

            .statistics {
                flex-direction: column;
            }

            .statistics div {
                margin: 10px 0;
            }
        }
    </style>
</head>
<body>

<?php include 'doctor-header.php' ?>
<?php include 'doctor-sidebar.php' ?>

<main>
    <div class="card statistics">
        <div>
            <h2>Pending Requests</h2>
            <p>12</p>
        </div>
        <div>
            <h2>In Progress</h2>
            <p>8</p>
        </div>
        <div>
            <h2>Completed Today</h2>
            <p>24</p>
        </div>
    </div>

    <input type="text" id="search" placeholder="Search requests...">

    <table>
        <thead>
            <tr>
                <th>Request ID</th>
                <th>Patient</th>
                <th>Type</th>
                <th>Location</th>
                <th>Contact</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>#ER1024</td>
                <td>John Doe<br>42 years</td>
                <td>Heart Attack</td>
                <td>New York, NY</td>
                <td>+1234567890</td>
                <td><span class="pending">Pending</span></td>
                <td>
                    <button class="btn accept" onclick="handleAccept()">Accept</button>
                </td>
            </tr>
            <tr>
                <td>#ER1025</td>
                <td>Jane Smith<br>28 years</td>
                <td>High Fever</td>
                <td>Los Angeles, CA</td>
                <td>+9876543210</td>
                <td><span class="in-progress">In Progress</span></td>
                <td>
                    <button class="btn update" onclick="handleUpdate()">Update</button>
                </td>
            </tr>
        </tbody>
    </table>
</main>

<script>
    // Search functionality
    document.getElementById('search').addEventListener('keyup', function () {
        const searchValue = this.value.toLowerCase();
        const rows = document.querySelectorAll('tbody tr');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchValue) ? '' : 'none';
        });
    });

    function handleAccept() {
        alert('Request Accepted!');
    }

    function handleUpdate() {
        alert('Request Updated!');
    }
</script>

</body>
</html>
