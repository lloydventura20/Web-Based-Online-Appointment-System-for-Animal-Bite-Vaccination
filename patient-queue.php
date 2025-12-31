<?php 
include 'includes/header.php';
include 'includes/navbar.php';
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
                <h1 class="h3 mb-0 text-gray-800">Patient Queue 1</h1>
            </div>

            <!-- Cards Row -->
            <div class="row">
                <!-- Lane 1 -->
                <div class="col-md-4">
                    <div class="card mb-4 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-center">Lane 1 Age(0-12)</h5>
                            <div id="accordion-lane-1">
                                <!-- Accordion content will be inserted here -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Lane 2 -->
                <div class="col-md-4">
                    <div class="card mb-4 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-center">Lane 2 Age(13-59)</h5>
                            <div id="accordion-lane-2">
                                <!-- Accordion content will be inserted here -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Lane 3 -->
                <div class="col-md-4">
                    <div class="card mb-4 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-center">Lane 3 Age(60 above)</h5>
                            <div id="accordion-lane-3">
                                <!-- Accordion content will be inserted here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Cards Row -->

            <!-- New Card Row -->
            <!-- <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-center">Preparing Patient Queue</h5>
                            <div id="accordion-additional-lane">
                                Additional lane content can be added here
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
            <!-- End of New Card Row -->

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

    $.ajax({
        url: 'actions/que.php', 
        method: 'GET',
        dataType: 'json',
        success: function(data) {
          
            function createAccordion(accordionId, groupName, patients) {
                const maxPatientsPerGroup = 10;
                let accordionHtml = '';
                
                for (let i = 0; i < patients.length; i += maxPatientsPerGroup) {
                    const chunk = patients.slice(i, i + maxPatientsPerGroup);
                    const groupNumber = Math.floor(i / maxPatientsPerGroup) + 1;
                    const collapseId = `${accordionId}-collapse-${groupNumber}`; 
                    const headingId = `${accordionId}-heading-${groupNumber}`; 
                    
                    let content = '';
                    chunk.forEach((patient, index) => {
                        content += `<p>${index + 1}. #${patient.patientid} ${patient.firstname} ${patient.lastname} (${patient.age} years old)</p>`;
                    });

              
                    const patientIds = chunk.map(patient => patient.patientid).join(',');

                    accordionHtml += `
                    <div class="card">
                        <div class="card-header bg-info" id="${headingId}">
                            <h2 class="mb-0 d-flex justify-content-between align-items-center">
                                <button class="btn btn-link text-light" type="button" data-toggle="collapse" data-target="#${collapseId}" aria-expanded="true" aria-controls="${collapseId}">
                                     Batch ${groupNumber}
                                </button>
                                <button class="btn btn-warning btn-sm" onclick="updateStatus('${patientIds}')">Go</button>
                            </h2>
                        </div>
                        <div id="${collapseId}" class="collapse" aria-labelledby="${headingId}" data-parent="#accordion-${accordionId}">
                            <div class="card-body">
                                ${content}
                            </div>
                        </div>
                    </div><br>`;
                }

              
                $(`#accordion-${accordionId}`).html(accordionHtml);
                
                $('.collapse').collapse();
            }

            $('#accordion-lane-1').empty();
            $('#accordion-lane-2').empty();
            $('#accordion-lane-3').empty();
            
        
            createAccordion('lane-1', 'Lane 1 (Age 0-12)', data['0-12']);
            createAccordion('lane-2', 'Lane 2 (Age 13-59)', data['13-59']);
            createAccordion('lane-3', 'Lane 3 (Age 60 above)', data['60-above']);
        }
    });
});


function updateStatus(patientIds) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to update the status of these patients?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, update it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'actions/patient-que.php',
                method: 'POST',
                data: { patient_ids: patientIds }, 
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Status Updated',
                            text: 'The status has been updated successfully.',
                            confirmButtonText: 'OK'
                        }).then(() => {
                           
                            updateLiveMonitor(patientIds);
                           
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Update Failed',
                            text: 'Failed to update status: ' + response.message,
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Request Failed',
                        text: 'Failed to update status. Please try again.',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }
    });
}

function updateLiveMonitor(patientIds) {
    $.ajax({
        url: 'actions/update_status.php',
        method: 'POST',
        data: { patient_ids: patientIds },
        success: function() {
            console.log('Live monitor updated successfully.');
        },
        error: function() {
            console.error('Error updating live monitor.');
        }
    });
}


</script>


