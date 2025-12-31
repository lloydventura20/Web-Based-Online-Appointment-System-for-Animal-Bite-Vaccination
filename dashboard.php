<?php 
include 'includes/header.php';
include 'includes/navbar.php';
include 'db/connection.php';

// Fetch data for the bar chart
$queryBarChart = "
    SELECT 
        f.animal_type, 
        f.wound_type, 
        p.gender, 
        COUNT(*) AS total
    FROM findings f
    JOIN patients p ON f.patientid = p.patientid
    GROUP BY f.animal_type, f.wound_type, p.gender
";
$resultBarChart = $conn->query($queryBarChart);

$data = [];
while ($row = $resultBarChart->fetch_assoc()) {
    $data[] = $row;
}

$animalTypes = ['Dog', 'Cat', 'Others', 'NA'];
$genders = ['Male', 'Female', 'Others'];
$woundTypes = ['Bite', 'Scratch', 'NA'];

$datasets = [];
foreach ($genders as $gender) {
    foreach ($woundTypes as $woundType) {
        $datasets[$gender][$woundType] = array_fill_keys($animalTypes, 0);
    }
}

foreach ($data as $row) {
    $animal = $row['animal_type'];
    $gender = $row['gender'];
    $wound = $row['wound_type'];
    $count = $row['total'];
    $datasets[$gender][$wound][$animal] = $count;
}

$chartDatasets = [];
$colors = ['#4e73df', '#e74a3b', '#1cc88a'];
$index = 0;

foreach ($genders as $gender) {
    foreach ($woundTypes as $woundType) {
        $chartDatasets[] = [
            'label' => "$gender - $woundType",
            'backgroundColor' => $colors[$index % count($colors)],
            'data' => array_values($datasets[$gender][$woundType]),
        ];
        $index++;
    }
}

// Fetch data for the line graph
$queryLineGraph = "
    SELECT 
        p.barangay, 
        SUM(CASE WHEN p.age < 15 THEN 1 ELSE 0 END) AS below_15,
        SUM(CASE WHEN p.age >= 15 THEN 1 ELSE 0 END) AS above_15
    FROM findings f
    JOIN patients p ON f.patientid = p.patientid
    GROUP BY p.barangay
";
$resultLineGraph = $conn->query($queryLineGraph);

$barangays = [];
$dataBelow15 = [];
$dataAbove15 = [];

while ($row = $resultLineGraph->fetch_assoc()) {
    $barangays[] = $row['barangay'];
    $dataBelow15[] = $row['below_15'];
    $dataAbove15[] = $row['above_15'];
}

// Fetch data for the pie chart (Pet vs. Stray)
$queryPieChart = "
    SELECT 
        f.animal_class, 
        COUNT(*) AS total
    FROM findings f
    GROUP BY f.animal_class
";
$resultPieChart = $conn->query($queryPieChart);

$animalClasses = [];
$dataAnimalClass = [];

while ($row = $resultPieChart->fetch_assoc()) {
    $animalClasses[] = $row['animal_class'];
    $dataAnimalClass[] = $row['total'];
}

// Fetch data for Dog, Cat, and Others graph
$queryAnimalTypeChart = "
    SELECT 
        f.animal_type, 
        COUNT(*) AS total
    FROM findings f
    GROUP BY f.animal_type
";
$resultAnimalTypeChart = $conn->query($queryAnimalTypeChart);

$animalTypeLabels = [];
$dataAnimalType = [];

while ($row = $resultAnimalTypeChart->fetch_assoc()) {
    $animalTypeLabels[] = $row['animal_type'];
    $dataAnimalType[] = $row['total'];
}

$conn->close();
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
                <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
            </div>
            <div class="row">
            <?php
                        include 'db/connection.php';

                        // Query to count today's pending appointments
                        $sql_today = "SELECT COUNT(*) AS today_appointment_count FROM appointment WHERE appointment_date = CURDATE()";
                        $result_today = $conn->query($sql_today);

                        $today_pending_count = 0; // Default value
                        if ($result_today->num_rows > 0) {
                            $row_today = $result_today->fetch_assoc();
                            $today_pending_count = $row_today['today_appointment_count'];
                        }

                        // Query to count all pending appointments
                        $sql_all_pending = "SELECT COUNT(*) AS all_pending_count FROM appointment WHERE status = 'pending'";
                        $result_all_pending = $conn->query($sql_all_pending);

                        $all_pending_count = 0; // Default value
                        if ($result_all_pending->num_rows > 0) {
                            $row_all_pending = $result_all_pending->fetch_assoc();
                            $all_pending_count = $row_all_pending['all_pending_count'];
                        }
                      

                        $conn->close();
                        ?>
                <!-- Today's Appointments -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Today's Appointment</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $today_pending_count; ?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-clipboard-user fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- All Pending Appointments -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-danger shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Pending Appointments</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $all_pending_count; ?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-circle-exclamation fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            

            <!-- Content Row -->
            <div class="row mt-4">
                <!-- Bar Chart Section -->
                <div class="col-lg-6">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Bites and Scratches by Animal Type and Gender</h6>
                        </div>
                        <div class="card-body">
                            <canvas id="demographicsChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Line Chart Section -->
                <div class="col-lg-6">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Incidents Per Barangay (Age Groups)</h6>
                        </div>
                        <div class="card-body">
                            <canvas id="barangayIncidentsChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Pie Chart Section -->
                <div class="col-lg-3">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Incidents by Animal Class</h6>
                        </div>
                        <div class="card-body">
                            <canvas id="animalClassChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Animal Type Chart Section -->
                <div class="col-lg-3">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Incidents by Animal Type</h6>
                        </div>
                        <div class="card-body">
                            <canvas id="animalTypeChart"></canvas>
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
    // Bar Chart
    const ctxBarChart = document.getElementById('demographicsChart').getContext('2d');
    new Chart(ctxBarChart, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($animalTypes); ?>,
            datasets: <?php echo json_encode($chartDatasets); ?>
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Bites and Scratches by Animal Type and Gender'
                }
            },
            scales: {
                x: { stacked: true },
                y: { stacked: true, beginAtZero: true }
            }
        }
    });

    // Line Chart
    const ctxLineChart = document.getElementById('barangayIncidentsChart').getContext('2d');
    new Chart(ctxLineChart, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($barangays); ?>,
            datasets: [
                {
                    label: 'Below 15',
                    backgroundColor: 'rgba(78, 115, 223, 0.1)',
                    borderColor: 'rgba(78, 115, 223, 1)',
                    data: <?php echo json_encode($dataBelow15); ?>,
                    fill: true
                },
                {
                    label: 'Above 15',
                    backgroundColor: 'rgba(28, 200, 138, 0.1)',
                    borderColor: 'rgba(28, 200, 138, 1)',
                    data: <?php echo json_encode($dataAbove15); ?>,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Incidents Per Barangay by Age Group'
                }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Pie Chart
    const ctxPieChart = document.getElementById('animalClassChart').getContext('2d');
    new Chart(ctxPieChart, {
        type: 'pie',
        data: {
            labels: <?php echo json_encode($animalClasses); ?>,
            datasets: [{
                data: <?php echo json_encode($dataAnimalClass); ?>,
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc'],
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Incidents by Animal Class'
                }
            }
        }
    });

    // Animal Type Chart
    const ctxAnimalTypeChart = document.getElementById('animalTypeChart').getContext('2d');
    new Chart(ctxAnimalTypeChart, {
        type: 'doughnut',
        data: {
            labels: <?php echo json_encode($animalTypeLabels); ?>,
            datasets: [{
                data: <?php echo json_encode($dataAnimalType); ?>,
                backgroundColor: ['#f6c23e', '#4e73df', '#1cc88a'],
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Incidents by Animal Type'
                }
            }
        }
    });
</script>
