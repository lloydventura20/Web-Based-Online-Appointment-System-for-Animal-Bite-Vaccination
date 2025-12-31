<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>ABTC - Appointment</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        body {
            background: url('img/img5.jpg') no-repeat center center fixed; /* Background image */
            background-size: cover; /* Cover the entire page */
            position: relative;
            margin: 0;
            padding: 0;
            height: 100vh; /* Full height for proper centering */
        }

        .bg-overlay {
            background-color: rgba(40, 167, 69, 0.6); /* Light green overlay with transparency */
            min-height: 100vh;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            position: absolute;
            top: 0;
            left: 0;
        }

        .card-custom {
            background-color: rgba(255, 255, 255, 0.85);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            z-index: 1; /* Ensure content is above the overlay */
        }

        .title-section {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
        }

        .title-section h2 {
            margin: 0 5px; /* Add margin for spacing between words */
        }
    </style>
    
</head>

<body>
    <!-- Light Green Overlay -->
    <div class="bg-overlay">
        <div class="container d-flex justify-content-center align-items-center">
            <!-- Right side: Appointment and Login -->
            <div class="col-md-6">
                <div class="card card-custom text-center">
                    <div class="title-section">
                        <h2 class="text-dark"><span class="text-success">A</span>nimal</h2>
                        <h2 class="text-dark"><span class="text-success">B</span>ite</h2>
                        <h2 class="text-dark"><span class="text-success">T</span>reatment</h2>
                        <h2 class="text-dark"><span class="text-success">C</span>enter</h2>
                    </div>
                    <p class="lead mt-3">Online Appointment for Animal Bite Vaccination</p>
                    <div class="mt-4">
                        <a href="register.php" class="btn btn-primary btn-lg rounded-pill mb-2" id="setAppointment" data-intro="Click here to set an appointment." data-step="1">
                            <i class="fas fa-calendar-plus"></i> Set Appointment
                        </a>
                        <div class="d-block d-sm-inline mx-sm-2">-or-</div>
                        <a href="adminlogin.php" class="btn btn-success btn-lg rounded-pill mt-2 mt-sm-0" id="login" data-intro="Login if you already have an account." data-step="2">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
    <!-- Include Intro.js CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intro.js/minified/introjs.min.css">
<!-- Include Intro.js JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/intro.js/minified/intro.min.js"></script>

     <!-- SweetAlert CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<!-- SweetAlert JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>

<script>
    $(document).ready(function(){
        $('#setAppointment').on('click', function(event) {
            event.preventDefault(); 

         
            Swal.fire({
                title: 'Loading...',
                text: 'Please wait for a while...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading(); 
                }
            });

            
            var linkHref = $(this).attr('href'); 
            setTimeout(function() {
                window.location.href = linkHref; 
            }, 2000); 
        });
    });
</script>

<script>
    $(document).ready(function(){
        $('#setAppointment').on('click', function(event) {
            event.preventDefault(); 

         
            Swal.fire({
                title: 'Loading...',
                text: 'Please wait for a while...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading(); 
                }
            });

            
            var linkHref = $(this).attr('href'); 
            setTimeout(function() {
                window.location.href = linkHref; 
            }, 2000); 
        });
    });
</script>

<script>
    $(document).ready(function(){
        $('#login').on('click', function(event) {
            event.preventDefault(); 

         
            Swal.fire({
                title: 'Loading...',
                text: 'Please wait for a while...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading(); 
                }
            });

            
            var linkHref = $(this).attr('href'); 
            setTimeout(function() {
                window.location.href = linkHref; 
            }, 2000); 
        });
    });
</script>
<script>
$(document).ready(function() {
    // Start the Intro.js tour when the page is loaded
    var intro = introJs();

    // Set options to customize the tour
    intro.setOptions({
        steps: [
            {
                element: '#setAppointment',
                intro: 'Click here to set an appointment.',
                position: 'bottom'
            },
            {
                element: '#login',
                intro: 'Login if you already have an account.',
                position: 'bottom'
            }
        ],
        showBullets: false, // Disable step bullets
        exitOnOverlayClick: false, // Disable exiting on overlay click
        disableInteraction: false, // Allow interaction during the tour
        hidePrev: true, // Hide the previous button
        hideNext: true, // Hide the next button
        showButtons: false // Hide all navigation buttons
    });

    
    $(document).on('click', '.introjs-overlay', function() {
        intro.nextStep();
    });

  
    intro.start();
});
</script>
