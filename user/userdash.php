<?php 
include 'includes/header.php';
include 'includes/navbar.php';
include '../db/connection.php';

// Get the logged-in patient's id from the session
$logged_in_patient_id = $_SESSION['patientid'];

// Fetch patient data along with findings and appointments for the logged-in patient
$query = "SELECT p.patientid, p.firstname, p.midname, p.lastname, p.sufix, p.date_of_birth, p.age, p.gender, p.cpnumber, p.email,p.barangay,p.municipal,p.province,
                 f.exposure,f.dose, f.animal_type, f.category, f.vaccine_type, f.wound_type, f.sob, f.dob as finding_dob,
                 f.pob, f.wound_wash, f.tandok, f.animal_class, f.pcec, f.pvrv, f.erig, f.d1, f.d3, f.d7, f.d2030, 
                 f.weight, f.bp, f.pr, f.rr, f.temp, f.ats,
                 a.appointment_day, a.appointment_date, a.status
          FROM patients p
          LEFT JOIN findings f ON p.patientid = f.patientid
          LEFT JOIN appointment a ON p.patientid = a.patientid
          WHERE p.patientid = '$logged_in_patient_id'";

$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

// Function to map the dose to Bootstrap color class
function getDoseClass($dose) {
    switch ($dose) {
        case '1st Dose':
            return 'text-warning';
        case '2nd Dose':
            return 'text-primary';
        case '3rd Dose':
            return 'text-success';
        default:
            return '';
    }
}

function getDayName($dayNumber) {
    $days = [
        1 => "Monday",
        2 => "Tuesday",
        3 => "Wednesday",
        4 => "Thursday",
        5 => "Friday",
        6 => "Saturday",
        7 => "Sunday"
    ];

    return isset($days[$dayNumber]) ? $days[$dayNumber] : "Unknown Day";
}

function getStatusClass($status) {
    switch($status) {
        case 'pending':
            return 'text-danger';
        case 'approved':
            return 'text-warning';
        case 'done':
            return 'text-success';
        default:
            return '';
    }
}
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
                <h1 class="h3 mb-0 text-gray-800">User Profile</h1>
            </div>

            <!-- Content Row -->
            <div class="row">
                <!-- Left Side: Patient Details and Appointment (in a column) -->
                <div class="col-lg-8">
                    <!-- Patient Details Section -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Patient Details</h6>
                        </div>
                        <div class="card-body">
                            <?php if ($row) { ?>
                            <div class="table-responsive">
                                <table class="table table-bordered" width="100%" cellspacing="0">
                                    <tbody>
                                        <tr>
                                            <th>Patient Number</th>
                                            <td><?php echo $row['patientid']; ?></td>
                                        </tr>
                                        <tr>
                                            <th>First Name</th>
                                            <td><?php echo $row['firstname']; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Middle Name</th>
                                            <td><?php echo $row['midname']; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Last Name</th>
                                            <td><?php echo $row['lastname']; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Sufix</th>
                                            <td><?php echo $row['sufix']; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Date of Birth</th>
                                            <td><?php echo $row['date_of_birth']; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Age</th>
                                            <td><?php echo $row['age']; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Gender</th>
                                            <td><?php echo $row['gender']; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Phone Number</th>
                                            <td><?php echo $row['cpnumber']; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Email</th>
                                            <td><?php echo $row['email']; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Address</th>
                                            <td><?php echo $row['barangay'].', '. $row['municipal'] .', '. $row['province']; ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <?php } else { ?>
                                <p>No patient details found.</p>
                            <?php } ?>
                        </div>
                    </div>

                    <!-- Appointments Section -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Appointments</h6>
                        </div>
                        <div class="card-body">
                            <?php if ($row['appointment_day']) { ?>
                            <div class="table-responsive">
                                <table class="table table-bordered" width="100%" cellspacing="0">
                                    <tbody>
                                        <tr>
                                            <th>Appointment Day</th>
                                            <td><?php echo getDayName($row['appointment_day']); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Appointment Date</th>
                                            <td><?php echo $row['appointment_date']; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                            <td class="<?php echo getStatusClass($row['status']); ?>">
                                                <?php echo ucfirst($row['status']); ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <?php } else { ?>
                                <p>No appointments found.</p>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <!-- Right Side: Findings -->
                <div class="col-lg-4">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Patient Findings</h6>
                        </div>
                        <div class="card-body">
                            <?php if ($row['dose']) { ?>
                            <div class="table-responsive">
                                <table class="table table-bordered" width="100%" cellspacing="0">
                                    <tbody>
                                        <tr>
                                            <th>Exposure</th>
                                            <td><?php echo $row['exposure']; ?></td>
                                        </tr>
                                        <tr>
                                            <th class="<?php echo getDoseClass($row['dose']); ?>">Dose</th>
                                            <td class="<?php echo getDoseClass($row['dose']); ?>"><?php echo $row['dose']; ?></td>
                                        </tr>
                                        <tr class="text-warning">
                                            <th>Day 0</th>
                                            <td><?php echo $row['d1']; ?></td>
                                        </tr>
                                        <tr class="text-primary">
                                            <th>Day 3</th>
                                            <td><?php echo $row['d3']; ?></td>
                                        </tr>
                                        <tr class="text-success">
                                            <th>Day 7</th>
                                            <td><?php echo $row['d7']; ?></td>
                                        </tr>
                                        <tr class="text-info">
                                            <th>Day 20/30</th>
                                            <td><?php echo $row['d2030']; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Animal Type</th>
                                            <td><?php echo $row['animal_type']; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Category</th>
                                            <td><?php echo $row['category']; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Vaccine Type</th>
                                            <td><?php echo $row['vaccine_type']; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Wound Type</th>
                                            <td><?php echo $row['wound_type']; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Sight of Bite</th>
                                            <td><?php echo $row['sob']; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Date of Bite</th>
                                            <td><?php echo $row['finding_dob']; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Place of Bite</th>
                                            <td><?php echo $row['pob']; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Wound Wash</th>
                                            <td><?php echo $row['wound_wash']; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Tandok</th>
                                            <td><?php echo $row['tandok']; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Animal Class</th>
                                            <td><?php echo $row['animal_class']; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Weight</th>
                                            <td><?php echo $row['weight']; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Blood Pressure</th>
                                            <td><?php echo $row['bp']; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Pulse Rate</th>
                                            <td><?php echo $row['pr']; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Respiratory Rate</th>
                                            <td><?php echo $row['rr']; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Temperature</th>
                                            <td><?php echo $row['temp']; ?></td>
                                        </tr>
                                        <tr>
                                            <th>ATS</th>
                                            <td><?php echo $row['ats']; ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <?php } else { ?>
                                <p>No findings found.</p>
                            <?php } ?>
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
