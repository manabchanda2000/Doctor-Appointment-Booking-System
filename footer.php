<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Footer</title>
    <style>
        *{
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }
        .footer-container {
            display: flex;
            justify-content: space-around;
            align-items: flex-start;
            flex-wrap: wrap;
            background-color: #1F2937;
            color: white;
            padding: 40px 20px;
        }

        .footer-left {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .footer-left img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
        }

        .footer-left h3 {
            font-size: 2em;
            margin-bottom: 10px;
            background: linear-gradient(90deg, #2563EB, #4F46E5);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .footer-left p {
            line-height: 1.5;
            color: #D1D5DB;
        }
        .f-heading{
            font-size: 18px;
            color: white;
            margin-bottom: 10px;
        }

        .footer-middle-left ul {
            list-style: none;
            padding: 0;
        }

        .footer-middle-left ul li {
            margin-bottom: 5px;
        }

        .footer-middle-left ul li a {
            text-decoration: none;
            color: #D1D5DB;
            transition: 0.3s;
        }

        .footer-middle-left ul li a:hover {
            color: #2563EB;
        }
        .social-icons{
            margin-top: 20px;
            display: flex;
            gap: 10px;
        }
        .social-icons a {
            color: white;
            text-decoration: none;
            background: linear-gradient(90deg, #2563EB, #4F46E5);
            padding: 10px;
            border-radius: 50%;
            font-size: 18px;
            transition: 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 40px;
            width: 40px;
        }

        .social-icons a:hover {
            scale: 1.1;
        }

        .footer-right p {
            margin-bottom: 5px;
            color: #D1D5DB;
        }

        .footer-right i {
            margin-right: 8px;
            color: #2563EB;
            line-height: 1.5;
        }

        .footer-bottom {
            background-color: #1F2937;
            text-align: center;
            padding: 30px;
            font-size: 12px;
            border-top: 1px solid #333;
            color: #D1D5DB;
            font-size: 16px;
        }
    </style>
</head>

<body>
    <footer>
        <div class="footer-container">
            <div class="footer-left">
                <img src="./img/logo.jpg" alt="HealthCare Logo">
                <h3>Apna Health</h3>
                <p>Your trusted partner for finding the best doctors and booking appointments easily.</p>
            </div>

            <div class="footer-middle-left">
                <h4 class="f-heading">Quick Links</h4>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="about.php">About</a></li>
                    <li><a href="doctors.php">Doctors</a></li>
                    <li><a href="clinic.php">Clinics</a></li>
                    <li><a href="tips.php">Health Tips</a></li>
                    <li><a href="review.php">Reviews</a></li>
                    <li><a href="notice.php">Announcements</a></li>
                    <li><a href="contact.php">Contact</a></li>
                </ul>
            </div>

            <div class="footer-middle-right">
                <h4 class="f-heading">Follow Us</h4>
                <div class="social-icons">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>
            <div class="footer-right">
                <h4 class="f-heading">Contact Us</h4>
                <p><i class="fas fa-phone"></i> +123 456 7890</p>
                <p><i class="fas fa-envelope"></i> support@healthcare.com</p>
                <p><i class="fas fa-map-marker-alt"></i> 123 Health St, New York, USA</p>
            </div>

        </div>

        <div class="footer-bottom">
            <p>&copy; 2025 Apna health. All rights reserved.</p>
        </div>
    </footer>

</body>

</html>