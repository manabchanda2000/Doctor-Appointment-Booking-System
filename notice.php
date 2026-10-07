<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements & Updates | Healthcare Platform</title>
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
        padding: 20px;
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
        height: 200px;
    }

    .hero-headline {
        font-size: 3.5em;
    }

    .hero-subline {
        font-size: 1.2em;
        color: #4B5563;
    }

    /* second section--------------------- */
    .second-sec {
        padding: 0 40px;
        position: relative;
        top: -40px;
    }

    .notice-sf {
        display: flex;
        align-items: center;
        background: #fdfdfd;
        padding: 15px 12px;
        margin-bottom: 20px;
        width: 100%;
        gap: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .notice-sf i {
        color: #4B5563;
        margin-right: 8px;
    }

    .search {
        display: flex;
        align-items: center;
        background: #fdfdfd;
        border: 1px solid #BFDBFE;
        padding: 10px 12px;
        width: 100%;
        border-radius: 6px;
    }

    .notice-sf input {
        border: none;
        outline: none;
        width: 100%;
        font-size: 14px;
    }

    /* Filter Dropdowns */
    .filter {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .filter select {
        border: 1px solid #BFDBFE;
        padding: 10px 12px;
        font-size: 14px;
        border-radius: 6px;
        background: #fdfdfd;
        cursor: pointer;
    }

    /* third section--------------- */
    .third-sec {
        padding: 0 20px 20px;
    }

    .notice-sec {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 20px;
    }

    .left {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
        width: 70%;
    }

    .artical-box {
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
        padding: 20px;
        background-color: #fdfdfd;
        border-radius: 8px;
        border: 1px solid #BFDBFE;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }

    .artical-top #notice-for {
        color: #991B1B;
        background-color: #FEE2E2;
        border-radius: 5px;
        padding: 5px;
    }

    .artical-top span {
        color: #6B7280;
        margin-right: 5px;
        font-size: 12px;
    }

    .artical-middle {
        padding-right: 30px;
        margin-bottom: 15px;
    }

    .artical-middle h3 {
        font-size: 18px;
        margin-bottom: 10px;
    }

    .artical-middle p {
        font-size: 15px;
        color: #4B5563;
    }

    .artical-buttom a {
        padding: 10px 15px;
        text-decoration: none;
        color: #007BFF;
        transition: all .3s ease;
    }

    .artical-buttom a:hover {
        color: #003268;
        font-weight: bolder
    }

    /* Categories Box */
    .right {
        background-color: #fdfdfd;
        border: 1px solid #BFDBFE;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        width: 30%;
        position: sticky;
        top: 20px;
    }

    /* Title */
    .right h3 {
        font-size: 18px;
        margin-bottom: 15px;
    }

    /* Category Buttons */
    .right button {
        display: flex;
        justify-content: space-between;
        width: 100%;
        padding: 10px 15px;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        background: transparent;
        color: #4B5563;
        cursor: pointer;
        transition: all 0.3s ease-in-out;
    }

    .right button:hover {
        background: #f0f0f0;
    }

    .right span {
        height: 30px;
        width: 30px;
        text-align: center;
        vertical-align: middle;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    </style>
</head>

<body>
    <?php include 'header.php'; ?>
    <main>
        <section class="hero-sec">
            <div class="hero">
                <h1 class="hero-headline">Latest Announcements & Notices</h1>
                <p class="hero-subline">Stay updated with the latest news, updates, and important information.</p>
            </div>
        </section>

        <section class="second-sec">
            <div class="notice-sf">
                <div class="search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" placeholder="Search announcements...">
                </div>
                <div class="filter">
                    <select>
                        <option>All Categories</option>
                        <option>Emergency Alerts</option>
                        <option>Healthcare Updates</option>
                        <option>Maintenance</option>
                    </select>
                    <select>
                        <option>Latest First</option>
                        <option>Oldest First</option>
                    </select>
                </div>
            </div>
        </section>

        <section class="third-sec">
            <div class="notice-sec">
                <div class="left">
                    <article class="artical-box">
                        <div class="artical-top">
                            <span id="notice-for">Emergency</span>
                            <span>July 10, 2024</span>
                            <span>•</span>
                            <span>Posted by Admin</span>
                        </div>
                        <div class="artical-middle">
                            <h3>New COVID-19 Guidelines Update</h3>
                            <p>Updated guidelines for COVID-19 protocols in healthcare facilities. Please review the
                                attached
                                documents for detailed information about the new procedures and safety measures.</p>
                        </div>
                        <div class="artical-buttom">
                            <a href="#">Read More</a>
                        </div>
                    </article>
                </div>
                <div class="right">
                    <h3>Categories</h3>
                    <div>
                        <button>
                            All Updates <span style="background-color: #DBEAFE; color: #1E40AF;">
                                <p>24</p>
                            </span>
                        </button>
                        <button>
                            Emergency Alerts <span style="background-color: #FEE2E2; color: #991B1B;">
                                <p>5</p>
                            </span>
                        </button>
                        <button>
                            Healthcare Updates <span style="background-color: #DCFCE7; color: #166534;">
                                <p>12</p>
                            </span>
                        </button>
                        <button>
                            Maintenance <span style="background-color: #FEF9C3; color: #854D0E;">
                                <p>7</p>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <?php include 'footer.php'; ?>
</body>

</html>