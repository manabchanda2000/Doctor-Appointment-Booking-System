<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Find Clinics Near You</title>
        <link
            href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap"
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

            /* second section-------------- */
            .second-sec {
                padding: 20px 40px;
                position: relative;
                top: -40px;
            }

            .filter {
                display: flex;
                align-items: center;
                justify-content: space-around;
                gap: 10px;
                flex-wrap: wrap;
                background-color: #fdfdfd;
                border-radius: 10px;
                padding: 20px;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            }

            .filter select {
                flex: 0 0 250px;
                padding: 10px;
                border: 1px solid #BFDBFE;
                border-radius: 5px;
            }

            /* third section----------------------- */
            .third-sec {
                padding: 20px;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 20px;
                flex-wrap: wrap;
            }

            .clinic-box {
                flex: 0 0 320px;
                background-color: #fdfdfd;
                border-radius: 10px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            }

            .clinic-box img {
                width: 100%;
                aspect-ratio: 2;
                object-fit: fill;
            }
            .clinic-details{
                padding: 10px;
                display: flex;
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            .clinic-details h3{
                font-size: 16px;
            }
            .clinic-details p{
                font-size: 14px;
                color: #6B7280;
            }
            .clinic-details i{
                margin-right: 5px;
            }
            .ratings{
                color: #ffb400;
                font-size: 12px;
            }
            .clinic-details a{
                text-decoration: none;
                padding: 10px 15px;
                width: 100%;
                background-color: #2563EB;
                color: white;
                text-align: center;
                border-radius: 5px;
            }
        </style>
    </head>

    <body>
        <?php include 'header.php'; ?>
        <main>
            <section class="hero-sec">
                <div class="hero">
                    <h1 class="hero-headline">Find the Best Clinics Near You</h1>
                    <p class="hero-subline">Search and book appointments with top-rated clinics easily</p>
                    <div class="search-box">
                        <i class="fa-brands fa-searchengin"></i>
                        <input type="search" placeholder="Search by location or clinic name......">
                        <button type="submit">Search</button>
                    </div>
                </div>
            </section>

            <section class="second-sec">
                <div class="filter">
                    <select>
                        <option>All Locations</option>
                        <option>New York</option>
                        <option>Los Angeles</option>
                        <option>Chicago</option>
                    </select>
                    <select>
                        <option>All Ratings</option>
                        <option>4+ Stars</option>
                        <option>3+ Stars</option>
                        <option>2+ Stars</option>
                    </select>
                    <select>
                        <option>Availability</option>
                        <option>Available Now</option>
                        <option>Open Today</option>
                    </select>
                    <select>
                        <option>All Types</option>
                        <option>General Practice</option>
                        <option>Dental Clinic</option>
                        <option>Eye Care</option>
                    </select>
                </div>
            </section>

            <section class="third-sec">
                <div class="clinic-box">
                    <img src="./img/clinic-1.png" alt="Clinic Image" />
                    <div class="clinic-details">
                        <h3>Central Medical Clinic</h3>
                        <p>
                            <i class="fas fa-map-marker-alt"></i>
                            123 Healthcare Ave, New York
                        </p>
                        <p>
                            <i class="far fa-clock"></i>
                            Open: 9:00 AM - 6:00 PM
                        </p>
                        <div class="ratings">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                            <span style="color: #6B7280;">(128 reviews)</span>
                        </div>
                        
                        <a href="">View More</a>
                    </div>
                </div>
            </section>
        </main>
        <?php include 'footer.php'; ?>
    </body>

    </html>