<html>
    <head>
        <title>emergency-support</title>
    <style>
    .emergency-hero {
  background-color: #ffe5e5;
  text-align: center;
  padding: 30px 20px;
}
.emergency-numbers, .nearby-hospitals, .emergency-form, .emergency-tips {
  background: #fff;
  padding: 20px;
  margin: 20px;
  border-left: 5px solid #ff4d4d;
  border-radius: 5px;
}
.emergency-numbers ul, .nearby-hospitals ul, .emergency-tips ul {
  list-style: disc;
  padding-left: 20px;
}
.emergency-form form {
  display: flex;
  flex-direction: column;
}
.emergency-form input, .emergency-form textarea {
  margin-bottom: 10px;
  padding: 10px;
}
.emergency-form button {
  background: #ff4d4d;
  color: #fff;
  padding: 10px;
  border: none;
}
.live-chat-help {
  text-align: center;
  margin: 20px;
}
</style>
</head>
<body>
    

<?php include 'header.php'; ?>
<main>
  <section class="first-sec emergency-hero">
    <h2>Emergency Support</h2>
    <p>If you or someone else is in an emergency, get help immediately!</p>
  </section>

  <!-- Emergency Numbers -->
  <section class="emergency-numbers">
    <h3>Important Emergency Numbers</h3>
    <ul>
      <li><strong>Ambulance:</strong> 102 / 108</li>
      <li><strong>Police:</strong> 100</li>
      <li><strong>Fire Brigade:</strong> 101</li>
      <li><strong>Women's Helpline:</strong> 1091</li>
    </ul>
  </section>

  <!-- Nearby Hospitals -->
  <section class="nearby-hospitals">
    <h3>Nearby Hospitals</h3>
    <ul>
      <li><strong>Fortis Hospital</strong> – Anandapur, Kolkata – <a href="tel:03366284500">033 6628 4500</a></li>
      <li><strong>AMRI Hospitals</strong> – Salt Lake – <a href="tel:03366800000">033 6680 0000</a></li>
      <li><strong>Peerless Hospital</strong> – Panchasayar – <a href="tel:03324622323">033 2462 2323</a></li>
    </ul>
  </section>

  <!-- Emergency Contact Form -->
  <section class="emergency-form">
    <h3>Request Emergency Help</h3>
    <form action="#" method="POST">
      <input type="text" name="name" placeholder="Your Name" required>
      <input type="tel" name="phone" placeholder="Phone Number" required>
      <textarea name="message" placeholder="Describe your emergency..." required></textarea>
      <button type="submit" name="submit">Send Request</button>
    </form>
  </section>

  <!-- Emergency Health Tips -->
  <section class="emergency-tips">
    <h3>Quick Emergency Tips</h3>
    <ul>
      <li>Don't panic – stay calm and alert.</li>
      <li>Call for help immediately – provide clear details.</li>
      <li>Apply basic first aid if needed.</li>
      <li>Don't crowd the emergency victim – allow space.</li>
      <li>Keep emergency contacts saved on your phone.</li>
    </ul>
  </section>

</main>

<?php include 'footer.php'; ?>
</body>
</html>