<?php
  if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE HTML>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Home - SneakerSpot</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

  <style>
    body {
      font-family: 'Arial', sans-serif;
      background-color: white;
      margin: 0;
      padding: 0;
    }

    .header {
      padding: 80px 20px;
      background-color: white;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .header .text-container {
      max-width: 50%;
    }

    .header h1 {
      font-size: 56px;
      font-weight: bold;
      margin-bottom: 20px;
      color: #333;
    }

    .header p {
      font-size: 22px;
      color: #555;
    }

    .header img {
      max-width: 45%;
      border-radius: 15px;
      box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
    }

    .features {
      padding: 50px 0;
      background-color: #f9f9f9;
    }

    .card-custom {
      border: none;
      border-radius: 20px;
      background: #407e60;
      color: white;
      overflow: hidden;
      position: relative;
      text-align: center;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
      transition: transform 0.3s ease;
      width: 300px;
      height: 400px;
      margin: 10px;
    }

    .card-custom img {
      width: 80%;
      height: auto;
      max-height: 150px;
      transform: rotate(-20deg);
      transition: transform 0.5s ease;
      margin: 0 auto;
      display: block;
    }

    .card-custom:hover img {
      transform: rotate(0);
    }

    .card-title {
      font-size: 1.8rem;
      margin-bottom: 20px;
      color: #fff;
    }

    .btn-more {
      background-color: #c44950;
      color: #fff;
      border-radius: 50px;
      padding: 15px 30px;
      font-size: 1rem;
      font-weight: bold;
      transition: background-color 0.3s ease;
    }

    .btn-more:hover {
      background-color: #a83a40;
      color: #fff;
    }

    .card-footer {
      background-color: white;
      color: #000;
      font-weight: bold;
      padding: 20px;
    }

    .row.justify-content-center {
      display: flex;
      justify-content: center;
      gap: 20px;
      flex-wrap: nowrap;
    }

    .testimonial {
      text-align: center;
      padding: 20px;
    }

    .testimonial img {
      border-radius: 50%;
      width: 100px;
      height: 100px;
      margin-bottom: 20px;
    }

    .testimonial h5 {
      margin-top: 20px;
      font-weight: bold;
    }

    .our-story {
      display: flex;
      flex-direction: row;
      justify-content: center;
      align-items: center;
      padding: 50px;
    }

    .our-story img {
      max-width: 32%;
      height: auto;
      border-radius: 8px;
    }

    .our-story .text-section {
      max-width: 600px;
      margin-left: 30px;
    }

    .our-story .text-section h1 {
      font-size: 36px;
      margin-bottom: 20px;
    }

    .our-story .text-section p {
      font-size: 18px;
      line-height: 1.6;
      margin-bottom: 20px;
    }

    .our-story .text-section {
      display: inline-block;
      padding: 10px 20px;
      font-size: 18px;
      text-decoration: none;
      border-radius: 5px;
    }

    #map {
      height: 500px;
      width: 100%;
    }
  </style>
</head>

<body onload="initMap()">
  <?php 
    include 'navbar.php'; 
    include 'accessibility_menu.php'; 
  ?>

  <header class="header w-75 h-75" style="border: 2px solid #407e60; border-radius:10px;">
    <div class="text-container">
      <h1>Discover Your Perfect Pair at SneakerSpot</h1>
      <p>Handpicked sports shoes that combine comfort, style, and performance.</p>
    </div>
    <img src="assets/images/collection.png" class="img-fluid w-50" alt="Sneaker Collection">
  </header>

  <div class="container features">
    <h3>Browse by brand:</h3>   
    <div class="row justify-content-center">
      <div class="col-md-4 mb-4">
        <div class="card card-custom">
          <div class="card-body">
            <h5 class="card-title">Nike</h5>
            <img src="assets/images/nike.png" alt="Nike">
            <a href="products.php?brand=Nike" class="btn btn-more mt-3">More</a>
          </div>
          <div class="card-footer">
            Nike
          </div>
        </div>
      </div>

      <div class="col-md-4 mb-4">
        <div class="card card-custom">
          <div class="card-body">
            <h5 class="card-title">Adidas</h5>
            <img src="assets/images/adidas.png" alt="Adidas">
            <a href="products.php?brand=Adidas" class="btn btn-more mt-3">More</a>
          </div>
          <div class="card-footer">
            Adidas
          </div>
        </div>
      </div>

      <div class="col-md-4 mb-4">
        <div class="card card-custom">
          <div class="card-body">
            <h5 class="card-title">Converse</h5>
            <img src="assets/images/converse.png" alt="Converse">
            <a href="products.php?brand=Converse" class="btn btn-more mt-3">More</a>
          </div>
          <div class="card-footer">
            Converse
          </div>
        </div>
      </div>
    </div>
  </div>

  <section id="our-story" class="our-story">
    <img src="assets/images/ourStory.png" alt="Our Story">
    <div class="text-section">
      <h1>Our Story</h1>
      <p>At SneakerSpot, we are passionate about sports shoes. Founded with the belief that the right pair of shoes can transform your game, we are dedicated to providing a curated selection of the latest and greatest from top brands like Nike, Adidas, New Balance, Reebok, Puma, and Converse.</p>
      <p>Our commitment to customer satisfaction drives us to offer top-notch service, competitive pricing, and a seamless shopping experience. Join us as we continue to innovate and transform the way people find their perfect pair of sneakers.</p>
      <a href="ourStory.php" class="btn btn-outline-secondary">Learn More</a>
    </div>
  </section>

  <section>
    <div id="testimonialCarousel" class="carousel slide" data-ride="carousel">
      <div class="carousel-inner">
        <div class="carousel-item active">
          <div class="testimonial">
            <img src="assets/images/icon1.png" alt="Client Image">
            <h5>Dana, Tel Aviv</h5>
            <p>"The selection of Nike shoes on SneakerSpot is amazing! I found the perfect pair for my morning runs. The service was smooth, and the delivery was quick. Highly recommended!"</p>
          </div>
        </div>
        <div class="carousel-item">
          <div class="testimonial">
            <img src="assets/images/icon2.png" alt="Client Image">
            <h5>Rachel, Jerusalem</h5>
            <p>"I was looking for a comfortable pair of Adidas sneakers, and SneakerSpot had exactly what I needed. The prices are great, and the customer service is outstanding."</p>
          </div>
        </div>
        <div class="carousel-item">
          <div class="testimonial">
            <img src="assets/images/icon3.png" alt="Client Image">
            <h5>Yossi, Haifa</h5>
            <p>"SneakerSpot made it easy for me to find the best Reebok shoes for my gym workouts. The variety of styles and sizes is impressive. I'll definitely shop here again!"</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section id="map">
    <h2>Find Us</h2>
    <div id="map"></div>
  </section>

  <?php include 'footer.php'; ?>

  <!-- Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDOWVMtaOUAJN6VGLwaL2ukt5LZdkCNem4&callback=initMap" async defer></script>
  <script>
    function initMap() {
      var location = { lat: 32.0853, lng: 34.7818 }; //Tel Aviv
      var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 13,
        center: location
      });
      var marker = new google.maps.Marker({
        position: location,
        map: map
      });
    }
  </script>
</body>

</html>
