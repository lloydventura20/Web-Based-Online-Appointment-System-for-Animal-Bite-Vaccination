<?php 
include 'includes/header.php';
?>

<!-- Hide the Navbar, Sidebar, and Footer with Bootstrap d-none class -->
<style>
    #navbar, #topbar, #sidebar, #footer {
        display: none; /* Hide using CSS */
    }
</style>

<!-- Optional: Include the hidden files -->
<div id="navbar" class="d-none">
    <?php include 'includes/navbar.php'; ?>
</div>
<div id="topbar" class="d-none">
    <?php include 'includes/topbar.php'; ?>
</div>

<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">

    <!-- Main Content -->
    <div id="content">

        <!-- Begin Page Content -->
        <div class="container-fluid">

            <!-- Page Heading -->
            <div class="d-flex flex-column align-items-center justify-content-center mb-4">
                <h1 class="h1 mb-0 text-dark text-center">Live Patient Monitor</h1>
                <!-- Interaction Button for Voice Activation -->
                <button id="enable-voice" class="btn btn-success mt-2"></button>
            </div>

            <!-- Section to display current date and time -->
            <h3 id="current-date-time" class="text-muted text-center"></h3>


            <!-- Patient Cards -->
            <div class="row" id="patient-cards">
               
            </div>
            <!-- End of Patient Cards -->

        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- End of Main Content -->

    <div id="footer" class="d-none">
        <?php include 'includes/footer.php'; ?>
    </div>
</div>

<?php 
include 'includes/scripts.php';
?>

<script>
$(document).ready(function() {
    let previousPatientIds = []; 
    let voiceEnabled = false;    

    // Enable voice interaction
    $('#enable-voice').click(function() {
        voiceEnabled = true;
        speakNotification("Welcome to Animal Bite Treatment Center.");
    });

    // Function to fetch patients
    function fetchPatients() {
        $.ajax({
            url: 'actions/live_monitor.php',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                let cardsHtml = '';
                let newPatientNames = [];

                // Loop through the data and create cards for each patient with status '1'
                data.forEach(patient => {
                    if (patient.status === '1') {
                        cardsHtml += `
                        <div class="col-md-4 mb-4">
                            <div class="card shadow-sm">
                                <div class="card-body bg-success">
                                    <h1 class="card-title text-center display-3 text-dark">${patient.patientid}</h1>
                                </div>
                            </div>
                        </div>`;

                        // Check for new patients
                        if (!previousPatientIds.includes(patient.patientid)) {
                            newPatientNames.push(`${patient.patientid}`);
                        }
                    }
                });

                // Update the patient cards
                $('#patient-cards').html(cardsHtml);

                // If there are new patients and voice is enabled, announce the message
                if (newPatientNames.length > 0 && voiceEnabled) {
                    let message = 'Please proceed to vaccination area Number: ';
                    announcePatients(newPatientNames, message, () => {
                        // Repeat announcement after all have been announced
                        setTimeout(() => {
                            announcePatients(newPatientNames, message);
                        }, 2000); // Delay before starting the repeat
                    });
                }

                // Update the list of previous patient IDs
                previousPatientIds = data.map(patient => patient.patientid);
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
            }
        });
    }

    // announce each patient with a callback after finishing the first round
    function announcePatients(patientNames, message, callback) {
        function speakNext(index) {
            if (index < patientNames.length) {
                let patientMessage = message + patientNames[index];
                speakNotification(patientMessage);

                // Call the next number after a short pause (2 seconds)
                setTimeout(() => speakNext(index + 1), 2000);
            } else if (callback) {
                callback(); // Call the callback when done announcing all patients
            }
        }

        speakNext(0); // Start announcing from the first patient
    }

    //  speak the notification
    function speakNotification(message) {
        if ('speechSynthesis' in window) {
            let utterance = new SpeechSynthesisUtterance(cleanMessage(message));
            utterance.lang = 'en-UK'; 
            window.speechSynthesis.speak(utterance);
        } else {
            console.warn('Speech synthesis not supported in this browser.');
        }
    }

    
    function cleanMessage(message) {
        return message.replace(/,/g, ''); 
    }

  
    fetchPatients();
    setInterval(fetchPatients, 5000); 
});
</script>





<script>
    $(document).ready(function() {
        function updateDateTime() {
            var now = new Date();
            var options = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            };
            var formattedDateTime = now.toLocaleDateString('en-US', options);
            $('#current-date-time').text(formattedDateTime);
        }

        // Update date and time immediately when the page loads
        updateDateTime();

        // Optionally, refresh the date and time every second
        setInterval(updateDateTime, 1000);
    });
</script>