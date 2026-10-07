<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Health Tips & Expert Advice</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
    * {
        padding: 0;
        margin: 0;
        box-sizing: border-box;
    }

    .hero-sec {
        background-image: url('./img/tips-back.jpg');
        background-size: contain;
        background-position: center;
        position: relative;
        color: #fff;
        opacity: 1;
        background-color: rgba(0, 0, 0, 0);
    }

    .hero {
        height: 500px;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        opacity: 0.8;
        background: #111827;
        padding: 40px;
    }

    .hero-headline {
        font-size: 3.5em;
        font-weight: bold;
        line-height: 1.2;
        margin-bottom: 20px;
    }

    .hero-headline span {
        display: block;
        text-align: center;
    }

    .hero-subline {
        font-size: 1.2em;
        margin-bottom: 30px;
        color: #6B7280;

    }

    .log-req {
        background-color: #fdfdfd;
        padding: 10px;
    }

    .log-req p {
        color: black;
    }

    .log-req a {
        text-decoration: none;
        color: #2563EB;
        font-weight: bold;
    }

    /* second section--------------------- */
    .second-sec {
        padding: 20px;
    }

    .select {
        display: flex;
        align-items: center;
        gap: 20px;
        background-color: #fdfdfd;
        padding: 10px;
        border-radius: 5px;
    }

    .select input {
        padding: 10px;
        flex: 1;
        background-color: #fdfdfd;
        border: 1px solid #6B7280;
        border-radius: 5px;
        color: #6B7280;
    }

    .select select {
        padding: 10px;
        background-color: #fdfdfd;
        border: 1px solid #6B7280;
        border-radius: 5px;
        color: #6B7280;
    }

    /* third section----------------------- */
    .third-sec {
        padding: 20px;
    }

    .doc-tips {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
    }

    .tips-box {
        flex: 0 0 320px;
        background-color: #fdfdfd;
        border-radius: 5px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
    }

    .tips-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 12px rgba(0, 0, 0, 0.2);
    }

    .tips-box img {
        width: 100%;
        aspect-ratio: 2;
        object-fit: fill;
    }

    .box-top {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
        gap: 5px;
        padding: 0 10px;
    }

    .box-top img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 10px;
    }

    .doc-data h3 {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .doc-data p {
        font-size: 14px;
        color: #2563EB;
    }

    .box-middle {
        padding: 0 10px;
    }

    .box-middle h4 {
        font-size: 18px;
        font-weight: 500;
        margin-bottom: 10px;
    }

    .box-middle p {
        font-size: 14px;
        color: #6B7280;
        line-height: 1.2;
        text-align: justify;
    }

    .box-bottom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 10px;
        padding: 0 10px 10px;
    }

    .box-bottom a {
        flex: 1;
        text-decoration: none;
        color: #2563EB;
        font-size: 14px;
        transition: all .3s ease;
    }

    .box-bottom button {
        display: flex;
        align-items: center;
        gap: 5px;
        background-color: transparent;
        border: none;
        color: #007BFF;
        font-size: 12px;
        cursor: pointer;
        margin-right: 5px;
    }

    .box-bottom a:hover {
        font-weight: bolder;
    }

    .box-bottom span {
        font-size: 12px;
        color: #6B7280;
    }
    </style>
</head>
<bod>
    <?php include 'header.php'; ?>
    <main>
        <section class="hero-sec">
            <div class="hero">
                <h1 class="hero-headline">
                    <span>Stay Healthy with Our</span>
                    <span style="color: #2563EB;">Expert Tips</span>
                </h1>
                <p class="hero-subline">
                    Get personalized health advice from leading medical professionals. Discover ways to improve your
                    lifestyle and wellbeing.
                </p>
                <marquee class="log-req" scrollamount="8" onmouseover="this.stop();" onmouseout="this.start();">
                    <p>Please <a href="./patient/patient-login.html">login</a> to ask any Question to our expert. If you
                        have no account <a href="./patient/patient-signup.html">Register</a> yourself.
                        <span>Thankyou</span>
                    </p>
                </marquee>
            </div>
        </section>

        <section class="second-sec">
            <div class="select">
                <input type="search" placeholder="Search health tips........">
                <select name="" id="catagory">
                    <option>All Specializations</option>
                    <option>Cardiology</option>
                    <option>Dermatology</option>
                    <option>Neurology</option>
                </select>
                <select name="" id="short-by">
                    <option value="">Oldest</option>
                    <option value="">Newest</option>
                </select>
            </div>
        </section>

        <section class="third-sec">
            <div class="doc-tips">
                <div class="tips-box">
                    <img src="./img/IMG.png" alt="tips">
                    <div class="box-top">
                        <img src="./img/doc-img2.png" alt="Doctor">
                        <div class="doc-data">
                            <h3>Dr. Sarah Johnson</h3>
                            <p>Cardiologist</p>
                        </div>
                    </div>
                    <div class="box-middle">
                        <h4>What are the best ways to maintain heart health?</h4>
                        <p>Regular exercise, a balanced diet rich in fruits and vegetables, maintaining healthy weight,
                            and
                            regular
                            check-ups are essential...</p>
                    </div>
                    <div class="box-bottom">
                        <a href="#">Read More</a>
                        <button><i class="fa-regular fa-thumbs-up"></i> 245</button>
                        <span>2 days ago</span>
                    </div>
                </div>
            </div>
        </section>

    </main>
    <script>
        function stopMarquee(marquee) {
            marquee.stop();
        }

    function startMarquee(marquee) {
        marquee.resume();
    }
    </script>
    <?php include 'footer.php'; ?>
    </body>

</html>