<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Your Doctor | Healthcare Platform</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
    * {
        padding: 0;
        margin: 0;
        box-sizing: border-box;
    }

    /* hero section------------------ */
    .hero-sec {
        background: linear-gradient(90deg, #EFF6FF 0%, #DBEAFE 100%);
        padding: 20px;
    }

    .hero {
        height: 300px;
        width: 100%;
        padding: 30px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: flex-start;
        gap: 20px;
    }

    .hero-headline {
        font-size: 3.5em;
    }

    .hero-subline {
        font-size: 1.2em;
        color: #6B7280;
    }

    .search-box {
        display: flex;
        align-items: center;
        background-color: #fdfdfd;
        padding: 10px 20px;
        border-radius: 5px;
        width: 100%;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }

    .search-box i {
        color: #111827;
        font-size: 24px;
        margin-right: 10px;
    }

    .search-box input {
        border: none;
        outline: none;
        flex: 1;
        padding: 5px;
        background-color: transparent;
        color: #6B7280;
        margin-right: 10px;
    }

    .search-box button {
        padding: 10px 20px;
        background-color: #2563EB;
        border: none;
        color: white;
        border-radius: 5px;
    }

    /* first section----------------- */
    .first-sec {
        padding: 30px 30px;

    }

    .filter-group {
        display: flex;
        gap: 20px;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        background-color: #fdfdfd;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        padding: 20px;
        position: relative;
        top: -50px;
    }

    .filter-input {
        flex: 0 0 200px;
    }

    .filter-input select {
        width: 100%;
        padding: 10px;
        border: 1px solid #D1D5DB;
        border-radius: 5px;
        font-size: 14px;
    }


    /* second section------------------- */
    .second-sec {
        padding: 20px;

    }

    .doc-section {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
    }

    .doc-box {
        flex: 0 0 300px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: linear-gradient(180deg, #Fdfdfd 0%, #EFF6FF 100%);
        border-radius: 10px;
        box-shadow: 0 6px 10px rgba(0, 0, 0, 0.2);
    }

    .doc-box img {
        width: 100%;
        height: 200px;
        object-fit: fill;
    }

    .doc-box .doc-top {
        display: flex;
        align-items: center;
        width: 100%;
        justify-content: space-between;
        padding: 0 20px;
        margin-top: 10px;
    }

    .doc-top span {
        padding: 5px 8px;
        border-radius: 5px;
        font-size: 14px;
        background-color: #c7f6c7;
        color: #1aab1a;
    }

    .doc-box .doc-top-middle {
        padding: 0 20px;
        margin: 10px 0;
        width: 100%;
    }

    .doc-top-middle p {
        color: #4B5563;
        margin-bottom: 15px;
        font-size: 14px;
    }

    .doc-box .doc-middle {
        display: flex;
        align-items: center;
        padding: 0 20px;
        width: 100%;

    }

    .doc-middle p {
        flex: 1;
        color: #2563EB;
        font-weight: bold;
    }

    .doc-middle .rating {
        color: #ffb400;
        font-size: 10px;
    }

    .doc-middle span {
        color: #4B5563;
        font-size: 10px;
    }

    .doc-box .doc-bottom {
        padding: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 20px;
        width: 100%;
    }

    .doc-bottom a {
        flex: 0 0 120px;
        text-decoration: none;
        padding: 10px 15px;
        transition: all .3s ease;
        text-align: center;
        border-radius: 5px;
        font-size: 14px;
    }

    .doc-bottom a:hover {
        background-color: #173d8f;
        color: white;
    }

    .book {
        background-color: #2563EB;
        color: white;
    }

    .view {
        border: 1px solid #2563EB;
        color: #2563EB;
    }
    </style>
</head>

<body>
    <?php include 'header.php'; ?>
    <main>
        <section class="hero-sec">
            <div class="hero">
                <h1 class="hero-headline">Find Your Doctor</h1>
                <p class="hero-subline">Connect with top healthcare professionals for quality medical care</p>
                <div class="search-box">
                    <i class="fa-brands fa-searchengin"></i>
                    <input type="search" placeholder="Search doctors, Specialist, conditions......">
                    <button type="submit">Search</button>
                </div>
            </div>
        </section>

        <section class="first-sec">
            <div class="filter-group">
                <div class="filter-input">
                    <select class="specialization-dropdown">
                        <option>All Specializations</option>
                        <option>Cardiology</option>
                        <option>Dermatology</option>
                        <option>Neurology</option>
                    </select>
                </div>
                <div class="filter-input">
                    <select class="location-dropdown">
                        <option>All Locations</option>
                        <option>New York</option>
                        <option>Los Angeles</option>
                        <option>Chicago</option>
                    </select>
                </div>
                <div class="filter-input">
                    <select class="experience-dropdown">
                        <option>Experience</option>
                        <option>0-5 years</option>
                        <option>5-10 years</option>
                        <option>10+ years</option>
                    </select>
                </div>
                <div class="filter-input">
                    <div class="rating-stars">
                        <select class="ratings-dropdown">
                            <option>Ratings</option>
                            <option>0</option>
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                            <option>5</option>
                        </select>
                    </div>
                </div>
                <div class="filter-input">
                    <select class="fees-dropdown">
                        <option>All Fees</option>
                        <option>Less than $20</option>
                        <option>$20 - $50</option>
                        <option>$50 - $100</option>
                        <option>More than $100</option>
                    </select>
                </div>
                <div class="filter-input">
                    <select class="availability-dropdown">
                        <option>Availability</option>
                        <option>Available Today</option>
                        <option>Available Tomorrow</option>
                        <option>Available This Week</option>
                    </select>
                </div>
            </div>
        </section>

        <section class="second-sec">
            <div class="doc-section">
                <div class="doc-box">
                    <img src="./img/doc-img5.png" alt="Doctor">
                    <div class="doc-top">
                        <h3>Dr. John Smith</h3>
                        <span>Available</span>
                    </div>
                    <div class="doc-top-middle">
                        <p style="color: #2563EB; font-weight: bold;">Cardiologist</p>
                        <p>15 years of experience</p>
                    </div>
                    <div class="doc-middle">
                        <p>Rs: 500/-</p>
                        <div class="rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <span>(128 reviews)</span>
                    </div>
                    <div class="doc-bottom">
                        <a href="#" class="book">Appointment</a>
                        <a href="#" class="view">View Profile</a>
                    </div>
                </div>
            </div>
        </section>

    </main>
    <?php include 'footer.php'; ?>
</body>

</html>