<?php
  require 'config/config.php';
  $data = [];
  
  if(isset($_POST['search'])) {
    // Get data from FORM
    $keywords = $_POST['keywords'];
    $location = $_POST['location'];

    //keywords based search
    $keyword = explode(',', $keywords);
    $concats = "(";
    $numItems = count($keyword);
    $i = 0;
    foreach ($keyword as $key => $value) {
      # code...
      if(++$i === $numItems){
         $concats .= "'".$value."'";
      }else{
        $concats .= "'".$value."',";
      }
    }
    $concats .= ")";
  //end of keywords based search
  
  //location based search
    $locations = explode(',', $location);
    $loc = "(";
    $numItems = count($locations);
    $i = 0;
    foreach ($locations as $key => $value) {
      # code...
      if(++$i === $numItems){
         $loc .= "'".$value."'";
      }else{
        $loc .= "'".$value."',";
      }
    }
    $loc .= ")";

  //end of location based search
    
    try {
      //foreach ($keyword as $key => $value) {
        # code...

        $stmt = $connect->prepare("SELECT * FROM room_rental_registrations_apartment WHERE country IN $concats OR country IN $loc OR state IN $concats OR state IN $loc OR city IN $concats OR city IN $loc OR address IN $concats OR address IN $loc OR rooms IN $concats OR landmark IN $concats OR landmark IN $loc OR rent IN $concats OR deposit IN $concats");
        $stmt->execute();
        $data2 = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $connect->prepare("SELECT * FROM room_rental_registrations WHERE country IN $concats OR country IN $loc OR state IN $concats OR state IN $loc OR city IN $concats OR city IN $loc OR rooms IN $concats OR address IN $concats OR address IN $loc OR landmark IN $concats OR rent IN $concats OR deposit IN $concats");
        $stmt->execute();
        $data8 = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $data = array_merge($data2, $data8);

    }catch(PDOException $e) {
      $errMsg = $e->getMessage();
    }
  }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SHRS</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts for this template -->
    <link href="assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Kaushan+Script' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700' rel='stylesheet' type='text/css'>

    <!-- Custom styles for this template -->
    <link href="assets/css/rent.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
  </head>

  <body id="page-top">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav">
      <div class="container">
        <a class="navbar-brand js-scroll-trigger" href="#page-top">Accommodation</a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          Menu
          <i class="fa fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav text-uppercase ml-auto">
           
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="#search">Search</a>
            </li>
            
            <?php 
              if(empty($_SESSION['username'])){
                echo '<li class="nav-item">';
                  echo '<a class="nav-link" href="./auth/login.php">Login</a>';
                echo '</li>';
              }else{
                echo '<li class="nav-item">';
                 echo '<a class="nav-link" href="./auth/dashboard.php">Home</a>';
               echo '</li>';
              }
            ?>
            

            <li class="nav-item">
              <a class="nav-link" href="./auth/register.php">Register</a>
            </li>

          </ul>
        </div>
      </div>
    </nav>

    <!-- Header -->
    <header class="masthead">
      <div class="container">
        <div class="intro-text">
          <div class="intro-heading text-uppercase">Find Home Together!<br></div>
 <!-- Search -->
 <section id="search">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <form action="" method="POST" class="center" novalidate>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <input class="form-control" id="keywords" name="keywords" type="text" placeholder="Keywords (Ex: 1 BHK, Rent Amount, Landmark)" required data-validation-required-message="Please enter keywords">
                    <p class="help-block text-danger"></p>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <input class="form-control" id="location" type="text" name="location" placeholder="Location" required data-validation-required-message="Please enter location.">
                    <p class="help-block text-danger"></p>
                  </div>
                </div>         

                <div class="col-md-2">
                  <div class="form-group">
                    <button id="" class="btn btn-success btn-md text-uppercase" name="search" value="search" type="submit">Search</button>
                  </div>
                </div>
              </div>
            </form>

        </div>
      </div>
    </header>

    
            <?php
              if(isset($errMsg)){
                echo '<div style="color:#FF0000;text-align:center;font-size:17px;">'.$errMsg.'</div>';
              }
              if(count($data) !== 0){
                echo "<h2 class='text-center'>Available Results:</h2>";
              }else{
                //echo "<h2 class='text-center' style='color:red;'>Try Some other keywords</h2>";
              }
            ?>        
            <?php 
                foreach ($data as $key => $value) {           
                  echo '<div class="card card-inverse card-info mb-3" style="padding:1%;">          
                        <div class="card-block">';
                          // echo '<a class="btn btn-warning float-right" href="update.php?id='.$value['id'].'&act=';if(isset($value['ap_number_of_plats'])){ echo "ap"; }else{ echo "indi"; } echo '">Edit</a>';
                         echo   '<div class="row">
                            <div class="col-4">
                            <h4 class="text-center">Owner Details</h4>';
                              echo '<p><b>Owner Name: </b>'.$value['fullname'].'</p>';
                              echo '<p><b>Contact Number: </b>'.$value['mobile'].'</p>';
                              echo '<p><b>Alternate Number: </b>'.$value['alternat_mobile'].'</p>';
                              echo '<p><b>Email: </b>'.$value['email'].'</p>';
                              echo '<p><b>Country: </b>'.$value['country'].'</p><p><b> State: </b>'.$value['state'].'</p><p><b> City: </b>'.$value['city'].'</p>';
                              if ($value['image'] !== 'uploads/') {
                                # code...
                                echo '<p><b>Image:</b></p> </br> <img src="app/'.$value['image'].'" width="230" class="img-thumbnail">';
                              }

                          echo '</div>
                            <div class="col-5">
                            <h4 class="text-center">Property Details</h4>';
                              // echo '<p><b>Country: </b>'.$value['country'].'<b> State: </b>'.$value['state'].'<b> City: </b>'.$value['city'].'</p>';
                              echo '<p><b>Plot Number: </b>'.$value['plot_number'].'</p>';

                              if(isset($value['rent'])){
                                echo '<p><b>Rent: </b>$'.$value['rent'].' <small><i>per month</i></small></p> ';
                              } 

                              if(isset($value['sale'])){
                                echo '<p><b>Sale: </b>$'.$value['sale'].'</p>';
                              } 
                              
                                if(isset($value['apartment_name']))                         
                                  echo '<div class="alert alert-success" role="alert"><p><b>Apartment Name: </b>'.$value['apartment_name'].'</p></div>';

                                if(isset($value['ap_number_of_plats']))
                                  echo '<div class="alert alert-success" role="alert"><p><b>Plat Number: </b>'.$value['ap_number_of_plats'].'</p></div>';

                              echo '<p><b>Available Rooms: </b>'.$value['rooms'].'</p>';
                              echo '<p><b>Address: </b>'.$value['address'].'</p><p><b> Landmark: </b>'.$value['landmark'].'</p>';
                          echo '</div>
                            <div class="col-3">
                            <h4>Other Details</h4>';
                            echo '<p><b>Accommodation: </b>'.$value['accommodation'].'</p>';
                            echo '<p><b>Description: </b>'.$value['description'].'</p>';
                              if($value['vacant'] == 0){ 
                                echo '<div class="alert alert-danger" role="alert"><h3><b>Occupied</b></h3></div>';
                              }else{
                                echo '<div class="alert alert-success" role="alert"><h3><b>Vacant!</b></h3></div>';
                              } 
                            echo '</div>
                          </div>              
                         </div>
                      </div>';
                }
              ?>              
          </div>
        </div>
      </div>
      <br><br><br><br><br><br>
    </section>    

    <!-- Check Booking Availability form -->
    <div class="container availabity-form">
        <div class="row">
            <div class="col-lg-12 bg-white shadow p-4 rounded">
                <h5 class="mt-2 mb-2 text-center fw-bold h-font">Check Booking Availability</h5>
                <form>
                    <div class="row align-items-end">
                        <div class="col-lg-3 mb-3">
                            <label class="form-label" style="font-weight: 500;">Booking-From</label>
                            <input type="text" class="form-control shadow-none">
                        </div>
                        <div class="col-lg-3  mb-3">
                            <label class="form-label" style="font-weight: 500;">Booking-To</label>
                            <input type="text" class="form-control shadow-none">
                        </div>
                        <div class="col-lg-3 mb-3">
                            <label class="form-label" style="font-weight: 500;">Vehicle Types</label>
                            <input type="text" class="form-control shadow-none">
                        </div>
                        <div class="col-lg-2 mb-3">
                            <label class="form-label" style="font-weight: 500;">Booking-Date</label>
                            <input type="date" class="form-control shadow-none">
                        </div>
                        <div class="col-lg-1 mb-lg-3 mb-2">
                            <button type="button" class="btn text-white shadow-none custom-bg " onclick="changeColor()">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function changeColor() {
            var button = document.querySelector('.custom-bg-hover ');
            button.classList.add('clicked');
        }
    </script>
    
    <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">Services</h2>
 <!-- services -->
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6 my-3">
                <div class="card border-0 shadow" style="max-width: 350px; margin: auto;">
                    <img src="image/prj3.jpg" class="card-img-top" alt="image" style="w-100">
                    <div class="card-body">
                        <h5 class="card-title">Anchor</h5>
                        <!-- <h6>if I want</h6> -->
                        <div class="feature mb-4">
                            <h6 class="mb-1">Feature</h6>
                            <span class="badge rounded-pill bg-light text-dark text-wrap">
                                Fast delivery
                            </span>
                            <span class="badge rounded-pill bg-light text-dark text-wrap">
                                Secure delivery
                            </span>
                            <span class="badge rounded-pill bg-light text-dark text-wrap">
                                Cost Efficiency delivery
                            </span>
                        </div>
    
                        <div class="facilities mb-4">
                            <h6 class="mb-1">Facilities</h6>
                            <span class="badge rounded-pill bg-light text-dark text-wrap">
                                Fast delivery
                            </span>
                            <span class="badge rounded-pill bg-light text-dark text-wrap">
                                Secure delivery
                            </span>
                            <span class="badge rounded-pill bg-light text-dark text-wrap">
                                Cost Efficiency delivery
                            </span>
                        </div>
    
                        <div class="rating mb-4">
                            <h6 class="mb-1">Rating</h6>
                            <span class="badge rounded-pill bg-light">
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                            </span>
                        </div>
    
                        <div class="d-flex justify-content-evenly mb-3">
                            <a href="#" class="btn btn-sm text-white custom-bg shadow">Book Now</a>
                            <a href="#" class="btn btn-sm btn-outline-dark shadow-none">Upload-LR</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 my-3">
                <div class="card border-0 shadow" style="max-width: 350px; margin: auto;">
                    <img src="image/prj4.jpg" class="card-img-top" alt="image" style="w-100">
                    <div class="card-body">
                        <h5 class="card-title">Haier</h5>
                        <!-- <h6>if I want</h6> -->
                        <div class="feature mb-4">
                            <h6 class="mb-1">Feature</h6>
                            <span class="badge rounded-pill bg-light text-dark text-wrap">
                                Fast delivery
                            </span>
                            <span class="badge rounded-pill bg-light text-dark text-wrap">
                                Secure delivery
                            </span>
                            <span class="badge rounded-pill bg-light text-dark text-wrap">
                                Cost Efficiency delivery
                            </span>
                        </div>
    
                        <div class="facilities mb-4">
                            <h6 class="mb-1">Facilities</h6>
                            <span class="badge rounded-pill bg-light text-dark text-wrap">
                                Fast delivery
                            </span>
                            <span class="badge rounded-pill bg-light text-dark text-wrap">
                                Secure delivery
                            </span>
                            <span class="badge rounded-pill bg-light text-dark text-wrap">
                                Cost Efficiency delivery
                            </span>
                        </div>
    
                        <div class="rating mb-4">
                            <h6 class="mb-1">Rating</h6>
                            <span class="badge rounded-pill bg-light">
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                            </span>
                        </div>
    
                        <div class="d-flex justify-content-evenly mb-3">
                            <a href="#" class="btn btn-sm text-white custom-bg shadow">Book Now</a>
                            <a href="#" class="btn btn-sm btn-outline-dark shadow-none">Upload-LR</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 my-3">
                <div class="card border-0 shadow" style="max-width: 350px; margin: auto;">
                    <img src="image/prj5.jpg" class="card-img-top" alt="image" style="w-100">
                    <div class="card-body">
                        <h5 class="card-title">Hitachi</h5>
                        <!-- <h6>if I want</h6> -->
                        <div class="feature mb-4">
                            <h6 class="mb-1">Feature</h6>
                            <span class="badge rounded-pill bg-light text-dark text-wrap">
                                Fast delivery
                            </span>
                            <span class="badge rounded-pill bg-light text-dark text-wrap">
                                Secure delivery
                            </span>
                            <span class="badge rounded-pill bg-light text-dark text-wrap">
                                Cost Efficiency delivery
                            </span>
                        </div>
    
                        <div class="facilities mb-4">
                            <h6 class="mb-1">Facilities</h6>
                            <span class="badge rounded-pill bg-light text-dark text-wrap">
                                Fast delivery
                            </span>
                            <span class="badge rounded-pill bg-light text-dark text-wrap">
                                Secure delivery
                            </span>
                            <span class="badge rounded-pill bg-light text-dark text-wrap">
                                Cost Efficiency delivery
                            </span>
                        </div>
    
                        <div class="rating mb-4">
                            <h6 class="mb-1">Rating</h6>
                            <span class="badge rounded-pill bg-light">
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                            </span>
                        </div>
    
                        <div class="d-flex justify-content-evenly mb-3">
                            <a href="#" class="btn btn-sm text-white custom-bg shadow">Book Now</a>
                            <a href="#" class="btn btn-sm btn-outline-dark shadow-none">Upload-LR</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 my-3">
                <div class="card border-0 shadow" style="max-width: 350px; margin: auto;">
                    <img src="image/prj2.jpg" class="card-img-top" alt="image" style="w-100">
                    <div class="card-body">
                        <h5 class="card-title">For-All</h5>
                        <!-- <h6>if I want</h6> -->
                        <div class="feature mb-4">
                            <h6 class="mb-1">Feature</h6>
                            <span class="badge rounded-pill bg-light text-dark text-wrap">
                                Fast delivery
                            </span>
                            <span class="badge rounded-pill bg-light text-dark text-wrap">
                                Secure delivery
                            </span>
                            <span class="badge rounded-pill bg-light text-dark text-wrap">
                                Cost Efficiency delivery
                            </span>
                        </div>
    
                        <div class="facilities mb-4">
                            <h6 class="mb-1">Facilities</h6>
                            <span class="badge rounded-pill bg-light text-dark text-wrap">
                                Fast delivery
                            </span>
                            <span class="badge rounded-pill bg-light text-dark text-wrap">
                                Secure delivery
                            </span>
                            <span class="badge rounded-pill bg-light text-dark text-wrap">
                                Cost Efficiency delivery
                            </span>
                        </div>
    
                        <div class="rating mb-4">
                            <h6 class="mb-1">Rating</h6>
                            <span class="badge rounded-pill bg-light">
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                            </span>
                        </div>
    
                        <div class="d-flex justify-content-evenly mb-3">
                            <a href="#" class="btn btn-sm text-white custom-bg shadow">Book Now</a>
                            <a href="#" class="btn btn-sm btn-outline-dark shadow-none">Upload-LR</a>
                        </div>
                    </div>
                </div>
            </div>
    
            <!-- Repeat the above card structure for other columns as needed -->
    
            <div class="col-lg-12 text-center mt-5">
                <a href="#" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none">Know More &gt;&gt;&gt;</a>
            </div>
        </div>
    </div>

    <h2 class=" portfolio mt-5 pt-4 mb-4 text-center fw-bold h-font" id="portfolio">Portfolio</h2>
 <!-- portfolio -->
 <section class="services" id="services">
    <div class="row">
        <div class="transportation col-lg-3 col-md-6 my-3 " id="transportio">
            <div class="box">
                <h3>Transportation</h3>
                <p>Transportation is a critical pillar of logistics, facilitating the seamless movement of goods from one point to another. Efficient transportation systems ensure timely deliveries, optimizing supply chain processes and contributing to overall business success.</p>
            </div>
        </div>

        <div class="Supply col-lg-3 col-md-6 my-3" id="supply">
            <div class="box">
                <h3>Supply Chain Management</h3>
                <p>"Imagine Supply Chain Management as the conductor of a great orchestra. Each part plays its role to make everything run smoothly and make customers happy. It's like turning problems into chances to do even better."</p>
            </div>
        </div>

        <div class="Warehousing col-lg-3 col-md-6 my-3" id="Warehousing">
            <div class="box">
                <h3>Warehousing and Distribution</h3>
                <p>"Warehousing and Distribution are like superheroes of logistics. They store and move things around, making sure everything gets to the right place at the right time. It's like magic, where things appear just when you need them!"</p>
            </div>
        </div>

        <div class="Commercial col-lg-3 col-md-6 my-3" id="Commercial">
            <div class="box">
                <h3>Provide of Commercial Office</h3>
                <p>"Revamping a Commercial Office is like giving it a makeover – new looks, better vibes. It's all about creating a space where work feels great, and ideas flow like a river. Imagine your office as a canvas, and renovation as the brushstroke that brings it to life!"</p>
            </div>
        </div>
    </div>
 </section>
 <!-- Testimonials   -->
    <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">Testimonials</h2>

    <div class="container mt-5">
        <div class="swiper swiper-testimonials">
            <div class="swiper-wrapper mb-5">

                <div class="swiper-slide bg-white p-4">
                    <div class="profile d-flex align-item-center mb-3">
                        <img src="image/prj3.jpg" width="100px" alt="">
                        <div class="ms-2">
                            <h6 class="m-0">Random User</h6>
                            <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Necessitatibus quod quibusdam accusamus</p>
                            <div class="rating">
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="swiper-slide bg-white p-4">
                    <div class="profile d-flex align-item-center mb-3">
                        <img src="image/prj5.jpg" width="100px" alt="">
                        <div class="ms-2">
                            <h6 class="m-0">Random User</h6>
                            <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Necessitatibus quod quibusdam accusamus</p>
                            <div class="rating">
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="swiper-slide bg-white p-4">
                    <div class="profile d-flex align-item-center mb-3">
                        <img src="image/prj4.jpg" width="100px" alt="">
                        <div class="ms-2">
                            <h6 class="m-0">Random User</h6>
                            <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Necessitatibus quod quibusdam accusamus</p>
                            <div class="rating">
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
    
 <!-- Reach-Us -->

    <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">Reach Us</h2>
     <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-8 p-4 mb-lg-0 mb-3 bg-white rounded">
                <iframe class="w-100" height="400px" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3598.588942991707!2d85.18518567517695!3d25.585339077463047!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39ed5fff3ec02b23%3A0x2ea84ab2ed3fc06f!2sZero%20Mile%20More!5e0!3m2!1sen!2sin!4v1702838247717!5m2!1sen!2sin"     loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe> 

            </div>
            <div class="col-lg-4 col-md-8">
                <div class="bg-white rounded mb-4 text-center">
                    <h5 class="m-2 text-info">Call us</h5>
                    <a href="tel:+918102159736" class="d-inline-block m-2 mb-2 mb-0 text-decoration-none text-dark">
                        <i class="bi bi-telephone-outbound-fill me-1"></i> +918102159736
                    </a>
                    <br>
                    <a href="tel:+918102159736" class="d-inline-block m-2 mb-0 text-decoration-none text-dark">
                        <i class="bi bi-telephone-outbound-fill me-1"></i> +918102159736
                    </a>
                    <br><br>
                </div>
                
                <div class="bg-white text-center rounded mb-4">
                    <h5 class="m-2  text-info">Follow Us</h5>
                    <a href="#" class="d-inline-block mb-1">
                        <span class="badge bg-light text-dark m-2 fs-6 p-2">
                            <i class="bi bi-twitter-x me-1"></i>  Twitter
                        </span>
                    </a>
                    <br>

                    <a href="#" class="d-inline-block mb-1">
                        <span class="badge bg-light text-dark m-2 fs-6 p-2">
                            <i class="bi bi-instagram me-1"></i>  Instagram
                        </span>
                    </a>
                    <br>

                    <a href="#" class="d-inline-block mb-1">
                        <span class="badge bg-light text-dark m-2 fs-6 p-2">
                            <i class="bi bi-facebook me-1"></i>  Facebook
                        </span>
                    </a>
                    <br>

                    <a href="#" class="d-inline-block mb-1">
                        <span class="badge bg-light text-dark m-2 fs-6 p-2">
                            <i class="bi bi-whatsapp me-1"></i>  Whatsapp
                        </span>
                    </a>
                    <br>

                    <a href="#" class="d-inline-block ">
                        <span class="badge bg-light text-dark m-2 fs-6 p-2">
                            <i class="bi bi-envelope-at-fill me-1"></i>  Email
                        </span>
                    </a>
                </div>    
            </div>
            
        </div>
     </div>

    <!-- Footer -->
    <footer style="background-color: #ccc;">
      <div class="container">
        <div class="row">
          <div class="col-md-4">
            <span class="copyright">&copy; Simple House Rental System - <?php echo date("Y"); ?></span>
          </div>
          <div class="col-md-4">
            <ul class="list-inline social-buttons">
            <li class="list-inline-item">
                <a href="#">
                  <i class="fa fa-whatsapp"></i>
                </a>
              </li>
              <li class="list-inline-item">
                <a href="#">
                  <i class="fa fa-twitter"></i>
                </a>
              </li>
              <li class="list-inline-item">
                <a href="#">
                  <i class="fa fa-facebook"></i>
                </a>
              </li>
              <li class="list-inline-item">
                <a href="#">
                  <i class="fa fa-instagram"></i>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </footer>
   
    <!-- Bootstrap core JavaScript -->
    <script src="assets/plugins/jquery/jquery.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="assets/plugins/jquery-easing/jquery.easing.min.js"></script>

    <!-- Contact form JavaScript -->
    <script src="assets/js/jqBootstrapValidation.js"></script>
    <script src="assets/js/contact_me.js"></script>

    <!-- Custom scripts for this template -->
    <script src="assets/js/rent.js"></script>
  </body>
</html>
