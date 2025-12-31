
<?php 
include 'includes/header.php';
include 'includes/navbar.php';
include 'db/connection.php';

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
                        <h1 class="h3 mb-0 text-gray-800">Patient Assessment</h1>
                        <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
                    </div>
                         <!-- Day Filter -->
            <div class="row mb-3">
                <div class="col-md-10"></div>
                <div class="col-md-2">
                    <label for="dayFilter">Filter by Day:</label>
                    <select id="dayFilter" class="form-control">
                        <option value="">All Days</option>
                        <option value="2">Tuesday</option>
                        <option value="5">Friday</option>
                    </select>
                </div>
            </div>

                                <!-- DataTables Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Patient Assessment Table</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Age</th>
                                    <th>Municipal</th>
                                    <th>Barangay</th>
                                    <th>Email</th>
                                    <th>Cellphone Number</th>
                                    <th>Appointment Day</th>
                                    <th>Appointment Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Name</th>
                                    <th>Age</th>
                                    <th>Municipal</th>
                                    <th>Barangay</th>
                                    <th>Email</th>
                                    <th>Cellphone Number</th>
                                    <th>Appointment Day</th>
                                    <th>Appointment Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                <?php
                                // Fetch data from the database using JOIN, and only select pending appointments
                                $sql = "SELECT 
                                            a.appointmentid,
                                            p.patientid,  
                                            p.firstname,
                                            p.midname,
                                            p.lastname,
                                            p.age,
                                            p.municipal,
                                            p.barangay,
                                            p.email,
                                            p.cpnumber,
                                            a.appointment_day,
                                            a.appointment_date,
                                            a.status
                                        FROM 
                                            appointment a
                                        INNER JOIN 
                                            patients p ON a.patientid = p.patientid
                                        WHERE 
                                            a.status = 'approved'"; 

                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    while($row = $result->fetch_assoc()) {
                                        $fullName = $row["firstname"] . " " . $row["midname"] . " " . $row["lastname"];
                                        echo "<tr>
                                            <td>" . $fullName . "</td>
                                            <td>" . $row["age"] . "</td>
                                            <td>" . $row["municipal"] . "</td>
                                            <td>" . $row["barangay"] . "</td>
                                            <td>" . $row["email"] . "</td>
                                            <td>" . $row["cpnumber"] . "</td>
                                            <td class='appointment-day'>" . $row["appointment_day"] . "</td>
                                            <td class='appointment-date'>" . $row["appointment_date"] . "</td>
                                            <td class='appointment-status'>" . ucfirst($row["status"]) . "</td>
                                            <td>
                                                <button type='button' name='patientid' class='btn btn-success btn-sm consult-btn' data-toggle='modal' data-target='#consultationModal' data-patientid='" . $row["patientid"] . "' data-appointmentid='" . $row["appointmentid"] . "'>
                                                    <i class='fas fa-fw fa-right-to-bracket'></i> Consult
                                                </button>
                                                <button type='button' name='delete' class='btn btn-danger btn-sm delete-appointment' data-patientid='" . $row["patientid"] . "'>
                                                    <i class='fas fa-fw fa-trash'></i>
                                                </button>
                                            </td>
                                        </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='10'>No Patient found</td></tr>";
                                }

                                
                                $conn->close();
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


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
                         <form id="patientForm" action="actions/save_patient.php" method="POST">
                         <h5 class="card-title">Personal Information</h5>
                         <hr class="sidebar-divider">
                                <div class="form-row">
                                
                                    <div class="form-group col-md-3">
                                    <label for="firstname">First Name</label>
                                    <input type="text" class="form-control" id="firstname" name="firstname" readonly>
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
                                    <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" required>
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
                                    <label for="guardian">Guardian Name</label>
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

                                    <!-- <div class="form-group col-md-3">
                                    <label for="sob">Sight of Bite</label>
                                    <input type="text" class="form-control" id="sob" name="sob">
                                    </div> -->

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
                                        <label for="d20/30">Day 28/30</label>
                                        <input type="date" class="form-control" id="d20/30" name="d20/30">
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
                                <input type="hidden" name="appointment_id" id="appointment_id" value="">
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

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->
<?php 
include 'includes/scripts.php';
include 'includes/footer.php';

?>

 <script>
    $(document).ready(function() {
        var table = $('#dataTable').DataTable();

        function updateAppointmentDays() {
            var daysOfWeek = ["", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];
            $('.appointment-day').each(function() {
                var dayNumber = parseInt($(this).text());
                if (dayNumber === 2 || dayNumber === 5) {
                    var dayName = daysOfWeek[dayNumber];
                    $(this).text(dayName);
                }
            });
        }

        updateAppointmentDays();

        table.on('draw', function() {
            updateAppointmentDays();
        });

        $('#dayFilter').on('change', function() {
            var selectedDay = $(this).val();
            if (selectedDay) {
                table.column(6).search('^' + selectedDay + '$', true, false).draw();
            } else {
                table.column(6).search('').draw();
            }
        });

        $(document).on('click', '.delete-appointment', function(e) {
            e.preventDefault(); 
            var patientid = $(this).data('patientid'); 

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
                        data: { patientid: patientid },
                        success: function(response) {
                            var res = JSON.parse(response);

                            if (res.status === 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: res.message,
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                   
                                    table.row($(e.target).closest('tr')).remove().draw();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: res.message,
                                    confirmButtonText: 'OK'
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Error deleting patient. Please try again.',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });
        });
    });
</script>

<script>
$(document).ready(function () {

    // Function to add days to a given date
    function addDays(date, days) {
        var result = new Date(date);
        result.setDate(result.getDate() + days);
        return result.toISOString().split('T')[0]; // Convert to 'YYYY-MM-DD' format
    }

    // Event delegation to handle click on dynamically loaded elements
    $(document).on('click', '.consult-btn', function () {
        var patientId = $(this).data('patientid'); 
        var appointmentId = $(this).data('appointmentid'); 

        // Perform the AJAX request to fetch patient data
        $.ajax({
            url: 'actions/fetch_patient_data.php',
            method: 'POST',
            data: { patient_id: patientId }, 
            success: function (response) {
                try {
                    // Parse the response as JSON
                    var patientData = JSON.parse(response);

                    // Populate patient data into the modal
                    $('#firstname').val(patientData.firstname || '');
                    $('#midname').val(patientData.midname || '');
                    $('#lastname').val(patientData.lastname || '');
                    $('#sufix').val(patientData.sufix || '');
                    $('#date_of_birth').val(patientData.date_of_birth || '');
                    $('#age').val(patientData.age || '');
                    $('#place_of_birth').val(patientData.place_of_birth || '');
                    $('#gender').val(patientData.gender || '');
                    $('#cpnumber').val(patientData.cpnumber || '');
                    $('#email').val(patientData.email || '');
                    $('#civil_status').val(patientData.civil_status || '');
                    $('#guardian_name').val(patientData.guardian_name || '');
                    $('#nationality').val(patientData.nationality || '');
                    $('#religion').val(patientData.religion || '');
                    $('#occupation').val(patientData.occupation || '');
                    $('#barangay').val(patientData.barangay || '');
                    $('#municipal').val(patientData.municipal || '');
                    $('#province').val(patientData.province || '');
                    $('#region').val(patientData.region || '');

                    $('#d1').val(patientData.appointment_date || '');

                    // Calculate and set d3, d7, and d20_30 dates based on d1
                    var d1Date = patientData.appointment_date;
                    if (d1Date) {
                        var d1DayOfWeek = new Date(d1Date).getDay(); // Get day of the week (0 = Sunday, 1 = Monday, ..., 5 = Friday, 6 = Saturday)
                        var d3Date;

                        if (d1DayOfWeek === 5) {
                            // If d1 is a Friday, add 4 days
                            d3Date = addDays(d1Date, 4);
                        } else if (d1DayOfWeek === 2) {
                            // If d1 is a Tuesday, add 3 days
                            d3Date = addDays(d1Date, 3);
                        } else {
                            // Otherwise, just add 3 days
                            d3Date = addDays(d1Date, 3);
                        }
                        $('#d3').val(d3Date);

                        // Add 7 days to d3 for d7
                        var d7Date = addDays(d3Date, 7);
                        $('#d7').val(d7Date);

                        // Add 21 days to d7 for d20/30
                        var d20_30Date = addDays(d7Date, 21);
                        $('#d20\\/30').val(d20_30Date);
                    }

                    // Store appointment and patient IDs
                    $('#appointment_id').val(appointmentId);
                    $('#patientid').val(patientId);
                } catch (e) {
                    console.error('Error parsing JSON response:', e);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error('Error fetching patient data:', textStatus, errorThrown);
            }
        });
    });
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
                        
                        window.location.href = 'consult.php'; 
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










