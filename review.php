<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Reviews & Testimonials | HealthCare Platform</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
    * {
        padding: 0;
        margin: 0;
        box-sizing: border-box;
    }


    /* hero section-------------------- */
    .hero-sec {
        padding: 30px;
        background: linear-gradient(180deg, #dae5f5 0%, #FFFFFF 100%);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .hero {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 20px;
        height: 300px;
    }

    .hero-headline {
        font-size: 3.5em;
    }

    .hero-subline {
        font-size: 1.2em;
        color: #4B5563;
    }

    .hero-btn {
        padding: 15px 30px;
        background-color: #2563EB;
        color: #FFFFFF;
        border-radius: 5px;
        cursor: pointer;
        border: transparent;
        font-weight: bold;
        transition: all .3s ease;
    }

    .hero-btn:hover {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        background-color: #116cbb;
    }

    /* second section-------------- */
    .second-sec {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 20px;
        padding: 10px;
        position: relative;
        top: -30px;
        flex-wrap: wrap;
    }

    .second-sec select {
        flex: 0 0 150px;
        padding: 10px;
        border: 1px solid #4B5563;
        background-color: #fdfdfd;
        border-radius: 5px;
    }

    /* third section------------------ */
    .third-sec {
        padding: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
        gap: 20px;
    }

    .reviw-box {
        flex: 0 0 320px;
        background: #fdfdfd;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.1);
    }

    /* Top Section */
    .box-top {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 5px;
    }

    .box-top img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
    }

    .top-name h3 {
        font-size: 16px;
        margin-bottom: 5px;
    }

    .top-name .rating {
        font-size: 12px;
        color: #ffb400;
    }

    /* Middle Section */
    .box-middle {
        margin: 10px 0;
        font-size: 14px;
        color: #4B5563;
        text-align: justify;
    }

    /* Bottom Section */
    .box-buttom {
        display: flex;
        justify-content: space-between;
        font-size: 12px;
        color: #4B5563;
        align-items: center;
    }

    /* Like Button */
    .box-buttom button {
        background: none;
        border: none;
        cursor: pointer;
        color: #4B5563;
    }

    .box-buttom button i {
        margin-right: 5px;
    }

    /* fifth section------------- */
    /* Video Review Section */
    .fifth-sec {
        padding: 40px 60px;
        margin-bottom: 20px;
    }

    .video-review {
        display: flex;
        align-items: flex-start;
        flex-direction: column;

    }

    /* Title Styling */
    .video-review h2 {
        font-size: 1.5em;
        margin-bottom: 20px;
    }

    /* Video Box */
    .video-box {
        border-radius: 10px;
        overflow: hidden;

    }

    /* Video Thumbnail */
    .video-box img {
        width: 35%;
        border-radius: 10px;
        cursor: pointer;
        transition: transform 0.3s ease-in-out;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    }

    .video-box img:hover {
        transform: scale(1.05);
    }
    </style>
</head>

<body>
    <?php include 'header.php'; ?>
    <main>
        <section class="hero-sec">
            <div class="hero">
                <h1 class="hero-headline">What Our Patients Say</h1>
                <p class="hero-subline">See what patients say about our doctors and services. Your feedback helps us
                    improve healthcare for
                    everyone.</p>
                <button class="hero-btn">Share Your Experience</button>
            </div>
        </section>

        <section class="second-sec">
            <select>
                <option>All Ratings</option>
                <option>1</option>
                <option>2</option>
                <option>3</option>
                <option>4</option>
                <option>5</option>
            </select>
            <select>
                <option>Sort by</option>
                <option>Newest</option>
                <option>Oldest</option>
            </select>
            <select>
                <option>likes</option>
                <option>Most like</option>
                <option>Less like</option>
            </select>
        </section>

        <section class="third-sec">
            <div class="reviw-box">
                <div class="box-top">
                    <img src="./img/patient.png" alt="Patient">
                    <div class="top-name">
                        <h3>Sarah Johnson</h3>
                        <div class="rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                    </div>
                </div>
                <div class="box-middle">
                    <p>Dr. Smith was incredibly thorough and patient during my consultation. He took the time to explain
                        everything
                        clearly and answer all my questions. The entire experience was excellent.</p>
                </div>
                <div class="box-buttom">
                    <button><i class="fa-regular fa-thumbs-up"></i>24</button>
                    <span>2 days ago</span>
                </div>
            </div>
        </section>

        <section class="fifth-sec">
            <div class="video-review">
                <h2>Video Testimonials</h2>
                <div class="video-box">
                    <img src="./img/video-review.png" alt="video">
                </div>
            </div>
        </section>
    </main>
    <?php include 'footer.php'; ?>
</body>

</html>