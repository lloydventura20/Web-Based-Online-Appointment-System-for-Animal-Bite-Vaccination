<?php 
include 'includes/header.php';
include 'includes/navbar.php';
include '../db/connection.php'; // Assuming you have a database connection file

// Query to count the number of admins
$adminQuery = "SELECT COUNT(*) as admin_count FROM user WHERE role = 'admin'";
$adminResult = mysqli_query($conn, $adminQuery);
$adminRow = mysqli_fetch_assoc($adminResult);
$adminCount = $adminRow['admin_count'];

// Query to count the number of users
$userQuery = "SELECT COUNT(*) as user_count FROM user WHERE role = 'user'";
$userResult = mysqli_query($conn, $userQuery);
$userRow = mysqli_fetch_assoc($userResult);
$userCount = $userRow['user_count'];

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
                <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
            </div>

            <!-- Content Row -->
            <div class="row">

                <!-- Display Admin Count -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Admins</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $adminCount; ?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-clipboard-user fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Display User Count -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                        Users</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $userCount; ?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-user fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- End of Content Row -->

        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- End of Main Content -->

<?php 
include 'includes/scripts.php';
include 'includes/footer.php';
?>
