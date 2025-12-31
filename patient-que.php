<?php 
include 'includes/header.php';
include 'includes/navbar.php';
include 'db/connection.php'; // Include your database connection file
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
                <h1 class="h3 mb-0 text-gray-800">Patient Queue</h1>
            </div>

            <!-- Single Row with Walk-In and Appointment -->
            <div class="row">
                <!-- Walk-In Section -->
                <div class="col-md-6">
                    <div class="card mb-4 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-center">Walk-In</h5>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <button class="btn btn-primary btn-sm" id="prev-walkin">Previous</button>
                                <span id="count-walkin" class="text-center">1</span>
                                <button class="btn btn-primary btn-sm" id="next-walkin">Next</button>
                            </div>
                            <button class="btn btn-danger btn-sm w-100" id="reset-walkin">Reset</button>
                        </div>
                    </div>
                </div>

                <!-- Appointment Section -->
                <div class="col-md-6">
                    <div class="card mb-4 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-center">Appointment</h5>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <button class="btn btn-primary btn-sm" id="prev-appointment">Previous</button>
                                <span id="count-appointment" class="text-center">1</span>
                                <button class="btn btn-primary btn-sm" id="next-appointment">Next</button>
                            </div>
                            <button class="btn btn-danger btn-sm w-100" id="reset-appointment">Reset</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->

            <!-- Table Section: Status = 0 -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Patient Queue (Preparing)</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Patient Number</th>
                                    <th>Patient Name</th>
                                    <th>Age</th>
                                    <th>Dose</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Fetch data from patient_que table with status = 0
                                $query = "SELECT 
                                pq.queid, 
                                CONCAT(
                                    p.firstname, ' ', 
                                    p.midname, ' ', 
                                    p.lastname, 
                                    CASE 
                                        WHEN p.sufix != 'N/A' THEN CONCAT(' ', p.sufix) 
                                        ELSE '' 
                                    END
                                ) AS patient_name,
                                p.age,
                                p.patientid,
                                f.dose
                              FROM patient_que pq
                              JOIN patients p ON pq.patientid = p.patientid
                              JOIN findings f ON pq.findingsid = f.findingid
                              WHERE pq.status = '0'";
                                $result = mysqli_query($conn, $query);

                                $counter = 1; // Start counter at 1
                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>
                                                <td>{$counter}</td>
                                                <td>{$row['patientid']}</td>
                                                <td>{$row['patient_name']}</td>
                                                <td>{$row['age']}</td>
                                                <td>{$row['dose']}</td>
                                                <td>
                                                    <button class='btn btn-primary btn-sm prepare-btn' data-id='{$row['queid']}'>Prepare</button>
                                                </td>
                                              </tr>";
                                        $counter++; // Increment counter for each row
                                    }
                                } else {
                                    echo "<tr><td colspan='6' class='text-center'>No records found</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /.table -->

            <!-- Table Section: Status = 1 -->
            <div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Patient Queue (Go)</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTableStatus1" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Batch</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query1 = "SELECT 
                        pq.queid, 
                        CONCAT(
                            p.firstname, ' ', 
                            p.midname, ' ', 
                            p.lastname, 
                            CASE 
                                WHEN p.sufix != 'N/A' THEN CONCAT(' ', p.sufix) 
                                ELSE '' 
                            END
                        ) AS patient_name,
                        p.age,
                        p.patientid,
                        f.dose
                      FROM patient_que pq
                      JOIN patients p ON pq.patientid = p.patientid
                      JOIN findings f ON pq.findingsid = f.findingid
                      WHERE pq.status = '1'";
                    $result1 = mysqli_query($conn, $query1);

                    $batchCounter = 0; // Batch counter
                    $patientCounter = 0; // Patient counter within a batch
                    $batchData = []; // Group patients by batch

                    if (mysqli_num_rows($result1) > 0) {
                        while ($row1 = mysqli_fetch_assoc($result1)) {
                            // Start a new batch if max 10 patients is reached
                            if ($patientCounter % 10 === 0) {
                                $batchCounter++;
                            }
                            $batchData[$batchCounter][] = $row1; // Group patient into the batch
                            $patientCounter++;
                        }

                        // Display each batch in a single row with an accordion under the Batch column
                        foreach ($batchData as $batchNumber => $patients) {
                            echo "<tr>
                                    <td>{$batchNumber}</td>
                                    <td>
                                        <div class='accordion' id='accordionBatch{$batchNumber}'>
                                            <div class='card'>
                                                <div class='card-header' id='headingBatch{$batchNumber}'>
                                                    <h2 class='mb-0'>
                                                        <button class='btn btn-link btn-block text-left' type='button' data-toggle='collapse' data-target='#collapseBatch{$batchNumber}' aria-expanded='true' aria-controls='collapseBatch{$batchNumber}'>
                                                            Batch {$batchNumber}
                                                        </button>
                                                    </h2>
                                                </div>
                                                <div id='collapseBatch{$batchNumber}' class='collapse' aria-labelledby='headingBatch{$batchNumber}' data-parent='#accordionBatch{$batchNumber}'>
                                                    <div class='card-body'>
                                                        <ul class='list-group'>";
                            foreach ($patients as $patientIndex => $patient) {
                                echo "<li class='list-group-item d-flex justify-content-between align-items-center'>
                                        #{$patient['patientid']} {$patient['patient_name']} ({$patient['dose']})
                                        <button class='btn btn-danger btn-sm remove-btn' data-id='{$patient['queid']}'>Remove</button>
                                      </li>";
                            }
                            echo "                          </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><button class='btn btn-success btn-lg go-btn' data-batch='{$batchNumber}'>Go</button></td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3' class='text-center'>No records found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- /.table -->


 <!-- Table Section: Status = 3 -->
 <div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-success">Patient Queue (Done)</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTableDone" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Patient Number</th>
                        <th>Patient Name</th>
                        <th>Age</th>
                        <th>Dose</th>
                        <th>Date Created</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch data from the report table and related patient details
                    $query = "
                        SELECT 
                            r.reportid,
                            r.patientid,
                            CONCAT(
                                p.firstname, ' ', 
                                p.midname, ' ', 
                                p.lastname, 
                                CASE 
                                    WHEN p.sufix != 'N/A' THEN CONCAT(' ', p.sufix) 
                                    ELSE '' 
                                END
                            ) AS patient_name,
                            p.age,
                            r.dose,
                            r.created_at
                        FROM report r
                        JOIN patients p ON r.patientid = p.patientid
                    ";
                    $result = mysqli_query($conn, $query);

                    $counter = 1; // Start counter at 1
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>
                                    <td>{$counter}</td>
                                    <td>{$row['patientid']}</td>
                                    <td>{$row['patient_name']}</td>
                                    <td>{$row['age']}</td>
                                    <td>{$row['dose']}</td>
                                    <td>{$row['created_at']}</td>
                                  </tr>";
                            $counter++; // Increment counter for each row
                        }
                    } else {
                        echo "<tr><td colspan='6' class='text-center'>No records found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

            <!-- /.table -->


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
        $('#dataTableDone').DataTable({
            "paging": true,          // Enable pagination
            "lengthChange": true,    // Allow changing the number of rows per page
            "searching": true,       // Enable the search bar
            "ordering": true,        // Enable column sorting
            "info": true,            // Display table information
            "autoWidth": false,      // Disable auto-width adjustment
            "responsive": true       // Make the table responsive
        });
    });
</script>



<script>
    // Initialize the counters from localStorage or default to 1
    function initializeCounters() {
        const walkinCount = parseInt(localStorage.getItem('count-walkin')) || 1;
        const appointmentCount = parseInt(localStorage.getItem('count-appointment')) || 1;

        $('#count-walkin').text(walkinCount);
        $('#count-appointment').text(appointmentCount);
    }

    // Update the queue count and persist to localStorage
    function updateQueue(queueId, increment) {
        const countElement = $(`#count-${queueId}`);
        let currentCount = parseInt(countElement.text()); // Get the current count as a number

        // Ensure the parsed value is valid
        if (isNaN(currentCount)) {
            currentCount = 1;
        }

        // Update count and wrap within 1-200 range
        currentCount += increment;
        if (currentCount > 200) {
            currentCount = 1; // Reset to 1 if it exceeds 200
        } else if (currentCount < 1) {
            currentCount = 200; // Reset to 200 if it goes below 1
        }

        countElement.text(currentCount);

        // Save the updated count to localStorage
        localStorage.setItem(`count-${queueId}`, currentCount);
    }

    // Reset the queue count and persist to localStorage
    function resetQueue(queueId) {
        const countElement = $(`#count-${queueId}`);
        countElement.text(1); // Reset to 1
        localStorage.setItem(`count-${queueId}`, 1); // Save to localStorage
    }

    // Initialize counters on page load
    $(document).ready(function () {
        initializeCounters();

        // Attach click handlers for Walk-In buttons
        $('#prev-walkin').on('click', function () {
            updateQueue('walkin', -1);
        });

        $('#next-walkin').on('click', function () {
            updateQueue('walkin', 1);
        });

        $('#reset-walkin').on('click', function () {
            resetQueue('walkin');
        });

        // Attach click handlers for Appointment buttons
        $('#prev-appointment').on('click', function () {
            updateQueue('appointment', -1);
        });

        $('#next-appointment').on('click', function () {
            updateQueue('appointment', 1);
        });

        $('#reset-appointment').on('click', function () {
            resetQueue('appointment');
        });
    });
</script>

<script>
$(document).on('click', '.prepare-btn', function () {
    const queId = $(this).data('id'); // Get Queue ID from data-id attribute

    // SweetAlert Confirmation
    Swal.fire({
        title: 'Are you sure?',
        text: 'You are about to prepare this patient.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, prepare!'
    }).then((result) => {
        if (result.isConfirmed) {
            // AJAX Request to Update Status
            $.ajax({
                url: 'actions/update_queue_status.php', // PHP file to handle the update
                method: 'POST',
                data: { queid: queId }, // Send Queue ID and status = 1 to the server
                success: function (response) {
                    if (response.trim() === 'success') { // Trim to handle any whitespace in the response
                        Swal.fire(
                            'Prepared!',
                            'The patient has been prepared successfully.',
                            'success'
                        ).then(() => {
                            location.reload(); // Reload the page to reflect changes
                        });
                    } else {
                        Swal.fire(
                            'Failed!',
                            'Failed to update status. Please try again.',
                            'error'
                        );
                    }
                },
                error: function () {
                    Swal.fire(
                        'Error!',
                        'An error occurred. Please try again.',
                        'error'
                    );
                }
            });
        }
    });
});
</script>

<script>
  $(document).on('click', '.remove-btn', function () {
    const queId = $(this).data('id'); // Get Queue ID from data-id attribute

    // SweetAlert Confirmation
    Swal.fire({
        title: 'Are you sure?',
        text: 'You are about to remove this patient from the batch.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, remove!'
    }).then((result) => {
        if (result.isConfirmed) {
            // AJAX Request to Update Status
            $.ajax({
                url: 'actions/live_update_queue_status.php', // PHP file to handle the update
                method: 'POST',
                data: { queid: queId }, // Send Queue ID to the server
                success: function (response) {
                    if (response.trim() === 'success') { // Trim to handle any whitespace in the response
                        Swal.fire(
                            'Removed!',
                            'Patient has been removed successfully.',
                            'success'
                        ).then(() => {
                            location.reload(); // Reload the page to reflect changes
                        });
                    } else {
                        Swal.fire(
                            'Failed!',
                            'Failed to remove patient. Please try again.',
                            'error'
                        );
                    }
                },
                error: function () {
                    Swal.fire(
                        'Error!',
                        'An error occurred. Please try again.',
                        'error'
                    );
                }
            });
        }
    });
  });
</script>

<script>
$(document).on('click', '.go-btn', function () {
    const batchNumber = $(this).data('batch'); // Get the batch number from the button's data attribute

    if (!batchNumber) {
        Swal.fire(
            'Error!',
            'Invalid batch number. Please try again.',
            'error'
        );
        return;
    }

    // SweetAlert confirmation dialog
    Swal.fire({
        title: 'Are you sure?',
        text: `You are about to process Batch ${batchNumber}.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Go!'
    }).then((result) => {
        if (result.isConfirmed) {
            // AJAX request to update the batch status
            $.ajax({
                url: 'actions/update_batch_status.php', // PHP file to handle the batch update
                method: 'POST',
                data: { batchNumber: batchNumber },
                success: function (response) {
                    console.log('Server Response:', response); // Debug: Log the server response
                    response = response.trim(); // Ensure no extra whitespace

                    if (response === 'success') {
                        Swal.fire(
                            'Processed!',
                            `Batch ${batchNumber} has been successfully processed.`,
                            'success'
                        ).then(() => {
                            location.reload(); // Reload the page to reflect changes
                        });
                    } else if (response === 'error') {
                        Swal.fire(
                            'Failed!',
                            `Failed to process Batch ${batchNumber}. No matching records found.`,
                            'error'
                        );
                    } else {
                        Swal.fire(
                            'Unexpected Response!',
                            `The server returned an unexpected response: ${response}`,
                            'error'
                        );
                    }
                },
                error: function (xhr, status, error) {
                    console.error('AJAX Error:', error); // Debug: Log the error
                    Swal.fire(
                        'Error!',
                        'An error occurred during the request. Please try again.',
                        'error'
                    );
                }
            });
        }
    });
});
</script>

