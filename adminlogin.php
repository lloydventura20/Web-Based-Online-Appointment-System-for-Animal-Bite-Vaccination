<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>ABTCadmin - Login</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body class="bg-success">
    <div class="container">
        <!-- Outer Row -->
        <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-8 col-md-9"> <!-- Adjusted the grid size here -->
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-12"> <!-- Changed to full width -->
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Log In your Profile!</h1>
                                    </div>
                                    <form class="user" id="loginForm">
                                    <div id="message" class="mt-3"></div> <!-- Div for displaying messages -->
                                        <div class="form-group">
                                            <input type="email" class="form-control form-control-user"
                                                id="exampleInputEmail" aria-describedby="emailHelp"
                                                placeholder="Enter Email Address..." name="email" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user"
                                                id="exampleInputPassword" placeholder="Password" name="password" required>
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary btn-user btn-block" id="loginButton">
                                            Login
                                        </button>
                                        <hr>
                                        <div class="text-center">
                                            <a href="index.php" class="small" id="home">-Home-</a>
                                        </div>
                                        <div class="text-center">
                                            <a href="admin/index.php" class="small" id="manageUserLink">-Manage User-</a>
                                        </div>
                                    </form>
                                 
                                </div>
                            </div>
                        </div>
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

    <!-- SweetAlert CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<!-- SweetAlert JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



</body>

</html>


<script>
    $(document).ready(function() {
        $("#loginForm").on('submit', function(e) {
            e.preventDefault();

           
            Swal.fire({
                title: 'Processing...',
                text: 'Please wait while we log you in.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading(); 
                }
            });

            $.ajax({
                type: "POST",
                url: "actions/login_admin.php",
                data: $(this).serialize(),
                success: function(response) {
                    Swal.close(); 
                    
                    if (response === "success") {
                        Swal.fire({
                            icon: 'success',
                            title: 'Login Successful',
                            text: 'Redirecting to dashboard...',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.href = "dashboard.php";
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Login Failed',
                            text: response
                        });
                    }
                },
                error: function() {
                    Swal.close(); 
                    Swal.fire({
                        icon: 'error',
                        title: 'An error occurred',
                        text: 'Please try again later.'
                    });
                }
            });
        });
    });
</script>


<script>
    $(document).ready(function(){
        $('#manageUserLink').on('click', function(event) {
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
        $('#home').on('click', function(event) {
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