<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>About Us - SneakerSpot</title>
  <meta name="description" content="Learn more about SneakerSpot, our mission, values, and the team behind your favorite sports shoes.">
  
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

  <style>
    body {
      font-family: 'Arial', sans-serif;
      margin: 0;
      padding: 0;
    }

    .header {
      padding: 60px 20px;
      text-align: center;
    }

    .header h1 {
      font-size: 48px;
      font-weight: bold;
      margin-bottom: 20px;
      color: #407e60;
    }

    .header p {
      font-size: 20px;
      margin-bottom: 0;
      color: #c44950;
    }

    .about-section {
      padding: 60px 20px;
      background-color: #f9f9f9;
    }

    .about-section h2 {
      font-size: 36px;
      color: #333;
      margin-bottom: 40px;
      text-align: center;
    }

    .about-content {
      max-width: 900px;
      margin: 0 auto;
      line-height: 1.8;
      color: #555;
    }

    .team-section {
      padding: 60px 20px;
      background-color: white;
    }

    .team-section h2 {
      font-size: 36px;
      color: #333;
      margin-bottom: 40px;
      text-align: center;
    }

    .team-member {
      text-align: center;
      margin-bottom: 40px;
    }

    .team-member img {
      border-radius: 50%;
      width: 150px;
      height: 150px;
      object-fit: cover;
      margin-bottom: 20px;
    }

    .team-member h5 {
      font-size: 20px;
      color: #333;
      margin-bottom: 10px;
    }

    .team-member p {
      font-size: 16px;
      color: #777;
    }

    .cart-sidebar:not(.open) {
      display: none;
    }
    
  </style>
</head>

<body>
<?php include 'navbar.php'; include 'accessibility_menu.php'; ?>
  <header class="header">
    <h1>About SneakerSpot</h1>
    <p>Our mission is to bring you the best sports shoes from around the world.</p>
  </header>

  <section class="about-section">
    <div class="about-content">
      <h2>Who We Are</h2>
      <p>
        At SneakerSpot, we are passionate about sports shoes. Founded with the belief that the right pair of shoes can
        transform your game, we are dedicated to providing a curated selection of the latest and greatest from top brands
        like Nike, Adidas, New Balance, Reebok, Puma, and Converse.
      </p>
      <p>
        Our team consists of avid sports enthusiasts and fashion-forward thinkers who are committed to bringing you
        quality, comfort, and style in every pair. Whether you're a professional athlete or just looking for something
        comfortable and stylish, SneakerSpot has something for everyone.
      </p>
    </div>
  </section>

  <section class="team-section">
    <div class="container">
      <h2>Meet Our Team</h2>
      <div class="row justify-content-center">
        <div class="col-md-4">
          <div class="team-member">
            <img src="assets/images/icon3.png" alt="Team Member 1">
            <h5>Daniel Levi</h5>
            <p>Founder & CEO</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="team-member">
            <img src="assets/images/icon1.png" alt="Team Member 2">
            <h5>Noa Melamed</h5>
            <p>Chief Designer</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="team-member">
            <img src="assets/images/icon2.png" alt="Team Member 3">
            <h5>Michaela Rubinshtein</h5>
            <p>Marketing Director</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <?php include 'footer.php'; ?>

  <!-- Bootstrap JS -->
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>
