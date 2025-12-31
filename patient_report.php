<?php 
include 'includes/header.php';
include 'includes/navbar.php';
include 'db/connection.php'; // Include the database connection

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
                <h1 class="h3 mb-0 text-gray-800">Patient Report - Animal Incidents</h1>
            </div>

            <!-- Filter Section -->
            <form method="GET" action="">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="start_date">Start Date</label>
                        <input type="date" id="start_date" name="start_date" class="form-control" value="<?= $_GET['start_date'] ?? date('Y-01-01') ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="end_date">End Date</label>
                        <input type="date" id="end_date" name="end_date" class="form-control" value="<?= $_GET['end_date'] ?? date('Y-m-d') ?>">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </div>
            </form>

            <?php
            // Get filter values
            $start_date = $_GET['start_date'] ?? date('Y-01-01');
            $end_date = $_GET['end_date'] ?? date('Y-m-d');

            // Query for Animal Incidents Summary
            $query1 = "
                SELECT 
                    animal_class, 
                    animal_type, 
                    gender, 
                    COUNT(*) AS total 
                FROM findings 
                JOIN patient_que ON findings.findingid = patient_que.findingsid 
                JOIN patients ON patient_que.patientid = patients.patientid 
                WHERE patient_que.created_at BETWEEN '$start_date' AND '$end_date'
                GROUP BY animal_class, animal_type, gender
            ";
            $result1 = mysqli_query($conn, $query1);

            // Query for Barangay Incidents and Age Groups
            $query2 = "
                SELECT 
                    CONCAT(barangay, ', ', municipal) AS location,
                    SUM(CASE WHEN age <= 15 THEN 1 ELSE 0 END) AS age_15_or_less,
                    SUM(CASE WHEN age > 15 THEN 1 ELSE 0 END) AS age_greater_than_15
                FROM patients 
                JOIN patient_que ON patients.patientid = patient_que.patientid 
                WHERE patient_que.created_at BETWEEN '$start_date' AND '$end_date'
                GROUP BY location
            ";
            $result2 = mysqli_query($conn, $query2);
            ?>

            <!-- Animal Incidents Summary Table -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Animal Incidents Summary</h6>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Animal Class</th>
                                <th>Animal Type</th>
                                <th>Gender</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $grand_total = 0;
                            while ($row = mysqli_fetch_assoc($result1)) {
                                $grand_total += $row['total'];
                                echo "<tr>
                                    <td>{$row['animal_class']}</td>
                                    <td>{$row['animal_type']}</td>
                                    <td>{$row['gender']}</td>
                                    <td>{$row['total']}</td>
                                </tr>";
                            }
                            ?>
                            <tr>
                                <th colspan="3" class="text-right text-success">Total</th>
                                <th class="text-success"><?= $grand_total ?></th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Barangay Incidents and Age Groups Table -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Barangay Incidents and Age Groups</h6>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Barangay</th>
                                <th>Age <= 15</th>
                                <th>Age > 15</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total_age_15_or_less = 0;
                            $total_age_greater_than_15 = 0;

                            while ($row = mysqli_fetch_assoc($result2)) {
                                $total_age_15_or_less += $row['age_15_or_less'];
                                $total_age_greater_than_15 += $row['age_greater_than_15'];

                                echo "<tr>
                                    <td>{$row['location']}</td>
                                    <td>{$row['age_15_or_less']}</td>
                                    <td>{$row['age_greater_than_15']}</td>
                                </tr>";
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th class="text-success">Total</th>
                                <th class="text-success"><?= $total_age_15_or_less ?></th>
                                <th class="text-success"><?= $total_age_greater_than_15 ?></th>
                            </tr>
                        </tfoot>
                    </table>
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
