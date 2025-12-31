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
                <h1 class="h3 mb-0 text-gray-800">Patient Record</h1>
                <a href="todays-record.php" class="d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-folder fa-sm text-white-50"></i> Today's Record</a>
            </div>
            

  <!-- DataTales Example -->
  <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Patient Record Table</h6>

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
                                    <th>Email</th>
                                    <th>Cellphone Number</th>
                                    <th>Exposure</th>
                                    <th>Dose</th>
                                    <th>Vaccine Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>No.</th>
                                    <th>Name</th>
                                    <th>Age</th>
                                    <th>Address</th>
                                    <th>Email</th>
                                    <th>Cellphone Number</th>
                                    <th>Exposure</th>
                                    <th>Dose</th>
                                    <th>Vaccine Date</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                <?php
                                // Fetch filter values from the GET request
                                $year = isset($_GET['year']) ? $_GET['year'] : '';
                                $month = isset($_GET['month']) ? $_GET['month'] : '';
                                $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
                                $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

                                // Build the base SQL query
                                $sql = "SELECT p.patientid, p.firstname, p.midname, p.lastname, p.age, p.email, p.cpnumber, p.barangay, p.municipal, p.province, f.findingid, f.exposure, f.dose, pq.queid, pq.updated_at
                                        FROM patients p
                                        LEFT JOIN findings f ON p.patientid = f.patientid
                                        LEFT JOIN patient_que pq ON p.patientid = pq.patientid
                                        WHERE pq.status IN ('0', '1', '2','3')";

                                // Add year and month filters if selected
                                if (!empty($year)) {
                                    $sql .= " AND YEAR(pq.updated_at) = '$year'";
                                }
                                if (!empty($month)) {
                                    $sql .= " AND MONTH(pq.updated_at) = '$month'";
                                }

                                // Add date range filter if start and end dates are selected
                                if (!empty($start_date) && !empty($end_date)) {
                                    $sql .= " AND DATE(pq.updated_at) BETWEEN '$start_date' AND '$end_date'";
                                } elseif (!empty($start_date)) {
                                    // If only start date is provided
                                    $sql .= " AND DATE(pq.updated_at) >= '$start_date'";
                                } elseif (!empty($end_date)) {
                                    // If only end date is provided
                                    $sql .= " AND DATE(pq.updated_at) <= '$end_date'";
                                }

                                $no = 0;

                                $result = $conn->query($sql);
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        $no++;
                                        echo "<tr>
                                                <td>$no.</td>
                                                <td>{$row['firstname']} {$row['midname']} {$row['lastname']}</td>
                                                <td>{$row['age']}</td>
                                                <td>{$row['barangay']}, {$row['municipal']}, {$row['province']}</td>
                                                <td>{$row['email']}</td>
                                                <td>{$row['cpnumber']}</td>
                                                <td>{$row['exposure']}</td>
                                                <td>{$row['dose']}</td>
                                                <td>{$row['updated_at']}</td>
                                                <td>
                                                    <button type='button' name='patientid' class='btn btn-success btn-sm consult-btn' data-toggle='modal' data-target='#consultationModal' data-patientid='" . $row["patientid"] . "' data-findingid='" . $row["findingid"] . "' data-queid='" . $row["queid"] . "' data-toggle='tooltip' title='Consult'>
                                                        <i class='fas fa-fw fa-right-to-bracket'></i> 
                                                    </button>
                                                    <button type='button' name='print' class='btn btn-warning btn-sm consult-btn' data-patientid='" . $row["patientid"] . "' data-findingid='" . $row["findingid"] . "' data-queid='" . $row["queid"] . "' data-toggle='tooltip' title='Print'>
                                                        <i class='fas fa-fw fa-download'></i> 
                                                    </button>
                                                    <button type='button' name='delete' class='btn btn-danger btn-sm delete-appointment' data-patientid='" . $row["patientid"] . "' data-toggle='tooltip' title='Delete'>
                                                        <i class='fas fa-fw fa-trash'></i> 
                                                    </button>
                                                </td>
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




            <!-- modal -->
                            <div class="modal fade" id="consultationModal" tabindex="-1" role="dialog" aria-labelledby="consultationModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-success">
                                <h5 class="modal-title text-light" id="consultationModalLabel">Consultation Details</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <!--  form  -->
                                <div class="card">
                                    <div class="card-header bg-info">
                                        <!-- <p></p> -->
                                    </div>
                                    <div class="card-body">
                                    
                                        <!-- form -->
                                        <form id="patientForm" action="actions/save_patient_record.php" method="POST">
                                        <h5 class="card-title">Personal Information</h5>
                                        <hr class="sidebar-divider">
                                                <div class="form-row">
                                                
                                                    <div class="form-group col-md-3">
                                                    <label for="firstname">First Name</label>
                                                    <input type="text" class="form-control" id="firstname" name="firstname" required>
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                    <label for="midname">Middle Name</label>
                                                    <input type="text" class="form-control" id="midname" name="midname" required>
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                    <label for="lastname">Last Name</label>
                                                    <input type="text" class="form-control" id="lastname" name="lastname" required>
                                                    </div>
                                                    <div class="form-group col-md-2">
                                                    <label for="sufix">Sufix Name</label>
                                                    <select class="custom-select" id="sufix" name="sufix" required>
                                                        <option selected disabled value="">Choose...</option>
                                                        <option>N/A</option>
                                                        <option>JR.</option>
                                                        <option>III</option>
                                                        <option>IV</option>
                                                        <option>V</option>
                                                    </select>
                                                    </div>
                                                    <div class="form-group col-md-1">
                                                    <label for="gender">Gender</label>
                                                    <select class="custom-select" id="gender" name="gender" required>
                                                        <option selected disabled value="">Choose...</option>
                                                        <option>Male</option>
                                                        <option>Female</option>
                                                        <option>Others</option>
                                                    </select>
                                                    </div>
                                                </div>

                                                <div class="form-row">
                                                    <div class="form-group col-md-2">
                                                    <label for="date_of_birth">Date Of Birth</label>
                                                    <input type="text" class="form-control" id="date_of_birth" name="date_of_birth" required>
                                                    </div>
                                                    <div class="form-group col-md-1">
                                                    <label for="age">Age</label>
                                                    <input type="text" class="form-control" id="age" name="age" required>
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                    <label for="civil_status">Civil Status</label>
                                                    <select class="custom-select" id="civil_status" name="civil_status" required>
                                                        <option selected disabled value="">Choose...</option>
                                                        <option>Single</option>
                                                        <option>Married</option>
                                                        <option>Widowed</option>
                                                    </select>
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                    <label for="place_of_birth">Place of Birth</label>
                                                    <input type="text" class="form-control" id="place_of_birth" name="place_of_birth" required>
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                    <label for="nationality">Nationality</label>
                                                    <input type="text" class="form-control" id="nationality" name="nationality" required>
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                    <label for="religion">Religion</label>
                                                    <input type="text" class="form-control" id="religion" name="religion" required>
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                    <label for="occupation">Occupation</label>
                                                    <input type="text" class="form-control" id="occupation" name="occupation" required>
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                    <label for="guardian_name">Guardian Name</label>
                                                    <input type="text" class="form-control" id="guardian_name" name="guardian_name">
                                                    </div>
                                                </div>

                                            

                                                <!-- contact -->
                                                <hr class="sidebar-divider">
                                                <h5 class="card-title">Contact Information</h5>
                                                <hr class="sidebar-divider">
                                                <div class="form-row">
                                                    <div class="form-group col-md-3">
                                                    <label for="cpnumber">Cellphone Number</label>
                                                    <input type="text" class="form-control" id="cpnumber" name="cpnumber" required>
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                    <label for="email">Email</label>
                                                    <input type="email" class="form-control" id="email" name="email" required>
                                                    </div>
                                                </div>
                                                <!--end of contact -->

                                                <!-- address -->
                                                <hr class="sidebar-divider">
                                                <h5 class="card-title">Address Information</h5>
                                                <hr class="sidebar-divider">
                                                <div class="form-row">
                                                    <div class="form-group col-md-3">
                                                        <label for="region">Region</label>
                                                        <input type="text" class="form-control" id="region" name="region" required>
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label for="province">Province</label>
                                                        <input type="text" class="form-control" id="province" name="province" required>
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label for="municipal">Municipal</label>
                                                        <input type="text" class="form-control" id="municipal" name="municipal" required>
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label for="barangay">Barangay</label>
                                                        <input type="text" class="form-control" id="barangay" name="barangay" required>
                                                    </div>                  
                                                </div>
                                                <!-- end of address -->
                                                <hr class="sidebar-divider">
                                                <h5 class="card-title">Consultation</h5>
                                                <hr class="sidebar-divider">
                                                <div class="form-row">
                                                <div class="form-group col-md-3">
                                                    <label for="exposure">Exposure</label>
                                                    <select class="custom-select" id="exposure" name="exposure" required>
                                                        <option selected disabled value="">Choose...</option>
                                                        <option>Pre Exposure</option>
                                                        <option>Post Exposure</option>
                                                        <option>Booster</option>
                                                    </select>
                                                    </div>
                                                <div class="form-group col-md-3">
                                                    <label for="dose">Dose</label>
                                                    <select class="custom-select" id="dose" name="dose" required>
                                                        <option selected disabled value="">Choose...</option>
                                                        <option>1st Dose</option>
                                                        <option>2nd Dose</option>
                                                        <option>3rd Dose</option>
                                                    </select>
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                    <label for="animal_type">Animal Type</label>
                                                    <select class="custom-select" id="animal_type" name="animal_type" required>
                                                        <option selected disabled value="">Choose...</option>
                                                        <option>Dog</option>
                                                        <option>Cat</option>
                                                        <option>Others</option>
                                                        <option>NA</option>
                                                    </select>
                                                    </div>

                                                    <div class="form-group col-md-3">
                                                    <label for="category">Category</label>
                                                    <select class="custom-select" id="category" name="category" required>
                                                        <option selected disabled value="">Choose...</option>
                                                        <option>I</option>
                                                        <option>II</option>
                                                        <option>III</option>
                                                        <option>NA</option>
                                                    </select>
                                                    </div>

                                                    <div class="form-group col-md-3">
                                                    <label for="vaccine_type">Vaccine Type</label>
                                                    <select class="custom-select" id="vaccine_type" name="vaccine_type" required>
                                                        <option selected disabled value="">Choose...</option>
                                                        <option>Verorab</option>
                                                        <option>Rabipur</option>
                                                    </select>
                                                    </div>

                                                    <div class="form-group col-md-3">
                                                    <label for="wound_type">Wound Type</label>
                                                    <select class="custom-select" id="wound_type" name="wound_type" required>
                                                        <option selected disabled value="">Choose...</option>
                                                        <option>Bite</option>
                                                        <option>Scratch</option>
                                                        <option>NA</option>
                                                    </select>
                                                    </div>

                                                    <div class="form-group col-md-3">
                                                    <label for="sob">Sight of Bite</label>
                                                    <select class="custom-select" id="sob" name="sob" required>
                                                        <option selected disabled value="">Choose...</option>
                                                        <option>Right Arm</option>
                                                        <option>Left Arm</option>
                                                        <option>Upper Body</option>
                                                        <option>Lower Body</option>
                                                        <option>Left Leg</option>
                                                        <option>Right Leg</option>
                                                        <option>Neck</option>
                                                        <option>Above Neck</option>
                                                        <option>NA</option>
                                                    </select>
                                                    </div>

                                                    <div class="form-group col-md-3">
                                                    <label for="dob">Date of Bite</label>
                                                    <input type="date" class="form-control" id="dob" name="dob">
                                                    <small class="form-text text-success">Put NA if not Applicable</small>
                                                    </div>

                                                    <div class="form-group col-md-3">
                                                    <label for="pob">Place of Bite</label>
                                                    <input type="text" class="form-control" id="pob" name="pob">
                                                    <small class="form-text text-success">Put NA if not Applicable</small>
                                                    </div>

                                                    <div class="form-group col-md-3">
                                                        <label for="wound_wash">Wound Wash</label>
                                                        <select class="custom-select" id="wound_wash" name="wound_wash" required>
                                                            <option selected disabled value="">Choose...</option>
                                                            <option>Yes</option>
                                                            <option>No</option>
                                                            <option>NA</option>
                                                        </select>
                                                    </div>

                                                    <div class="form-group col-md-3">
                                                        <label for="tandok">Tandok</label>
                                                        <select class="custom-select" id="tandok" name="tandok" required>
                                                            <option selected disabled value="">Choose...</option>
                                                            <option>Yes</option>
                                                            <option>No</option>
                                                            <option>NA</option>
                                                        </select>
                                                    </div>

                                                    <div class="form-group col-md-3">
                                                        <label for="animal_class">Animal Classification</label>
                                                        <select class="custom-select" id="animal_class" name="animal_class" required>
                                                            <option selected disabled value="">Choose...</option>
                                                            <option>Pet</option>
                                                            <option>Stray</option>
                                                            <option>NA</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-row">
                                                    <div class="form-group col-md-3">
                                                        <label for="pcec">PCEC</label>
                                                        <input type="text" class="form-control" id="pcec" name="pcec">
                                                        <small class="form-text text-success">Put NA if not Applicable</small>
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label for="pvrv">PVRV</label>
                                                        <input type="text" class="form-control" id="pvrv" name="pvrv">
                                                        <small class="form-text text-success">Put NA if not Applicable</small>
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label for="erig">ERIG</label>
                                                        <input type="text" class="form-control" id="erig" name="erig">
                                                        <small class="form-text text-success">Put NA if not Applicable</small>
                                                    </div>
                                                </div>

                                                <div class="form-row">
                                                    <div class="form-group col-md-3">
                                                        <label for="d1">Day 0</label>
                                                        <input type="date" class="form-control" id="d1" name="d1">
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label for="d3">Day 3</label>
                                                        <input type="date" class="form-control" id="d3" name="d3">
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label for="d7">Day 7</label>
                                                        <input type="date" class="form-control" id="d7" name="d7">
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label for="d2030">Day 28/30</label>
                                                        <input type="date" class="form-control" id="d2030" name="d2030">
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label for="weight">Weight</label>
                                                        <input type="text" class="form-control" id="weight" name="weight">
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label for="bp">Blood Pressure</label>
                                                        <input type="text" class="form-control" id="bp" name="bp">
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label for="pr">Pulse Rate</label>
                                                        <input type="text" class="form-control" id="pr" name="pr">
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label for="rr">Respiration Rate</label>
                                                        <input type="text" class="form-control" id="rr" name="rr">
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label for="temp">Body Temperature</label>
                                                        <input type="text" class="form-control" id="temp" name="temp">
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label for="ats">ATS</label>
                                                        <select class="custom-select" id="ats" name="ats" required>
                                                            <option selected disabled value="">Choose...</option>
                                                            <option>1500</option>
                                                            <option>3000</option>
                                                            <option>4500</option>
                                                            <option>6000</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="que_id" id="que_id" value="">
                                                <input type="hidden" name="finding_id" id="finding_id" value="">
                                                <input type="hidden" name="patientid" id="patientid" value="">
                                                <button type="submit" class="btn btn-success float-right" name="submit">Proceed</button>
                                            </form>
                                            <!-- end of form -->
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
             <!-- end of modal -->

        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- End of Main Content -->

<?php 
include 'includes/scripts.php';
include 'includes/footer.php';
?>

<script>
$(document).ready(function () {
    
    $(document).on('click', '.consult-btn', function () {
       
        var patientId = $(this).data('patientid'); 
        var findingsId = $(this).data('findingid');
        var queId = $(this).data('queid');

        clearModalFields();

        $.ajax({
            url: 'actions/fetch_patient_data_record.php',
            method: 'POST',
            data: { patient_id: patientId }, 
            success: function (response) {
                try {
                    
                    var data = JSON.parse(response);

                    if (data.error) {
                        console.error(data.error);
                        return;
                    }

                    if (!data || $.isEmptyObject(data)) {
                        console.error('No data found for patient ID:', patientId);
                        return;
                    }

                    $('#firstname').val(data.firstname || '');
                    $('#midname').val(data.midname || '');
                    $('#lastname').val(data.lastname || '');
                    $('#sufix').val(data.sufix || '');
                    $('#date_of_birth').val(data.date_of_birth || '');
                    $('#age').val(data.age || '');
                    $('#place_of_birth').val(data.place_of_birth || '');
                    $('#gender').val(data.gender || '');
                    $('#cpnumber').val(data.cpnumber || '');
                    $('#email').val(data.email || '');
                    $('#civil_status').val(data.civil_status || '');
                    $('#guardian_name').val(data.guardian_name || '');
                    $('#nationality').val(data.nationality || '');
                    $('#religion').val(data.religion || '');
                    $('#occupation').val(data.occupation || '');
                    $('#barangay').val(data.barangay || '');
                    $('#municipal').val(data.municipal || '');
                    $('#province').val(data.province || '');
                    $('#region').val(data.region || '');

                    $('#exposure').val(data.exposure || '');
                    $('#dose').val(data.dose || '');
                    $('#animal_type').val(data.animal_type || '');
                    $('#category').val(data.category || '');
                    $('#vaccine_type').val(data.vaccine_type || '');
                    $('#wound_type').val(data.wound_type || '');
                    $('#sob').val(data.sob || '');
                    $('#dob').val(data.dob || '');
                    $('#pob').val(data.pob || '');
                    $('#wound_wash').val(data.wound_wash || '');
                    $('#tandok').val(data.tandok || '');
                    $('#animal_class').val(data.animal_class || '');
                    $('#pcec').val(data.pcec || '');
                    $('#pvrv').val(data.pvrv || '');
                    $('#erig').val(data.erig || '');
                    $('#d1').val(data.d1 || '');
                    $('#d3').val(data.d3 || '');
                    $('#d7').val(data.d7 || '');
                    $('#d2030').val(data.d2030 || '');
                    $('#weight').val(data.weight || '');
                    $('#bp').val(data.bp || '');
                    $('#pr').val(data.pr || '');
                    $('#rr').val(data.rr || '');
                    $('#temp').val(data.temp || '');
                    $('#ats').val(data.ats || '');

                    $('#que_id').val(queId);
                    $('#finding_id').val(findingsId);
                    $('#patientid').val(patientId);

                } catch (e) {
                    console.error('Error parsing JSON response:', e);
                    console.error('Response:', response);  
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error('Error fetching patient data:', textStatus, errorThrown);
            }
        });
    });


    function clearModalFields() {
        $('#firstname, #midname, #lastname, #sufix, #date_of_birth, #age, #place_of_birth, #gender, #cpnumber, #email, #civil_status, #guardian_name, #nationality, #religion, #occupation, #barangay, #municipal, #province, #region').val('');
        $('#exposure,#dose, #animal_type, #category, #vaccine_type, #wound_type, #sob, #dob, #pob, #wound_wash, #tandok, #animal_class, #pcec, #pvrv, #erig, #d1, #d3, #d7, #d2030, #weight, #bp, #pr, #rr, #temp, #ats').val('');
        $('#que_id, #finding_id, #patientid').val('');
    }
});

</script>

<script>
$(document).ready(function() {
    
    $('#patientForm').on('submit', function(event) {
        event.preventDefault(); 

        $.ajax({
            url: $(this).attr('action'), 
            type: $(this).attr('method'), 
            data: $(this).serialize(), 
            success: function(response) {
              
                Swal.fire({
                    title: 'Success!',
                    text: 'Patient information has been saved successfully.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        
                        window.location.href = 'patient-record.php'; 
                    }
                });
            },
            error: function() {
                
                Swal.fire({
                    title: 'Error!',
                    text: 'An error occurred while saving the patient information. Please try again.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
    });
});
</script>

<script>
   $(document).on('click', '.delete-appointment', function () {
    var patientId = $(this).data('patientid'); 

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
            
            $.ajax({
                url: 'actions/delete-appointment.php', 
                type: 'GET',
                data: { patientid: patientId }, 
                success: function(response) {
                    Swal.fire(
                        'Deleted!',
                        'The appointment has been deleted.',
                        'success'
                    ).then(() => {
                       
                        location.reload();
                    });
                },
                error: function(xhr, status, error) {
                    Swal.fire(
                        'Error!',
                        'An error occurred while deleting the appointment.',
                        'error'
                    );
                }
            });
        }
    });
});
</script>

<script>
$(document).on('click', 'button[name="print"]', function() {
    // Get data attributes from the button
    var patientId = $(this).data('patientid');
    var findingId = $(this).data('findingid');
    var queId = $(this).data('queid');
    
    // Send an AJAX request to generate the PDF
    $.ajax({
        url: 'pdf-gen/generate_pdf.php', // PHP script to handle PDF generation
        type: 'POST',
        data: {
            patientId: patientId,
            findingId: findingId,
            queId: queId
        },
        xhrFields: {
            responseType: 'blob' // Expect binary data in response
        },
        success: function(response) {
            // On success, handle the PDF download
            var blob = new Blob([response], { type: 'application/pdf' });
            var link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = "Patient_Report.pdf";
            link.click();
        },
        error: function(xhr, status, error) {
            console.log('Error: ' + error);
        }
    });
});
</script>
<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
});
</script>


