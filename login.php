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
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

</head>
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
                                        <a class="small" href="index.php">Back to home page!</a>
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
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

</body>

</html>
<script>
    $(document).ready(function() {
        $('#loginForm').on('submit', function(e) {
            e.preventDefault();
            
            var email = $('#email').val().trim();
            var cpnumber = $('#cpnumber').val().trim();
            
            if (email === '' || cpnumber === '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'All fields are required.',
                    confirmButtonColor: '#d33',
                });
                return;
            }

            $.ajax({
                url: 'actions/login-user.php', // Path to your PHP login file
                type: 'POST',
                dataType: 'json',
                data: { email: email, cpnumber: cpnumber },
                success: function(response) {
                    console.log("Response:", response);
                    
                    if (response.status === "Success") {
                        // Assuming response.user is an array containing the patient and appointment details
                        var user = response.user[0]; // Access the first record for the patient details
                        
                        // Display user details in the designated div
                        var userDetails = `
                            <h3>Welcome, ${user.firstname} ${user.lastname}!</h3>
                            <p>Email: ${user.email}</p>
                            <p>Mobile: ${user.cpnumber}</p>
                            <p>Address: ${user.barangay}, ${user.municipal}, ${user.province}</p>
                            <hr>
                            <h4>Appointments:</h4>
                        `;

                        // Add each appointment if available
                        if (response.user.length > 0) {
                            response.user.forEach(function(appointment, index) {
                                // Determine the day of the week based on appointment_day value
                                var appointmentDay = '';
                                if (appointment.appointment_day === 2) {
                                    appointmentDay = 'Tuesday';
                                } else if (appointment.appointment_day === 5) {
                                    appointmentDay = 'Friday';
                                } else {
                                    appointmentDay = 'Other day'; // Default if it's neither 2 nor 5
                                }

                                userDetails += `
                                    <p>Appointment Day: ${appointmentDay}</p>
                                    <p>Date: ${appointment.appointment_date}</p>
                                    <p>Status: ${appointment.appointment_status}</p>
                                `;
                            });
                        } else {
                            userDetails += '<p>No appointments available.</p>';
                        }

                        $('#userDetails').html(userDetails); // Insert details into the div
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Login Error',
                            text: response.message,
                            confirmButtonColor: '#d33',
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error:", error);
                    Swal.fire(
                        'Error!',
                        'An error occurred while processing your request.',
                        'error'
                    );
                }
            });
        });
    });
</script>

