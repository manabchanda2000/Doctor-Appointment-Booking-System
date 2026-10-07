<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Healthcare Platform</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
    * {
        padding: 0;
        margin: 0;
        box-sizing: border-box;
    }

    /* hero section----------------- */
    .hero-sec {
        padding: 30px;
        background: linear-gradient(180deg, #dae5f5 0%, #FFFFFF 100%);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .hero {
        padding: 40px 20px;
        height: 300px;
        text-align: center;
    }

    .hero-headline {
        font-size: 3.5em;
    }

    .hero-subline {
        font-size: 1.2em;
        color: #6B7280;
        max-width: 600px;
    }

    /* second section----------------------- */

    .second-sec {
        padding: 10px;
    }

    .contact {
        display: flex;
        align-items: center;
        justify-content: space-around;
        flex-wrap: wrap;
        position: relative;
        top: -60px;
    }

    .contact-box {
        flex: 0 0 400px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 20px;
        background: linear-gradient(135deg, #EFF6FF -2%, #F5F3FF 100%);
        box-shadow: 2px 3px 8px rgba(0, 0, 0, 0.2);
        border-radius: 5px;
        gap: 20px;
    }

    .contact-box span {
        height: 40px;
        width: 40px;
        border-radius: 50%;
        background-color: #DBEAFE;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .contact-box i {
        font-size: 24px;
        color: #3B82F6;
    }

    .contact-box h3 {
        font-size: 20px;
    }

    .contact-box p {
        font-size: 14px;
        color: #6B7280;
    }

    /* third section--------------------- */

    .third-sec {
        padding: 30px 20px;
    }

    .message {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
    }

    .message-form {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        width: 50%;
        background-color: #fdfdfd;
        border-radius: 8px;
        padding: 15px;
        box-shadow: 2px 3px 8px rgba(0, 0, 0, 0.1);
    }

    .message-form h2 {
        font-size: 1.5em;
        margin-bottom: 10px;
    }

    .message-form p {
        font-size: 1.1em;
        color: #6B7280;
        margin-bottom: 15px;
    }

    .message-form form {
        padding: 10px;
        width: 100%;
    }

    .form-data {
        width: 100%;
        margin-bottom: 15px;
    }

    .form-data label {
        display: block;
        font-size: 15px;
        color: #374151;
        font-weight: 600;
        position: relative;
        top: 8px;
        left: 15px;
        background-color: white;
        width: fit-content;
    }

    .form-data input,
    .form-data textarea {
        width: 100%;
        padding: 12px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 14px;
        color: #111827;
        outline: none;
    }

    .form-data input:focus,
    .form-data textarea:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 5px rgba(59, 130, 246, 0.2);
    }

    .form-data button {
        width: 100%;
        padding: 12px;
        background-color: #3b82f6;
        color: #ffffff;
        font-size: 1em;
        font-weight: 600;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        text-align: center;
        transition: all 0.3s ease;
    }

    button:hover {
        background-color: #2563eb;
    }

    button:active {
        background-color: #1d4ed8;
    }

    .map {
        width: 50%;
        height: 530px;
        box-shadow: 2px 3px 8px rgba(0, 0, 0, 0.1);
    }

    .map img {
        width: 100%;
        height: 100%;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    /* forth section------------------------ */

    .forth-sec {
        padding: 30px 20px;
    }

    .faq-sec {
        padding: 15px 20px;
        background-color: #fdfdfd;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }

    .heading {
        font-size: 1.5em;
        margin-bottom: 10px;
    }

    .faq-box {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
        width: 100%;

    }

    .faq {
        border-bottom: 1px solid #d1d5db;
        width: 100%;
        padding: 10px 0;
    }

    .faq h3 {
        font-size: 18px;
        margin-bottom: 10px;
    }

    .faq p {
        font-size: 14px;
        color: #374151;
    }
    </style>
</head>

<body>
    <?php include 'header.php'; ?>
    <main>
        <section class="hero-sec">
            <div class="hero">
                <h1 class="hero-headline">Get in Touch with Us</h1>
                <p class="hero-subline">Have questions? Need help? We're here 24/7 to assist you with any healthcare
                    needs or concerns you may have.</p>
            </div>
        </section>

        <section class="second-sec">
            <div class="contact">
                <div class="contact-box">
                    <span><i class="fas fa-phone"></i></span>
                    <h3>Call Us</h3>
                    <p>General: +1 (555) 123-4567</p>
                </div>
                <div class="contact-box">
                    <span><i class="fas fa-envelope"></i></span>
                    <h3>Email Us</h3>
                    <p>info@healthcare.com</p>
                </div>
                <div class="contact-box">
                    <span><i class="fas fa-map-marker-alt"></i></span>
                    <h3>Visit Us</h3>
                    <p>123 Healthcare Ave New York, NY 10001</p>
                </div>
            </div>
        </section>

        <section class="third-sec">
            <div class="message">
                <div class="message-form">
                    <h2>Send us a message</h2>
                    <p>Fill out the form below and we'll get back to you as soon as possible.</p>
                    <form action="enquery-submit.php" method="POST">
                        <div class="form-data">
                            <label for="name">Full Name</label>
                            <input type="text" name="name" id="name">
                        </div>
                        <div class="form-data">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email">
                        </div>
                        <div class="form-data">
                            <label for="phone">Phone Number</label>
                            <input type="tel" name="phone" id="phone">
                        </div>
                        <div class="form-data">
                            <label for="message">Message</label>
                            <textarea name="message" id="message" rows="4"></textarea>
                        </div>
                        <div class="form-data">
                            <button type="submit">Send Message</button>
                        </div>
                    </form>
                </div>
                <div class="map">
                   <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d8707.176529407978!2d88.48912176157258!3d23.383939515409267!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sin!4v1749056120475!5m2!1sen!2sin" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </section>

        <section class="forth-sec">
            <div class="faq-sec">
                <h2 class="heading">Frequently Asked Questions</h2>
                <div class="faq-box">
                    <div class="faq">
                        <h3>How do I book an appointment?</h3>
                        <p>You can book an appointment through our online portal, mobile app, or by calling our
                            reception at +1 (555) 123-4567. We offer flexible scheduling options to accommodate your
                            needs.</p>
                    </div>
                    <div class="faq">
                        <h3>What insurance plans do you accept?</h3>
                        <p>We accept most major insurance plans. Please contact our office with your specific insurance
                            information, and we'll be happy to verify your coverage.</p>
                    </div>
                    <div class="faq">
                        <h3>What should I bring to my first appointment?</h3>
                        <p>Please bring your ID, insurance card, list of current medications, and any relevant medical
                            records or test results. Arriving 15 minutes early helps us process your information
                            efficiently.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <?php include 'footer.php'; ?>
</body>

</html>