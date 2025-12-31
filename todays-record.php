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
                <h1 class="h3 mb-0 text-gray-800">Today's Record</h1>
                <a href="patient-record.php" class="d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-user fa-sm text-white-50"></i> Patient record</a>
            </div>

            <!-- DataTales Example -->
            <div class="card shadow mb-4">
            <!--  -->
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">Patient Record Table</h6>
                            
                            <!-- Button Container -->
                            <div class="ml-auto">
                                <!-- Generate Report Button -->
                                <a href="pdf-gen/generate_pdf_today.php" class="btn btn-sm btn-danger shadow-sm">
                                    <i class="fas fa-download fa-sm text-white-50"></i> Generate PDF
                                </a>
                                <a href="excel-gen/generate_excel_today.php" class="btn btn-sm btn-success shadow-sm ml-2">
                                    <i class="fas fa-download fa-sm text-white-50"></i> Generate Excel
                                </a>
                            </div>
                        </div>
            <!--  -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Age</th>
                                    <th>Address</th>
                                    <th>Email</th>
                                    <th>Cellphone Number</th>
                                    <th>Exposure</th>
                                    <th>Dose</th>
                                    <th>Patient Create</th>
                                    <th>Patient Update</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Name</th>
                                    <th>Age</th>
                                    <th>Address</th>
                                    <th>Email</th>
                                    <th>Cellphone Number</th>
                                    <th>Exposure</th>
                                    <th>Dose</th>
                                    <th>Patient Create</th>
                                    <th>Patient Update</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                            <tbody>
                            <?php
                                // Fetch patient records for today only
                                $sql = "SELECT 
                                            p.patientid, p.firstname, p.midname, p.lastname, p.age, p.email, p.cpnumber, p.barangay, p.municipal, p.province, 
                                            f.findingid, f.exposure,f.dose, 
                                            pq.queid, pq.created_at, pq.updated_at -- Include created_at and updated_at from patient_que
                                        FROM patients p
                                        LEFT JOIN findings f ON p.patientid = f.patientid
                                        LEFT JOIN patient_que pq ON p.patientid = pq.patientid
                                        WHERE pq.status IN ('0', '1', '2','3') 
                                        AND (DATE(pq.created_at) = CURDATE() OR DATE(pq.updated_at) = CURDATE())"; // Filter for today's records

                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    // Output data of each row
                                    while($row = $result->fetch_assoc()) {
                                        echo "<tr>
                                                <td>{$row['firstname']} {$row['midname']} {$row['lastname']}</td>
                                                <td>{$row['age']}</td>
                                                <td>{$row['barangay']}, {$row['municipal']}, {$row['province']}</td>
                                                <td>{$row['email']}</td>
                                                <td>{$row['cpnumber']}</td>
                                                <td>{$row['exposure']}</td>
                                                <td>{$row['dose']}</td>
                                                <td>{$row['created_at']}</td> <!-- Display created_at -->
                                                <td>{$row['updated_at']}</td> <!-- Display created_at -->
                                                
                                                <td>
                                                    <button type='button' name='print' class='btn btn-warning btn-sm consult-btn' data-patientid='" . $row["patientid"] . "' data-findingid='" . $row["findingid"] . "' data-queid='". $row["queid"]."'>
                                                    <i class='fas fa-fw fa-download'></i> 
                                                </button>
                                                    <button type='button' name='delete' class='btn btn-danger btn-sm delete-appointment' data-patientid='" . $row["patientid"] . "'>
                                                        <i class='fas fa-fw fa-trash'></i>
                                                    </button>
                                                </td>
                                            </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='9'>No records found</td></tr>"; // Adjust colspan to match the number of table columns
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
        $('#dose, #animal_type, #category, #vaccine_type, #wound_type, #sob, #dob, #pob, #wound_wash, #tandok, #animal_class, #pcec, #pvrv, #erig, #d1, #d3, #d7, #d2030, #weight, #bp, #pr, #rr, #temp, #ats').val('');
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
