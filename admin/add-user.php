<?php 
include 'includes/header.php';
include 'includes/navbar.php';
?>

<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">

    <!-- Main Content -->
    <div id="content">

        <?php include 'includes/topbar.php'; ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Add User</h1>
            </div>

            <!-- Add User Form in a Smaller Card -->
            <div class="row justify-content-center">
                <div class="col-md-6"> <!-- Adjust the width with col-md-6 to make it smaller -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-dark"><i class="fas fa-user text-dark"></i> User Information</h6>
                        </div>
                        <div class="card-body">
                            <form action="actions/add-user.php" method="POST">
                                <div class="form-group">
                                    <label for="firstname">First Name</label>
                                    <input type="text" class="form-control" id="firstname" name="firstname" placeholder="Enter First Name" required>
                                </div>
                                <div class="form-group">
                                    <label for="midname">Middle Name</label>
                                    <input type="text" class="form-control" id="midname" name="midname" placeholder="Enter Middle Name" required>
                                </div>
                                <div class="form-group">
                                    <label for="lastname">Last Name</label>
                                    <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Enter Last Name" required>
                                </div>

                                <div class="form-group">
                                    <label for="email">Email address</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
                                </div>

                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
                                    <small id="password-feedback" class="form-text text-danger"></small>
                                </div>

                                <div class="form-group">
                                    <label for="role">Role</label>
                                    <select class="form-control" id="role" name="role" required>
                                        <option value="">Select role</option>
                                        <option value="admin">Admin</option>
                                        <option value="user">User</option>
                                    </select>
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-success"><i class="fas fa-add fa-sm text-white"></i> Add User</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- End of Main Content -->

<?php 
include 'includes/scripts.php';
include 'includes/footer.php';
?>
<script>
    $(document).ready(function(){
        // Listen for keyup event on the password field
        $('#password').on('keyup', function() {
            var password = $(this).val();
            
            if (password.length >= 8) {
                $('#password-feedback').removeClass('text-danger').addClass('text-success').text('Password looks good!').css('color', 'green');
            } else if (password.length > 0) {
                $('#password-feedback').removeClass('text-success').addClass('text-danger').text('Password must be at least 8 characters long.').css('color', 'red');
            } else {
                $('#password-feedback').removeClass('text-success text-danger').text('');
            }
        });

        // Handle form submission
        $('form').on('submit', function(event){
            event.preventDefault();

            var password = $('#password').val();

            // Check if the password is at least 8 characters long
            if(password.length < 8) {
                Swal.fire({
                    icon: 'error',
                    title: 'Password Too Short',
                    text: 'Password must be at least 8 characters long.',
                });
                return false; // Prevent form submission
            }

            // If the password is valid, show a success notification
            $.ajax({
                url: 'actions/add-user.php',
                method: 'POST',
                data: $(this).serialize(),
                success: function(response){
                    Swal.fire({
                        icon: 'success',
                        title: 'User Added',
                        text: response,
                    }).then(function() {
                        location.reload(); // Reload the page after SweetAlert confirmation
                    });
                },
                error: function(){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'There was an error adding the user.',
                    });
                }
            });
        });
    });
</script>
