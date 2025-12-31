<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>ABTC - Login</title>

    <!-- Custom fonts for this template-->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

</head>
<style>
    body {
            background: url('../img/img5.jpg') no-repeat center center fixed; /* Background image */
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
</style>

<body>
    <div class="bg-overlay">
    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg bg-success">
                                <!-- show profile -->
                                 <div class="p-5">
                                 <div id="userDetails" class="text-center text-light"></div>
                                 </div>

                            </div>

                            <div class="col-lg">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Log In your Profile!</h1>
                                    </div>
                                    <form id="loginForm" class="user">
                                        <div class="form-group">
                                            <input type="email" class="form-control form-control-user"
                                                id="email" aria-describedby="emailHelp"
                                                placeholder="Enter Email Address..." name="email">
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user"
                                                id="cpnumber" placeholder="Cellphone/Mobile Number" name="cpnumber">
                                        </div>
                                        <button type="submit" id="submit" name="submit" class="btn btn-primary btn-user btn-block">Login</button>
                                        
                                    </form>
                                    <hr>
                                 
                                    <div class="text-center">
                                        <a class="small" href="../index.php">Back to home page!</a>
                                    </div>
                                    <div class="text-center">
                                        <a class="small" href="../adminlogin.php">login</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
    </div>



    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../js/sb-admin-2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

</body>

</html>
<script>
  $(document).ready(function() {
    $('#loginForm').on('submit', function(e) {
        e.preventDefault();
        
        var email = $('#email').val();
        var cpnumber = $('#cpnumber').val();

        // Basic validation
        if (email === '' || cpnumber === '') {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please fill out all fields!'
            });
            return;
        }

        // Show loading spinner before AJAX request
        Swal.fire({
            title: 'Logging in...',
            text: 'Please wait',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading(); // Show loading spinner
            }
        });

        $.ajax({
            url: '../actions/login_patient.php',
            type: 'POST',
            data: {
                email: email,
                cpnumber: cpnumber
            },
            beforeSend: function() {
                // Show loading spinner (in case it's not shown by Swal.fire)
                Swal.fire({
                    title: 'Logging in...',
                    text: 'Please wait',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading(); // Ensure the loading spinner is showing before request
                    }
                });
            },
            success: function(response) {
                console.log(response);  // Log the response to debug

                // Ensure response is in JSON format
                var parsedResponse = JSON.parse(response);

                Swal.close(); // Close the loading spinner

                if (parsedResponse.success) {
                    // Show success message and redirect
                    Swal.fire({
                        icon: 'success',
                        title: 'Login Successful!',
                        text: 'Redirecting to your profile...',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(function() {
                        window.location.href = 'userdash.php';
                    });
                } else {
                    // Show login failed message
                    Swal.fire({
                        icon: 'error',
                        title: 'Login Failed!',
                        text: parsedResponse.message
                    });
                }
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);  // Log any errors for debugging
                Swal.close(); // Close the loading spinner
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'There was an issue with your request.'
                });
            }
        });
    });
});
</script>




