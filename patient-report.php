<?php 
include 'includes/header.php';
include 'includes/navbar.php';
include 'db/connection.php'; // Include the database connection

// Fetch the start and end date from the GET request
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

// Query to get animal incident data grouped by animal type and gender with date range filtering
$query1 = "SELECT 
            animal_type, 
            COUNT(findingid) AS no_of_incidents,
            SUM(CASE WHEN gender = 'male' THEN 1 ELSE 0 END) AS male,
            SUM(CASE WHEN gender = 'female' THEN 1 ELSE 0 END) AS female,
            COUNT(findingid) AS total
          FROM findings
          JOIN patients ON findings.patientid = patients.patientid
          WHERE 1=1";

// Apply the date range filter for d1
if (!empty($start_date) && !empty($end_date)) {
    $query1 .= " AND d1 BETWEEN '$start_date' AND '$end_date'";
} elseif (!empty($start_date)) {
    $query1 .= " AND d1 >= '$start_date'";
} elseif (!empty($end_date)) {
    $query1 .= " AND d1 <= '$end_date'";
}

$query1 .= " GROUP BY animal_type";

$result1 = mysqli_query($conn, $query1);

// Query to get barangay-wise incidents and age group distribution with date range filtering
$query2 = "SELECT 
            patients.barangay,
            COUNT(findingid) AS no_of_incidents,
            SUM(CASE 
                WHEN TIMESTAMPDIFF(YEAR, patients.date_of_birth, CURDATE()) < 15 THEN 1 ELSE 0 END) AS age_less_15,
            SUM(CASE 
                WHEN TIMESTAMPDIFF(YEAR, patients.date_of_birth, CURDATE()) >= 15 THEN 1 ELSE 0 END) AS age_15_above
          FROM findings
          JOIN patients ON findings.patientid = patients.patientid
          WHERE 1=1";

// Apply the date range filter for d1
if (!empty($start_date) && !empty($end_date)) {
    $query2 .= " AND d1 BETWEEN '$start_date' AND '$end_date'";
} elseif (!empty($start_date)) {
    $query2 .= " AND d1 >= '$start_date'";
} elseif (!empty($end_date)) {
    $query2 .= " AND d1 <= '$end_date'";
}

$query2 .= " GROUP BY patients.barangay";

$result2 = mysqli_query($conn, $query2);

// Initialize total counters for the first table
$total_incidents = 0;
$total_male = 0;
$total_female = 0;
$total_overall = 0;

// Initialize total counters for the second table
$total_barangay_incidents = 0;
$total_age_less_15 = 0;
$total_age_15_above = 0;
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

            <!-- Date Range Filter Form -->
            <form method="GET" class="form-inline mb-4">
                <div class="form-group mr-2">
                    <label for="start_date">Start Date: </label>
                    <input type="date" name="start_date" class="form-control" value="<?php echo isset($_GET['start_date']) ? $_GET['start_date'] : ''; ?>">
                </div>
                <div class="form-group mr-2">
                    <label for="end_date">End Date: </label>
                    <input type="date" name="end_date" class="form-control" value="<?php echo isset($_GET['end_date']) ? $_GET['end_date'] : ''; ?>">
                </div>
                <button type="submit" class="btn btn-primary">Filter</button>
            </form>

           <!-- Table to display the animal incidents report -->
           <div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Animal Incidents Summary</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Animal Type</th>
                        <th>No. of Bites</th>
                        <th>Male</th>
                        <th>Female</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total_incidents = $total_male = $total_female = $total_overall = 0;

                    if (mysqli_num_rows($result1) > 0) {
                        $chartData = [];
                        while ($row1 = mysqli_fetch_assoc($result1)) {
                            // Add to total counters
                            $total_incidents += $row1['no_of_incidents'];
                            $total_male += $row1['male'];
                            $total_female += $row1['female'];
                            $total_overall += $row1['total'];

                            // Store data for the chart
                            $chartData[] = [
                                "animal_type" => $row1['animal_type'],
                                "no_of_incidents" => $row1['no_of_incidents'],
                                "male" => $row1['male'],
                                "female" => $row1['female'],
                                "total" => $row1['total']
                            ];

                            echo "<tr>";
                            echo "<th>" . $row1['animal_type'] . "</th>";
                            echo "<td>" . $row1['no_of_incidents'] . "</td>";
                            echo "<td>" . $row1['male'] . "</td>";
                            echo "<td>" . $row1['female'] . "</td>";
                            echo "<td>" . $row1['total'] . "</td>";
                            echo "</tr>";
                        }

                        // Display the total row
                        echo "<tr>";
                        echo "<th>Total</th>";
                        echo "<td>" . $total_incidents . "</td>";
                        echo "<td>" . $total_male . "</td>";
                        echo "<td>" . $total_female . "</td>";
                        echo "<td>" . $total_overall . "</td>";
                        echo "</tr>";
                    } else {
                        echo "<tr><td colspan='5'>No records found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

            <!-- New table for barangay-wise incidents and age groups -->
            <div class="card shadow mb-4 mt-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Barangay Incidents and Age Groups</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Barangay</th>
                        <th>No. of Incidents</th>
                        <th>Age (<15)</th>
                        <th>Age (15>)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (mysqli_num_rows($result2) > 0) {
                        while ($row2 = mysqli_fetch_assoc($result2)) {
                            // Add to total counters for barangay-wise data
                            $total_barangay_incidents += $row2['no_of_incidents'];
                            $total_age_less_15 += $row2['age_less_15'];
                            $total_age_15_above += $row2['age_15_above'];

                            echo "<tr>";
                            echo "<th>" . $row2['barangay'] . "</th>";
                            echo "<td>" . $row2['no_of_incidents'] . "</td>";
                            echo "<td>" . $row2['age_less_15'] . "</td>";
                            echo "<td>" . $row2['age_15_above'] . "</td>";
                            echo "</tr>";
                        }

                        // Display the total row for barangay-wise data
                        echo "<tr>";
                        echo "<th>Total</th>";
                        echo "<td>" . $total_barangay_incidents . "</td>";
                        echo "<td>" . $total_age_less_15 . "</td>";
                        echo "<td>" . $total_age_15_above . "</td>";
                        echo "</tr>";
                    } else {
                        echo "<tr><td colspan='4'>No records found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
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
