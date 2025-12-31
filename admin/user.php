<?php 
include 'includes/header.php';
include 'includes/navbar.php';
include '../db/connection.php';
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
                <h1 class="h3 mb-0 text-gray-800">Users</h1>
            </div>

            <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">Admin / User Table</h6>
                            <div class="ml-auto">
                                <a href="add-user.php" class="btn btn-sm btn-success shadow-sm">
                                    <i class="fas fa-add fa-sm text-white-50"></i> Add User
                                </a>
                            </div>
                        </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                <?php
                                // Query to fetch all users from the 'user' table
                                $query = "SELECT userid, firstname, midname, lastname, email, password,role FROM user";
                                $result = mysqli_query($conn, $query);

                                // Check if there are any users
                                if (mysqli_num_rows($result) > 0) {
                                    $no = 1; // Initialize row number
                                    // Loop through all users and display them in the table
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $fullName = $row['firstname'] . ' ' . $row['midname'] . ' ' . $row['lastname'];
                                        echo "<tr>";
                                        echo "<td>" . $no++ . "</td>"; // Increment the row number
                                        echo "<td>" . $fullName . "</td>";
                                        echo "<td>" . $row['email'] . "</td>";
                                        echo "<td>" . $row['role'] . "</td>";
                                        echo "<td>
                                            <button type='button' name='edit' class='btn btn-success btn-sm edit-btn' data-toggle='modal' data-target='#editUserModal' data-userid='" . $row["userid"] . "' data-firstname='" . $row['firstname'] . "' data-midname='" . $row['midname'] . "' data-lastname='" . $row['lastname'] . "' data-email='" . $row['email'] . "'>
                                                <i class='fas fa-fw fa-right-to-bracket'></i> Edit
                                            </button>
                                            <button class='btn btn-danger btn-sm delete-user' data-id='" . $row['userid'] . "'><i class='fas fa-fw fa-trash'></i></button>
                                        </td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='4'>No users found</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Edit User Modal -->

                    <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="edit-user.php" method="POST">
                                    <div class="modal-body">
                                        <input type="hidden" name="userid" id="editUserId">
                                        <div class="form-group">
                                            <label for="firstname">First Name</label>
                                            <input type="text" class="form-control" id="firstname" name="firstname" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="midname">Middle Name</label>
                                            <input type="text" class="form-control" id="midname" name="midname" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="lastname">Last Name</label>
                                            <input type="text" class="form-control" id="lastname" name="lastname" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" class="form-control" id="email" name="email" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="password">Password</label>
                                            <input type="password" class="form-control" id="password" name="password">
                                            <small class="form-text text-muted">Leave this blank if you do not want to change the password.</small>
                                            <small id="passwordHelp" class="form-text text-danger"></small>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary" name="submit" id="submit">Save changes</button>
                                    </div>
                                </form>
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
    // show data in modal
    $(document).on('click', '.edit-btn',function() {
        var userid = $(this).data('userid');
        var firstname = $(this).data('firstname');
        var midname = $(this).data('midname');
        var lastname = $(this).data('lastname');
        var email = $(this).data('email');

        // value of modal
        $('#editUserId').val(userid);
        $('#firstname').val(firstname);
        $('#midname').val(midname);
        $('#lastname').val(lastname);
        $('#email').val(email);

        // Show the modal (if using a modal)
        // $('#editUserModal').modal('show');
    });
    // Keyup validation for password field
    $('#password').on('keyup', function() {
    var password = $(this).val();
    
    if (password.length >= 8) {
        $('#passwordHelp').removeClass('text-danger').addClass('text-success').text('Password looks good!').css('color', 'green');
    } else if (password.length > 0) {
        $('#passwordHelp').removeClass('text-success').addClass('text-danger').text('Password must be at least 8 characters long.').css('color', 'red');
    } else {
        $('#passwordHelp').removeClass('text-success text-danger').text('');
    }
});


    
    $('#editUserModal').on('submit', 'form', function(e) {
        e.preventDefault(); 

       
        var formData = $(this).serialize();
        var password = $('#password').val(); 

      
        if (password.length > 0 && password.length < 8) {
            Swal.fire({
                title: 'Error!',
                text: 'Password must be at least 8 characters long.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        } else {
            $.ajax({
                url: 'actions/edit-user.php', 
                type: 'POST',
                data: formData,
                success: function(response) {
                    Swal.fire({
                        title: 'Success!',
                        text: 'User updated successfully!',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload(); 
                        }
                    });
                },
                error: function(xhr, status, error) {
                    // Display SweetAlert error notification
                    Swal.fire({
                        title: 'Error!',
                        text: 'An error occurred while updating the user.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }
    });
</script>

<script>
    $(document).on('click', '.delete-user', function() {
        var userId = $(this).data('id'); // Get the user ID from the button's data attribute

        // Confirm deletion using SweetAlert2
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Send AJAX request to delete the user
                $.ajax({
                    url: 'actions/delete-user.php',
                    method: 'POST',
                    data: { userid: userId },
                    success: function(response) {
                        if (response == "success") {
                            Swal.fire(
                                'Deleted!',
                                'User has been deleted.',
                                'success'
                            ).then(function() {
                                location.reload(); // Reload the page after deletion
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                'There was a problem deleting the user.',
                                'error'
                            );
                        }
                    },
                    error: function() {
                        Swal.fire(
                            'Error!',
                            'There was an error sending the request.',
                            'error'
                        );
                    }
                });
            }
        });
    });
</script>

