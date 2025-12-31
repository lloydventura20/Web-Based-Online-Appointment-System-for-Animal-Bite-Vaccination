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
                <h1 class="h3 mb-0 text-gray-800">Appointment</h1>
            </div>

            <div class="row mb-3 justify-content-end">
                <div class="col-md-12 d-flex justify-content-end align-items-center">
                    <div class="mr-3">
                        <label for="dayFilter" class="d-inline-block">Filter by Day:</label>
                        <select id="dayFilter" class="form-control d-inline-block">
                            <option value="">All Days</option>
                            <option value="2">Tuesday</option>
                            <option value="5">Friday</option>
                        </select>
                    </div>
                    
                </div>
            </div>
    <!-- DataTables Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Appointment Table</h6>
                    <div class="m1-auto">
                    <button class="btn btn-success btn-sm mx-2" id="approveAll">Approve All</button>
                    <button class="btn btn-danger btn-sm mx-2" id="deleteAll">Delete All</button>
                    </div>
                    
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
                                            a.status = 'pending'"; // Filter only pending appointments

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
                                                <button type='button' name='approve' class='btn btn-warning btn-sm' data-appointmentid='" . $row["appointmentid"] . "'>
                                                    <i class='fas fa-fw fa-right-to-bracket'></i> Approve
                                                </button>
                                                <button type='button' name='delete' class='btn btn-danger btn-sm delete-appointment' data-patientid='" . $row["patientid"] . "'>
                                                    <i class='fas fa-fw fa-trash'></i>
                                                </button>
                                            </td>
                                        </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='10'>No pending appointments found</td></tr>";
                                }

                                // Close the connection
                                $conn->close();
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

<!-- jQuery Script -->
<script>
   $(document).ready(function() {
    $(document).on('click', '.btn-warning[name="approve"]', function() {
        var appointmentId = $(this).data('appointmentid');

        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to approve this appointment?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, approve it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                
                Swal.fire({
                    title: 'Processing...',
                    text: 'Please wait while the appointment is being approved.',
                    icon: 'info',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    timer: 1500,
                    willClose: () => {
                        
                        $.ajax({
                            url: 'actions/approve_appointment.php', 
                            type: 'POST',
                            data: { appointmentid: appointmentId },
                            success: function(response) {
                                if (response.trim() === 'success') {
                                    Swal.fire({
                                        title: 'Success!',
                                        text: 'Appointment approved successfully!',
                                        icon: 'success',
                                        confirmButtonText: 'OK'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            location.reload(); 
                                        }
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Error!',
                                        text: 'Failed to approve appointment. Please try again.',
                                        icon: 'error',
                                        confirmButtonText: 'OK'
                                    });
                                }
                            },
                            error: function() {
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'An error occurred while processing the request.',
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            }
                        });
                    }
                });
            }
        });
    });
});
</script>


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

        // Approve Individual Appointment
        // $(document).on('click', '.btn-warning[name="approve"]', function() {
        //     var appointmentId = $(this).data('appointmentid');
        //     approveAppointment(appointmentId);
        // });

        // Approve All Appointments
        $('#approveAll').on('click', function() {
            var allAppointmentIds = [];
            $('#dataTable tbody tr').each(function() {
                allAppointmentIds.push($(this).find('button[name="approve"]').data('appointmentid'));
            });

            Swal.fire({
                title: 'Are you sure?',
                text: "You want to approve all listed appointments?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, approve all!'
            }).then((result) => {
                if (result.isConfirmed) {
                    approveAllAppointments(allAppointmentIds);
                }
            });
        });

        // Delete Individual Appointment
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

        // Delete All Appointments
        $('#deleteAll').on('click', function() {
            var allPatientIds = [];
            $('#dataTable tbody tr').each(function() {
                allPatientIds.push($(this).find('.delete-appointment').data('patientid'));
            });

            if (allPatientIds.length > 0) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This will delete all displayed appointments permanently!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#ccc',
                    confirmButtonText: 'Yes, delete all!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: 'actions/delete_all_appointments.php',
                            type: 'POST',
                            data: { ids: allPatientIds },
                            success: function(response) {
                                if (response === 'success') {
                                    Swal.fire('Deleted!', 'All appointments have been deleted.', 'success').then(() => location.reload());
                                } else {
                                    Swal.fire('Error!', 'An error occurred, please try again.', 'error');
                                }
                            }
                        });
                    }
                });
            } else {
                Swal.fire('No Selection!', 'There are no appointments selected to delete.', 'info');
            }
        });
    });

    // function approveAppointment(appointmentId) {
    //     // AJAX call to approve an individual appointment
    //     $.ajax({
    //         url: 'actions/approve_appointment.php',
    //         type: 'POST',
    //         data: { appointmentid: appointmentId },
    //         success: function(response) {
    //             if (response.trim() === 'success') {
    //                 Swal.fire('Approved!', 'Appointment has been approved.', 'success').then(() => location.reload());
    //             } else {
    //                 Swal.fire('Error!', 'Failed to approve the appointment. Please try again.', 'error');
    //             }
    //         }
    //     });
    // }

    function approveAllAppointments(appointmentIds) {
        // AJAX call to approve all listed appointments
        $.ajax({
            url: 'actions/approve_all_appointments.php',
            type: 'POST',
            data: { ids: appointmentIds },
            success: function(response) {
                Swal.fire('Approved!', 'All appointments have been approved.', 'success').then(() => location.reload());
            }
        });
    }
</script>




