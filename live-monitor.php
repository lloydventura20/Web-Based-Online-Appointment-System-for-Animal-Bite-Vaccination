<?php 
include 'includes/header.php'; 
?>

<style>
    .box {
        background-color: #007bff; /* Blue background */
        color: white; /* White text */
        text-align: center;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 150px;
        font-size: 18px;
        font-weight: bold;
        border-radius: 5px;
    }

    .number-box {
        background-color: white; /* White background */
        border: 2px solid #007bff; /* Blue border */
        text-align: center;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 150px;
        font-size: 30px;
        font-weight: bold;
        border-radius: 5px;
        color: #007bff; /* Blue text */
    }

    footer {
        display: none; /* Hide footer dynamically */
    }
</style>

<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">

    <!-- Main Content -->
    <div id="content">

        <!-- Begin Page Content -->
        <div class="container-fluid">

            <!-- Page Heading -->
            <div class="d-flex flex-column align-items-center justify-content-center mb-4">
                <h1 class="h1 mb-0 text-dark text-center">Animal Bite Treatment Center</h1>
                <h4 class="h2 mb-0 text-dark text-center">Patient Monitor</h4>
                <!-- Interaction Button for Voice Activation -->
                <button id="activate-sound" class="btn btn-light mt-2"></button>
            </div>

            <!-- Section to display current date and time -->
            <h3 id="current-date-time" class="text-muted text-center"></h3>


            <!-- 2x2 Grid Layout -->
            <div class="row">
    <!-- Left Column: Appointment and Walk-In -->
    <div class="col-md-4">
        <!-- Appointment Card -->
        <div class="card shadow mb-4">
            <div class="card-header text-center font-weight-bold" style="font-size: 3.5rem;">
                Appointment
            </div>
            <div class="card-body text-center bg-dark">
                <div class="number-box" id="live-appointment" style="font-size: 5rem; font-weight: bold; color: #007bff;">
                    1
                </div>
            </div>
        </div>

        <!-- Walk-In Card -->
        <div class="card shadow">
            <div class="card-header text-center font-weight-bold" style="font-size: 3.5rem;">
                Walk-In
            </div>
            <div class="card-body text-center bg-dark">
                <div class="number-box" id="live-walkin" style="font-size: 5rem; font-weight: bold; color: #007bff;">
                    1
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Live Queue -->
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header text-center font-weight-bold" style="font-size: 3.5rem;">
                Vaccination Area
            </div>
            <div class="card-body bg-success">
                <div id="liveQueue" class="row">
                    <!-- Patient IDs with status = 2 will be dynamically loaded as individual cards -->
                    <div class="col-md-4 mb-3">
                        <div class="card shadow text-center">
                            <div class="card-body">
                                <h5 class="card-title" style="font-size: 4.2rem; font-weight: bold; color: #6c757d;"></h5>
                                <p class="card-text" style="font-size: 3rem; font-weight: bold; color: #dc3545;">
                                    
                                </p>
                            </div>
                        </div>
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
include 'includes/footer.php'; // Footer is included but hidden
?>



<script>
    let soundEnabled = false; // Track if sound is enabled

    // Function to announce the count using Web Speech API
    function announceCount(type, count) {
        if ('speechSynthesis' in window && soundEnabled) {
            const utterance = new SpeechSynthesisUtterance(`${type} number ${count}`);
            speechSynthesis.speak(utterance);
        } else if (!soundEnabled) {
            console.warn('Sound is not activated.');
        } else {
            console.warn('Speech Synthesis is not supported in this browser.');
        }
    }

    // Function to update live monitor and announce changes
    function updateMonitor() {
        const walkinCount = localStorage.getItem('count-walkin') || '0';
        const appointmentCount = localStorage.getItem('count-appointment') || '0';

        const previousWalkin = $('#live-walkin').text();
        const previousAppointment = $('#live-appointment').text();

        // Update the displayed counts
        $('#live-walkin').text(walkinCount);
        $('#live-appointment').text(appointmentCount);

        // Announce changes only if the count has updated
        if (walkinCount !== previousWalkin) {
            announceCount('Walk-in', walkinCount);
        }
        if (appointmentCount !== previousAppointment) {
            announceCount('Appointment', appointmentCount);
        }
    }

    // Initialize the live monitor and keep it updated
    $(document).ready(function () {
        updateMonitor();

        // Polling to check for updates every second
        setInterval(updateMonitor, 1000);

        // Enable sound activation when button is clicked
        $('#activate-sound').on('click', function () {
            soundEnabled = true;
        });
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



<script>
  $(document).ready(function () {
    let soundActivated = false;
    const notifiedPatients = new Set(); // Use a Set to track patients who have been notified

    // Activate sound after user interaction
    $(document).on('click', function () {
        if (!soundActivated) {
            console.log('Sound activated!');
            soundActivated = true;
        }
    });

    function fetchLiveQueue() {
        $.ajax({
            url: 'actions/fetch_live_queue.php',
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                const liveQueue = $('#liveQueue');
                liveQueue.empty();

                if (data.length > 0) {
                    data.forEach(patient => {
                        liveQueue.append(`
                            <div class="col-md-4 mb-3">
                                <div class="card shadow text-center">
                                    <div class="card-body">
                                        <h5 class="card-title">Patient No.</h5>
                                        <p class="card-text" style="font-size: 3rem; font-weight: bold;">
                                            ${patient.patientid}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        `);

                        // Voice notification - Notify only twice
                        if (soundActivated && !notifiedPatients.has(patient.patientid)) {
                            speakNotification(`Please proceed to vaccination area number ${patient.patientid}`);
                            speakNotification(`Please proceed to vaccination area number ${patient.patientid}`);
                            notifiedPatients.add(patient.patientid); // Mark as notified
                        }
                    });
                } else {
                    liveQueue.append(`
                        <div class="col-12 text-center">
                            <p>No patients in the queue.</p>
                        </div>
                    `);
                }
            },
            error: function () {
                console.error('Failed to fetch live queue data.');
            }
        });
    }

    function speakNotification(message) {
        const synth = window.speechSynthesis;
        if (!synth) {
            console.error('Speech synthesis is not available.');
            return;
        }
        const utterance = new SpeechSynthesisUtterance(message);
        synth.speak(utterance);
    }

    setInterval(fetchLiveQueue, 5000);
    fetchLiveQueue();
});


</script>


