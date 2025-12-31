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
                <h1 class="h3 mb-0 text-gray-800">Patient Summary</h1>
                
            </div>
            

  <!-- DataTales Example -->
  <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Patient summary Table</h6>

                    <!-- Filter by Year and Month Form -->
                    <form method="GET" class="form-inline ml-auto">
                        <div class="form-group mr-2">
                            <select name="year" class="form-control form-control-sm">
                                <option value="">Select Year</option>
                                <?php 
                                // Generate years dynamically
                                $currentYear = date('Y');
                                for ($year = 2020; $year <= $currentYear; $year++) {
                                    // Retain selected value
                                    $selected = (isset($_GET['year']) && $_GET['year'] == $year) ? 'selected' : '';
                                    echo "<option value='$year' $selected>$year</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group mr-2">
                            <select name="month" class="form-control form-control-sm">
                                <option value="">Select Month</option>
                                <?php 
                                // Generate months dynamically
                                for ($m = 1; $m <= 12; $m++) {
                                    $month = date('F', mktime(0, 0, 0, $m, 1));
                                    // Retain selected value
                                    $selected = (isset($_GET['month']) && $_GET['month'] == $m) ? 'selected' : '';
                                    echo "<option value='$m' $selected>$month</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <!-- Separate Filter Button for Year and Month -->
                        <div class="ml-auto">
                            <button type="submit" class="btn btn-secondary btn-sm">Filter Year/Month</button>
                        </div>
                    </form>

                    <!-- Filter by Date Range Form -->
                    <form method="GET" class="form-inline ml-auto">
                        <div class="form-group mr-2">
                            <input type="date" name="start_date" class="form-control form-control-sm" value="<?php echo isset($_GET['start_date']) ? $_GET['start_date'] : ''; ?>" placeholder="Start Date">
                        </div>
                        <div class="form-group mr-2">
                            <input type="date" name="end_date" class="form-control form-control-sm" value="<?php echo isset($_GET['end_date']) ? $_GET['end_date'] : ''; ?>" placeholder="End Date">
                        </div>
                        <!-- Separate Filter Button for Date Range -->
                        <div class="ml-auto">
                            <button type="submit" class="btn btn-secondary btn-sm">Filter by Date Range</button>
                        </div>
                    </form>

                    <!-- Button Container for PDF/Excel -->
                        <div class="ml-auto">
                            <!-- Generate PDF Button -->
                            <a href="pdf-gen/generate_pdf_all.php?year=<?php echo isset($_GET['year']) ? $_GET['year'] : ''; ?>&month=<?php echo isset($_GET['month']) ? $_GET['month'] : ''; ?>&start_date=<?php echo isset($_GET['start_date']) ? $_GET['start_date'] : ''; ?>&end_date=<?php echo isset($_GET['end_date']) ? $_GET['end_date'] : ''; ?>" class="btn btn-sm btn-danger shadow-sm">
                                <i class="fas fa-download fa-sm text-white-50"></i> Generate PDF
                            </a>

                            <!-- Generate Excel Button -->
                            <a href="excel-gen/generate_excel_all.php?year=<?php echo isset($_GET['year']) ? $_GET['year'] : ''; ?>&month=<?php echo isset($_GET['month']) ? $_GET['month'] : ''; ?>&start_date=<?php echo isset($_GET['start_date']) ? $_GET['start_date'] : ''; ?>&end_date=<?php echo isset($_GET['end_date']) ? $_GET['end_date'] : ''; ?>" class="btn btn-sm btn-success shadow-sm ml-2">
                                <i class="fas fa-download fa-sm text-white-50"></i> Generate Excel
                            </a>
                        </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Name</th>
                                    <th>Age</th>
                                    <th>Address</th>
                                    <th>Cellphone Number</th>
                                    <th>Exposure</th>
                                    <th>First Dose</th>
                                    <th>Second Dose</th>
                                    <th>Third Dose</th>
                                    
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>No.</th>
                                    <th>Name</th>
                                    <th>Age</th>
                                    <th>Address</th>
                                    <th>Cellphone Number</th>
                                    <th>Exposure</th>
                                    <th>First Dose</th>
                                    <th>Second Dose</th>
                                    <th>Third Dose</th>
                                    
                                </tr>
                            </tfoot>
                            <tbody>
                                <?php
                                
                                $year = isset($_GET['year']) ? $_GET['year'] : '';
                                $month = isset($_GET['month']) ? $_GET['month'] : '';
                                $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
                                $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

                                
                                $sql = "SELECT p.patientid, p.firstname, p.midname, p.lastname, p.age, p.email, p.cpnumber, p.barangay, p.municipal, p.province, f.findingid, f.exposure, f.dose,f.d1,f.d3,f.d7, pq.queid, pq.updated_at
                                        FROM patients p
                                        LEFT JOIN findings f ON p.patientid = f.patientid
                                        LEFT JOIN patient_que pq ON p.patientid = pq.patientid
                                        WHERE pq.status IN ('0', '1', '2','3')";

                               
                                if (!empty($year)) {
                                    $sql .= " AND YEAR(pq.updated_at) = '$year'";
                                }
                                if (!empty($month)) {
                                    $sql .= " AND MONTH(pq.updated_at) = '$month'";
                                }

                                
                                if (!empty($start_date) && !empty($end_date)) {
                                    $sql .= " AND DATE(pq.updated_at) BETWEEN '$start_date' AND '$end_date'";
                                } elseif (!empty($start_date)) {
                                    
                                    $sql .= " AND DATE(pq.updated_at) >= '$start_date'";
                                } elseif (!empty($end_date)) {
                                    
                                    $sql .= " AND DATE(pq.updated_at) <= '$end_date'";
                                }

                                $no = 0;
                                $currentDate = date('Y-m-d');

                                $result = $conn->query($sql);
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            $no++;

                                            $d1_class = ($row['d1'] <= $row['updated_at']) ? 'text-success' : '';
                                            $d3_class = ($row['d3'] <= $row['updated_at']) ? 'text-success' : '';
                                            $d7_class = ($row['d7'] <= $row['updated_at']) ? 'text-success' : '';

                                            echo "<tr>
                                                <td>$no.</td>
                                                <td>{$row['firstname']} {$row['midname']} {$row['lastname']}</td>
                                                <td>{$row['age']}</td>
                                                <td>{$row['barangay']}, {$row['municipal']}, {$row['province']}</td>
                                                <td>{$row['cpnumber']}</td>
                                                <td>{$row['exposure']}</td>
                                                <td class='$d1_class'>{$row['d1']}</td>
                                                <td class='$d3_class'>{$row['d3']}</td>
                                                <td class='$d7_class'>{$row['d7']}</td>
                                            </tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='10'>No records found</td></tr>";
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



