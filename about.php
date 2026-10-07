<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - HealthCare Platform</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
    * {
        padding: 0;
        margin: 0;
        box-sizing: border-box;
    }

    /* hero section------------------- */
    .hero-sec {
        background: url('./img/about-hero.png') no-repeat center center/cover;
        opacity: 1;
        background-color: rgba(0, 0, 0, 0);
    }

    .hero {
        height: 550px;
        width: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        color: white;
        opacity: 0.8;
        background: #111827;
    }

    .hero-sec .hero-headline {
        font-size: 3.5em;
        font-weight: bold;
        margin-bottom: 15px;
    }

    .hero-sec .hero-subline {
        font-size: 1.2em;
        max-width: 650px;
        text-align: center;
        line-height: 1.6;
        color: #F3F4F6;
    }


    /* second section-----------------? */
    .second-sec {
        padding: 30px 15px;
    }

    .about {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 40px;
        padding: 10px 50px;
    }

    .about-box {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        padding: 50px 15px;
        max-width: 50%;
    }

    .about-heading {
        font-size: 2em;
        color: #1E3A8A;
        font-weight: bold;
        margin-bottom: 15px;
    }

    .about-line {
        font-size: 1em;
        line-height: 1.6;
        color: #6B7280;
        margin-bottom: 15px;
    }

    .about-why {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
    }

    .about-why div {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
        padding: 15px;
    }

    .about-why div span {
        height: 40px;
        width: 40px;
        background-color: #DBEAFE;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .about-why div span i {
        font-size: 20px;
        color: #3B82F6;
    }

    .about-why div p {
        font-size: 14px;
        color: #6B7280;
    }

    .about-img {
        max-width: 50%;
    }

    .about-img img {
        max-width: 100%;
        border-radius: 10px;
    }

    /* third section------------------------------- */

    .third-sec {
        padding: 20px;
    }

    .mis-vis {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 20px;
        padding: 0 15px 20px;
    }

    .heading {
        font-size: 2em;
        color: #1E3A8A;
        ;
        margin: 20px 0;
        font-weight: bold;
    }

    .mis-vis-info {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 40px;
    }

    .mission,
    .vision {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 20px;
        background-color: #fdfdfd;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 1px 2px 5px rgba(0, 0, 0, 0.1);
    }

    .mission img,
    .vision img {
        width: 20px;

    }

    .mission-data,
    .vision-data {
        display: flex;
        align-items: center;
    }

    .mission-data span,
    .vision-data span {
        height: 40px;
        width: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #DBEAFE;
        border-radius: 50%;
        margin-right: 10px;
    }

    .mission-data h3,
    .vision-data h3 {
        font-size: 1em;
    }

    .mission p,
    .vision p {
        font-size: 14px;
        color: #4B5563;
    }

    /* forth section----------------------------- */

    .forth-sec {
        padding: 30px 20px;
        background-color: #e7f0fc;
    }

    .chose-us {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }


    .chose-body {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 30px;
        flex-wrap: wrap;
        padding: 20px 0;
    }

    .chose-box {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
        flex: 0 0 420px;
        box-shadow: 2px 2px 4px rgba(17, 24, 39, 0.1);
        padding: 20px;
        background-color: #fdfdfd;
        border-radius: 5px;
        transition: all .3s ease;
    }

    .chose-box:hover {
        transform: translateY(-5px);
    }

    .chose-box div {
        height: 40px;
        width: 40px;
        border-radius: 50%;
        background-color: #DBEAFE;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .chose-box i {
        font-size: 20px;
        color: #3B82F6;
    }

    .chose-box h3 {
        font-size: 1em;
    }

    .chose-box p {
        color: #6B7280;
        font-size: 14px;
    }

    /* fifth section-------------------------- */

    .fifth-sec {
        padding: 20px;
    }

    .team {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .pera {
        color: #6B7280;
        font-size: 16px;
        margin-bottom: 15px
    }

    .team-body {
        padding: 10px 0 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
    }

    .team-box {
        flex: 0 0 300px;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        border-radius: 10px;
        gap: 20px;
        background-color: #fdfdfd;
        box-shadow: 0px 6px 10px rgba(0, 0, 0, 0.2);
        transition: all .3s ease;
    }

    .team-box:hover {
        scale: 1.03;
        box-shadow: 0px 6px 10px rgba(0, 0, 0, 0.25);
    }

    .team-box img {
        width: 100%;
        height: 200px;
        object-fit: fill;
        border-radius: 10px 10px 0 0;
    }

    .team-box div {
        padding: 0 15px 15px;
    }

    .team-box h3 {
        font-size: 18px;
        margin-bottom: 8px;
    }

    .team-box p {
        font-size: 16px;
        margin-bottom: 10px;
    }

    .team-box .line {
        color: #6B7280;
        font-size: 14px;
    }

    .team-box a {
        text-decoration: none;
        padding: 8px 10px;
        width: 100%;
        border-radius: 20px;
        color: white;
        display: block;
        text-align: center;
        background-color: #2563EB;
        transition: all .3s ease;
    }

    .team-box a:hover {
        background-color: #1b48ab;
    }

    /* sixth section----------------------- */
    .sixth-sec {
        padding: 30px 20px;
    }

    .achievement {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .achiv-body {
        padding: 20px 0px;
        display: flex;
        align-items: center;
        justify-content: space-around;
        flex-wrap: wrap;
        gap: 20px;
        width: 100%;
    }

    .achiv-box {
        flex: 0 0 300px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
        padding: 20px;
        background-color: #fdfdfd;
        box-shadow: 0 6px 10px rgba(0, 0, 0, 0.2);
        border-radius: 5px;
    }

    .achiv-box i {
        font-size: 24px;
        color: #2563EB;
    }

    .achiv-box h3 {
        font-size: 1.5em;
    }

    .achiv-box p {
        font-size: 16px;
        color: #4B5563;
    }
    </style>
</head>

<body>
    <?php include 'header.php'; ?>
    <main>
        <section class="hero-sec">
            <div class="hero">
                <h1 class="hero-headline">About Us - Your Trusted Healthcare Partner</h1>
                <p class="hero-subline">We connect you with top doctors for easy and reliable healthcare services,
                    ensuring quality care
                    is always within reach.</p>
            </div>
        </section>

        <section class="second-sec">
            <div class="about">
                <div class="about-box">
                    <h2 class="about-heading">Who We Are</h2>
                    <p class="about-line">
                        We are a dedicated healthcare platform committed to revolutionizing the way people access
                        medical care. Our mission is to break down barriers between patients and healthcare
                        providers,
                        making quality healthcare accessible to everyone.
                    </p>
                    <p class="about-line">
                        With a network of verified healthcare professionals and state-of-the-art digital
                        infrastructure,
                        we're building the future of healthcare delivery - one that's more convenient, transparent,
                        and
                        patient-centric.
                    </p>
                    <div class="about-why">
                        <div>
                            <span><i class="fas fa-certificate"></i></span>
                            <h3>ISO Certified</h3>
                            <p>Quality Assured</p>
                        </div>
                        <div>
                            <span><i class="fas fa-users"></i></span>
                            <h3>100k+</h3>
                            <p>Patients Served</p>
                        </div>
                        <div>
                            <span><i class="fas fa-user-md"></i></span>
                            <h3>500+</h3>
                            <p>Expert Doctors</p>
                        </div>
                    </div>

                </div>
                <div class="about-img">
                    <img src="./img/about-1.png" alt="Healthcare Illustration">
                </div>
            </div>
        </section>

        <section class="third-sec">
            <div class="mis-vis">
                <h2 class="heading">Our Mission & Vision</h2>
                <div class="mis-vis-info">
                    <div class="mission">
                        <div class="mission-data">
                            <span><img src="./img/mision.png" alt="mission"></span>
                            <h3>Our Mission</h3>
                        </div>
                        <p>To make quality healthcare accessible to everyone through innovative technology and a
                            network of trusted healthcare providers.</p>
                    </div>
                    <div class="vision">
                        <div class="vision-data">
                            <span><img src="./img/vision.png" alt="vision"></span>
                            <h3>Our Vision</h3>
                        </div>
                        <p>To be the world's most trusted healthcare platform, where every person can access
                            quality care at their convenience.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="forth-sec">
            <div class="chose-us">
                <h2 class="heading">Why Choose Us</h2>
                <div class="chose-body">
                    <div class="chose-box">
                        <div>
                            <i class="fas fa-user-md"></i>
                        </div>
                        <h3>Verified Doctors</h3>
                        <p>All our healthcare professionals are thoroughly verified and credentialed to ensure the
                            highest quality of care.</p>
                    </div>
                    <div class="chose-box">
                        <div>
                            <i class="fas fa-clock"></i>
                        </div>
                        <h3>24/7 Support</h3>
                        <p>Round-the-clock assistance ensures you're never alone in your healthcare journey.</p>
                    </div>
                    <div class="chose-box">
                        <div>
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <h3>Easy Booking</h3>
                        <p>Schedule appointments with just a few clicks through our intuitive booking system.</p>
                    </div>
                    <div class="chose-box">
                        <div>
                            <i class="fas fa-heart"></i>
                        </div>
                        <h3>Patient-Centered Care</h3>
                        <p>Your health and comfort are our top priorities, with personalized care plans for every
                            patient.</p>
                    </div>
                    <div class="chose-box">
                        <div>
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3>Data Privacy</h3>
                        <p>Your medical information is protected with state-of-the-art security measures.</p>
                    </div>
                    <div class="chose-box">
                        <div>
                            <i class="fas fa-ambulance"></i>
                        </div>
                        <h3>Emergency Help</h3>
                        <p>Quick access to emergency services and immediate medical attention when needed.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="fifth-sec">
            <div class="team">
                <h2 class="heading">Meet Our Team</h2>
                <p class="pera">The dedicated professionals behind our healthcare platform</p>
                <div class="team-body">
                    <div class="team-box">
                        <img src="./img/doc-img5.png" alt="Dr. Sarah Johnson">
                        <div>
                            <h3>Michael Chen</h3>
                            <p>Chief Medical Officer</p>
                            <p class="line">20+ years of experience in healthcare management and patient care</p>
                            <a href="#">View Profile</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="sixth-sec">
            <div class="achievement">
                <h2 class="heading">Our Achievements</h2>
                <p class="pera">Milestones We're Proud Of</p>
                <div class="achiv-body">
                    <div class="achiv-box">
                        <i class="fas fa-user-md"></i>
                        <h3>50,000+</h3>
                        <p>Happy Patients</p>
                    </div>
                    <div class="achiv-box">
                        <i class="fas fa-certificate"></i>
                        <h3>100+</h3>
                        <p>Expert Doctors</p>
                    </div>
                    <div class="achiv-box">
                        <i class="fas fa-hospital"></i>
                        <h3>25</h3>
                        <p>Years Experience</p>
                    </div>
                    <div class="achiv-box">
                        <i class="fas fa-award"></i>
                        <h3>98%</h3>
                        <p>Success Rate</p>
                    </div>
                </div>
            </div>
        </section>

    </main>
    <?php include 'footer.php'; ?>

</body>

</html>