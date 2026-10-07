# 🏥 Apna Health – Doctor Appointment Booking System

A full-stack web app where patients find doctors and book appointments, doctors manage their clinics and patients, and an admin oversees the whole platform. Built as a BCA project with **PHP, MySQL, HTML, CSS and JavaScript**.

## ✨ Features

**👤 Patient**
- Sign up / log in, edit profile
- Search doctors and view their clinics, fees and timings
- Book, reschedule or cancel appointments (physical or video call)
- View payments, medical records and prescriptions
- Review doctors, ask questions, read health tips and announcements
- Emergency booking with location

**🩺 Doctor**
- Sign up (approved by admin), manage profile
- Add and edit clinics with days, timings and fees
- Approve, reject or complete appointments
- View patient records, upload prescriptions, track earnings
- Post health tips and announcements, answer patient questions

**🛡️ Admin**
- Dashboard with platform overview
- Manage doctors, patients, clinics, appointments and payments
- Moderate reviews, Q&A, health tips and announcements
- Handle contact queries, team members and login logs

**📧 Email:** welcome email on signup and OTP verification for password change, sent with PHPMailer.

## 🛠️ Tech Stack

PHP · MySQL (mysqli) · HTML/CSS/JS · PHPMailer · Font Awesome · XAMPP

## 🚀 Getting Started

1. Install [XAMPP](https://www.apachefriends.org/) and start **Apache** and **MySQL**.
2. Clone the repo into `htdocs` (name the folder `major-p`, since the email scripts reference that path):
   ```bash
   git clone https://github.com/manabchanda2000/Doctor_Appointmenr_Booking_System.git major-p
   ```
3. Open phpMyAdmin, create a database named `project-data`, and import `project-data.sql`.
4. Check the database settings in `connection.php` (defaults: `root`, no password).
5. For emails, add your own Gmail address and [App Password](https://support.google.com/accounts/answer/185833) in `generate-otp.php` and `patient/patient-signup-submit.php`.
6. Visit `http://localhost/major-p/`.

## 📁 Structure

```
├── admin/      # Admin panel
├── doctor/     # Doctor panel
├── patient/    # Patient panel
├── img/ upload/ # Static images and user uploads
├── PHPMailer-master/
├── connection.php
└── project-data.sql   # Database schema + sample data
```

## 🔮 Future Improvements

- Online payment gateway integration
- Real video-call support
- Move secrets to environment variables

## 👨‍💻 Author

**Manab Chanda** – [GitHub](https://github.com/manabchanda2000)
