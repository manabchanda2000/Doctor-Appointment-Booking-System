<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="animation.css">
    <title>HealthCare Platform - Find Doctors & Book Appointments</title>
    <style>
    * {
        padding: 0;
        margin: 0;
        box-sizing: border-box;
        font-family: 'Inter', sans-serif;
    }

    
    /* hero section------------------------ */
    .hero {
        width: 100%;
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
        background-color: white;
        padding: 30px 30px 0;
        animation: fadein 1.5s ease;
    }

    .hero-content {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 30px;
        width: 42%;
    }

    .headline {
        font-size: 3.5em;
        font-weight: bold;
    }

    .headline span {
        color: #2563EB;
    }

    .sub-line {
        font-size: 1.2em;
        color: #6B7280;
        text-align: justify;
    }

    .hero-buttons {
        display: flex;
        gap: 15px;
        align-items: center;
    }

    .btn {
        padding: 15px 35px;
        font-size: 1em;
        font-weight: bold;
        border-radius: 5px;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .find-doctors {
        background: #2563EB;
        color: white;
    }

    .emergency {
        border: 1px solid #eb2525;
        color: #eb2525;
    }

    .btn:hover {
        box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.12);
        scale: 1.01;
    }

    .hero-img {
        width: 42%;
    }

    .hero-img img {
        width: 100%;
    }

    /* Second section------------------------- */

    .second-sec {
        background-color: #f0f8ff;
        padding: 30px 20px;
    }

    .second-sec .user-count {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-around;
    }

    .user-count .user-count-box {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .user-count-box div {
        display: flex;
        gap: 10px;
        align-items: center;
        color: #4B5563;
    }

    .user-count-box .count-no {
        font-size: 1.8em;
        padding: 10px 10px;
        color: #2563EB;
    }

    .user-count-box .count-for {
        padding: 5px 10px;
        font-size: 16px;
    }

    /* third section---------------------- */

    .third-sec {
        padding: 20px 20px 80px;
        background-color: #fdfdfd;
        border: 3px solid #EFF6FF;
    }

    .how-work {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .heading {
        font-size: 2em;
        color: #1E3A8A;
        margin: 30px 0 10px;
        text-align: center;

    }

    .pera {
        font-size: 1em;
        color: #4B5563;
        text-align: center;
        margin-bottom: 20px;
    }

    .work-step {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 30px;
        flex-wrap: wrap;
        margin-top: 15px;
    }

    .work-step .step {
        flex: 0 0 400px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        padding: 30px;
        background-color: white;
        border-radius: 5px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: all .3s ease;
        cursor: pointer;
    }

    .work-step .step:hover {
        box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
        scale: 1.06;
        border: 1px solid #2563EB;
    }

    .step div {
        font-size: 34px;
        color: #d1d6e0;
        font-weight: bold;
        opacity: .5;
        letter-spacing: 5px;
    }

    .step i {
        color: #2563EB;
        position: relative;
        top: -30px;
        font-size: 24px;

    }

    .step h3 {
        color: #111827;
        font-size: 20px;
        margin-bottom: 20px;
    }

    .step p {
        color: #6B7280;
        font-size: 14px;
        text-align: center;
    }

    /* fourth section-------------------------------? */

    .fourth-sec {
        padding: 20px;
        text-align: center;
    }

    .feature {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
        padding: 20px 0;
        margin: 20px 0 50px;
        transform: translateY(200px);
        transition: all 0.6s ease-out;
    }

    .feature-box {
        background: linear-gradient(135deg, #EFF6FF -2%, #FFFFFF 100%);
        flex: 0 0 300px;
        padding: 20px;
        border-radius: 10px;
        width: 260px;
        text-align: left;
        transition: all .3s ease;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .feature-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }

    .feature-box span {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 40px;
        width: 40px;
        border-radius: 50%;
        background-color: #DBEAFE;
        margin-bottom: 10px;
    }

    .feature-box i {
        font-size: 24px;
        color: #2563EB;
    }

    .feature-box h3 {
        font-size: 1.1em;
        margin-bottom: 15px;
        color: #1E3A8A;
    }

    .feature-box p {
        font-size: 14px;
        color: #4B5563;
    }

    .feature.active {
        opacity: 1;
        transform: translateY(0);
    }

    /* section five------------------------------ */

    .fifth-sec {
        padding: 30px 20px;
    }

    .certified {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 50px 20px;
        border-radius: 5px;
        background: linear-gradient(90deg, #EFF6FF 0%, #FFFFFF 50%, #EFF6FF 100%);
        box-shadow: 0px 10px 15px rgba(0, 0, 0, 0.1);

    }

    .certified-content {
        margin-top: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 30px;
        flex-wrap: wrap;
    }

    .certified-box {
        flex: 0 0 250px;
        border: 1px solid #DBEAFE;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 20px;
        padding: 20px;
        border-radius: 5px;
        transition: all .3s ease;
    }

    .certified-box i {
        color: #2563EB;
        font-size: 24px;
    }

    .certified-box:hover {
        background-color: #EFF6FF;
        border: 1px solid #c3daf8;
    }

    /* section five----------------------------------- */

    .sixth-sec {
        padding: 20px;
    }

    .doctor-sec {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 30px;
        flex-wrap: wrap;
        padding: 20px 10px;
        margin: 20px 0;

    }

    .doc-card {
        background: #fdfdfd;
        padding: 20px;
        border-radius: 10px;
        border: 2px solid #DBEAFE;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
        align-items: center;
        transition: all .3s ease;
    }

    .doc-card:hover {
        scale: 1.03;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        border: 1px solid #0056b3;
    }

    .doc-card img {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        margin-bottom: 15px;
        border: 4px solid #DBEAFE;
    }

    .doc-card h3 {
        font-size: 1em;
    }

    .doc-card p {
        font-size: 14px;
        color: #4B5563;
        margin: 5px 0 10px;
    }

    .rating {
        display: flex;
        align-items: center;
        gap: 5px;
        color: #f39c12;
        font-size: 14px;
        margin-bottom: 15px;
    }

    .doc-btn {
        display: flex;
        gap: 20px;
        margin-top: 15px;
    }

    .doc-btn a {
        padding: 10px 15px;
        text-decoration: none;
        border-radius: 5px;
        text-align: center;
        transition: all 0.3s ease;
    }

    .book-doc {
        background: #2563EB;
        color: white;
    }

    .book-doc:hover,
    .view-more:hover {
        background: #0056b3;
        color: white;
    }

    .view-more {
        border: 2px solid #2563EB;
        color: #2563EB;
    }


    /* sixth section-------------------------- */

    .seven-sec {
        padding: 20px;
    }

    .review-sec {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .review-data {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 30px;
        flex-wrap: wrap;
        padding: 20px 10px;
        margin: 20px 0;
    }

    .review-box {
        flex: 0 0 400px;
        background: #fdfdfd;
        padding: 20px;
        border: 2px solid #DBEAFE;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        text-align: left;
        transition: 0.3s;
    }

    .review-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }

    .review-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 15px;

    }

    .review-header img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        border: 2px solid #DBEAFE;
        object-fit: cover;
    }

    .rating {
        margin: 5px 0;
    }

    .review-info {
        width: 100%;
        padding: 5px;
        background-color: whitesmoke;
        border-radius: 5px;
    }

    .review-info h4 {
        font-size: 16px;

    }

    .review-box p {
        font-size: 14px;
        color: #4B5563;
        line-height: 1.5;
        padding: 5px;
        border-radius: 5px;
        background-color: whitesmoke;
    }

    /* seven section--------------------------- */

    .eight-sec {
        padding: 20px;
    }

    .tips {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .tips-data {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 30px;
        flex-wrap: wrap;
        padding: 20px 10px;
        margin: 20px 0;
    }

    .tips-box {
        flex: 0 0 400px;
        background: #fdfdfd;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        text-align: left;
        border: 2px solid #DBEAFE;
        transition: 0.3s;
    }

    .tips-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }

    .tips-box img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: 10px 10px 0 0;
    }

    .tips-content {
        padding: 15px;
    }

    .tips-content h3 {
        font-size: 20px;
        margin-bottom: 10px;
    }

    .tips-content p {
        font-size: 14px;
        color: #4B5563;
        line-height: 1.5;
        margin-bottom: 10px;
    }

    .tips-content a {
        color: #2563EB;
        text-decoration: none;
        font-weight: bold;
        transition: all .3s ease;
    }

    .tips-content a:hover {
        text-decoration: underline;
    }

    /* eight section-------------------------------- */

    .nine-sec {
        padding: 20px;

    }

    .urgent-sec {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
        padding: 20px;
        border-radius: 10px;
        border: 2px solid #FECACA;
        background-color: #fde9e9;
        margin-top: 15px;
    }

    .urgent-help h3 {
        font-size: 1.2em;
        font-weight: bolder;
        color: red;
    }

    .urgent-help p {
        color: #4B5563;
    }

    .em-btn {
        background-color: #DC2626;
        border: none;
        padding: 10px 20px;
        color: white;
        border-radius: 5px;
        cursor: pointer;
        transition: all .3s ease;
    }
    .em-btn:hover{
        background-color: #c10000;
    }

    .view-all {
        padding: 10px 15px;
        background-color: #2563EB;
        color: white;
        text-decoration: none;
        float: right;
        border-radius: 5px;
        transition: all .3s ease;
    }

    .view-all:hover {
        background-color:rgb(25, 71, 170);
    }
    </style>
</head>

<body>
    <?php include 'header.php'; ?>
    <main>
        <section class="hero">
            <div class="hero-content">
                <h1 class="headline">Find the Best <span>Doctors Near You</span></h1>
                <p class="sub-line">"Book trusted healthcare appointments anytime, anywhere. Get
                    the care you deserve from experienced professionals in your
                    area."</p>
                <div class="hero-buttons">
                    <a href="./doctors.php" class="btn find-doctors"><i class="fa-solid fa-user-doctor"></i> Find Doctors</a>
                    <a href="./emergency-support.php" class="btn emergency"><i class="fa-solid fa-truck-medical"></i> Emergency Support</a>
                </div>
            </div>
            <div class="hero-img">
                <figure>
                    <img src="./img/home-hero.jpg" alt="">
                </figure>
            </div>
        </section>

        <section class="second-sec">
            <div class="user-count">
                <div class="user-count-box">
                    <h4 class="count-no">2,000+</h4>
                    <div>
                        <i class="fas fa-user-md"></i>
                        <p class="count-for">Qualified Doctors</p>
                    </div>

                </div>
                <div class="user-count-box">
                    <h4 class="count-no">50,000+</h4>
                    <div>
                        <i class="fas fa-smile"></i>
                        <p class="count-for">Happy Patients</p>
                    </div>
                </div>
                <div class="user-count-box">
                    <h4 class="count-no">100,000+</h4>
                    <div>
                        <i class="fas fa-calendar-check"></i>
                        <p class="count-for">Appointments Booked</p>
                    </div>
                </div>
                <div class="user-count-box">
                    <h4 class="count-no">200+</h4>
                    <div>
                        <i class="fas fa-hospital-alt"></i>
                        <p class="count-for">Partner Hospitals</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="third-sec">
            <div class="how-work">
                <h2 class="heading">How It Works</h2>
                <p class="pera">Simple steps to book your appointment</p>
                <div class="work-step">
                    <div class="step">
                        <div>01</div>
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <h3>Search Doctor</h3>
                        <p>Find the perfect specialist for your needs from our extensive network of qualified doctors.
                        </p>
                    </div>
                    <div class="step">
                        <div>02</div>
                        <i class="fa-solid fa-clock"></i>
                        <h3>Choose Time Slot</h3>
                        <p>Select a convenient time from available slots in the doctor's schedule</p>
                    </div>
                    <div class="step">
                        <div>03</div>
                        <i class="fas fa-calendar-check"></i>
                        <h3>Book Appointment</h3>
                        <p>Confirm your appointment with secure online booking system</p>
                    </div>
                </div>

            </div>
        </section>

        <section class="fourth-sec">
            <h2 class="heading">Our Features</h2>
            <p class="pera">Experience healthcare reimagined with our comprehensive suite of features<br>designed to
                make your medical journey seamless and efficient.</p>
            <div class="feature">
                <div class="feature-box">
                    <span><i class="fas fa-calendar-check"></i></span>
                    <h3>Easy Online Booking</h3>
                    <p>Schedule appointments with just a few clicks, 24/7 at your convenience.</p>
                </div>

                <div class="feature-box">
                    <span><i class="fas fa-user-md"></i></span>
                    <h3>Verified Doctors</h3>
                    <p>All our healthcare providers are thoroughly verified and credentialed.</p>
                </div>

                <div class="feature-box">
                    <span><i class="fas fa-location-dot"></i></span>
                    <h3>Location Search</h3>
                    <p>Find healthcare providers and facilities near you with ease.</p>
                </div>

                <div class="feature-box">
                    <span><i class="fas fa-stethoscope"></i></span>
                    <h3>Specialist Doctors</h3>
                    <p>Access to a wide network of specialized medical professionals.</p>
                </div>

                <div class="feature-box">
                    <span><i class="fas fa-headset"></i></span>
                    <h3>24/7 Support</h3>
                    <p>Round-the-clock assistance for all your healthcare needs.</p>
                </div>

                <div class="feature-box">
                    <span><i class="fas fa-hospital"></i></span>
                    <h3>Wide Clinic Network</h3>
                    <p>Access to thousands of clinics and hospitals nationwide.</p>
                </div>

                <div class="feature-box">
                    <span><i class="fas fa-heart-pulse"></i></span>
                    <h3>Health Tracking</h3>
                    <p>Monitor your health metrics and progress over time.</p>
                </div>

                <div class="feature-box">
                    <span><i class="fas fa-shield-halved"></i></span>
                    <h3>Secure Records</h3>
                    <p>Your medical data is protected with enterprise-grade security.</p>
                </div>
            </div>
        </section>

        <section class="fifth-sec">
            <div class="certified">
                <h2 class="heading">Trusted &amp; Certified</h2>
                <p class="pera">We maintain the highest standards of healthcare service delivery through
                    various<br>certifications and accreditations.</p>
                <div class="certified-content">
                    <div class="certified-box">
                        <i class="fas fa-certificate"></i>
                        <p>ISO Certified</p>
                    </div>

                    <div class="certified-box">
                        <i class="fas fa-globe"></i>
                        <p>WHO Approved</p>
                    </div>

                    <div class="certified-box">
                        <i class="fas fa-lock"></i>
                        <p>HIPAA Compliant</p>
                    </div>

                    <div class="certified-box">
                        <i class="fas fa-check-circle"></i>
                        <p>NABH Accredited</p>
                    </div>

                    <div class="certified-box">
                        <i class="fas fa-flask"></i>
                        <p>FDA Standards</p>
                    </div>

                    <div class="certified-box">
                        <i class="fas fa-users"></i>
                        <p>10,000+ Patients</p>
                    </div>

                    <div class="certified-box">
                        <i class="fas fa-user-md"></i>
                        <p>Certified Doctors</p>
                    </div>

                    <div class="certified-box">
                        <i class="fas fa-shield-alt"></i>
                        <p>GDPR Compliant</p>
                    </div>

                    <div class="certified-box">
                        <i class="fas fa-laptop-medical"></i>
                        <p>HealthTech Verified</p>
                    </div>

                    <div class="certified-box">
                        <i class="fas fa-star"></i>
                        <p>Top Rated</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="sixth-sec">
            <h2 class="heading">Top Rated Doctors</h2>
            <p class="pera">Find and book appointments with the best doctors in your area</p>
            <div class="doctor-sec">
                <div class="doc-card">
                    <img src="./img/doc.jpg" alt="Doctor">
                    <h3>Dr. Sarah Johnson</h3>
                    <p>Cardiologist</p>
                    <div class="rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                        <span>(128 reviews)</span>
                    </div>
                    <p>15 years experience</p>
                    <div class="doc-btn">
                        <a href="./doctors.php" class="book-doc">Book Now</a>
                        <a href="./doctors.php" class="view-more">View More</a>
                    </div>

                </div>
            </div>
            <a href="./doctors.php" class="view-all">View All →</a>
        </section>

        <section class="seven-sec">
            <div class="review-sec">
                <h2 class="heading">What Our Patients Say</h2>
                <p class="pera">See what our patients say about their experience</p>
                <div class="review-data">
                    <div class="review-box">
                        <div class="review-header">
                            <img src="./img/man.jpg" alt="Patient">
                            <div class="review-info">
                                <h4>Michael Brown</h4>
                                <div class="rating">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                </div>
                            </div>
                        </div>
                        <p>"Excellent service! The booking process was smooth, and the doctor was very professional.
                            Highly recommended!"</p>
                    </div>
                </div>
            </div>
            <a href="./review.php" class="view-all">View All →</a>
        </section>

        <section class="eight-sec">
            <div class="tips">
                <h2 class="heading">Health Tips &amp; Wellness Guides</h2>
                <p class="pera">Discover expert advice for a healthier lifestyle</p>
                <div class="tips-data">
                    <article class="tips-box">
                        <img src="./img/IMG.png" alt="Health Tips">
                        <div class="tips-content">
                            <h3>10 Tips for a Healthy Heart</h3>
                            <p>Learn the essential habits that can help maintain your heart health and prevent
                                cardiovascular diseases.</p>
                            <a href="./tips.php">Read More →</a>
                        </div>
                    </article>
                </div>
            </div>
            <a href="./tips.php" class="view-all">View All →</a>
        </section>

        <section class="nine-sec">
            <div class="urgent-sec">
                <div class="urgent-help">
                    <h3>Need Urgent Help?</h3>
                    <p>24/7 Emergency Support Available</p>
                </div>
                <button class="em-btn">
                    <i class="fa-solid fa-phone-flip"></i>
                    Call Emergency: 911
                </button>
            </div>
        </section>

    </main>
    <?php include 'footer.php'; ?>
    <script>
    document.addEventListener("DOMContentLoaded", () => {
        function startCounting(el, target) {
            let count = 0;
            let speed = Math.floor(target / 200); // Speed of count
            let interval = setInterval(() => {
                count += speed;
                if (count >= target) {
                    count = target;
                    clearInterval(interval);
                }
                el.innerText = count.toLocaleString() + "+"; // Format with commas
            }, 20);
        }

        function checkVisibility() {
            document.querySelectorAll(".count-no").forEach((counter) => {
                let target = parseInt(counter.innerText.replace(/[^0-9]/g, "")); // Extract numbers
                let position = counter.getBoundingClientRect().top;
                let windowHeight = window.innerHeight;

                if (position < windowHeight && counter.dataset.animated !== "true") {
                    counter.dataset.animated = "true";
                    startCounting(counter, target);
                }
            });
        }

        window.addEventListener("scroll", checkVisibility);
        checkVisibility();
    });

    // step animation
    document.addEventListener("DOMContentLoaded", function() {
        const steps = document.querySelectorAll(".step");

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.classList.add("active");
                    }, index * 1000); // 300ms delay between each step
                }
            });
        }, {
            threshold: 0.5
        });

        steps.forEach(step => observer.observe(step));
    });

    // our features animation
    document.addEventListener("DOMContentLoaded", function() {
        const features = document.querySelectorAll(".feature");

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.classList.add("active");
                    }, index * 500); // 300ms delay between each box
                }
            });
        }, {
            threshold: 0.5
        });

        features.forEach(feature => observer.observe(feature));
    });

    // doctor animation
    // document.addEventListener("DOMContentLoaded", function() {
    //     let track = document.querySelector(".doctor-sec");
    //     let cards = document.querySelectorAll(".doc-card");
    //     let cardWidth = cards[0].offsetWidth + 20;
    //     let totalCards = cards.length;

    //     function slideDoctors() {
    //         track.style.transition = "transform 0.5s ease-in-out";
    //         track.style.transform = `translateX(-${cardWidth}px)`;

    //         setTimeout(() => {
    //             track.style.transition = "none";
    //             let firstCard = track.firstElementChild;
    //             track.appendChild(firstCard);
    //             track.style.transform = "translateX(0)";
    //         }, 600);
    //     }

    //     setInterval(slideDoctors, 3000);
    // });
    </script>

</body>

</html>