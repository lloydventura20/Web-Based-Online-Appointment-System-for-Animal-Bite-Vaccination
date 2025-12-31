<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>ABTC - Register</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">


</head>
<style>
    .margin-bot{
        margin-bottom: 2px;
    }
    .margin-bott{
        margin-bottom: 7px;
    }
    
</style>

<body class="bg-success">

    <div class="container mt-4">

        <div class="card o-hidden border-0 shadow-lg my-3">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <!-- <div class="col-lg-5 d-none d-lg-block bg-register-image"></div> -->
                    <div class="col-lg">
                        <div class="p-1">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Animal Bite Treatment Center</h1>
                                <h2 class="h4 text-gray-900 mb-4">Appointment</h2>
                                <!-- Divider -->
                            <!-- <hr class="sidebar-divider my-0"> -->
                            </div>
                            
                            <div class="card">
                        <div class="card-header bg-info">
                            <!-- <p></p> -->
                        </div>
                        <div class="card-body ">
                            <h5 class="card-title">Personal Information</h5>
                            <hr class="sidebar-divider">
                            <!-- form -->
                            <form action="actions/save-register.php" method="post">
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
                                    <input type="text" class="form-control" id="date_of_birth" name="date_of_birth" placeholder="mm/dd/yyyy" required>
                                    </div>
                                    <div class="form-group col-md-1">
                                    <label for="age">Age</label>
                                    <input type="text" class="form-control" id="age" name="age" readonly>
                                    </div>
                                    <div class="form-group col-md-3">
                                    <label for="place_of_birth">Place of Birth</label>
                                    <input type="text" class="form-control" id="place_of_birth" name="place_of_birth" required>
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
                                    <label for="nationality">Nationality</label>
                                    <input type="text" class="form-control" id="nationality" name="nationality" required>
                                    </div>
                                    <div class="form-group col-md-3">
                                    <label for="religion">Religion</label>
                                    <input type="text" class="form-control" id="religion" name="religion" required>
                                    <small class="form-text text-success">Put NA if not Applicable</small>
                                    </div>
                                    <div class="form-group col-md-3">
                                    <label for="occupation">Occupation</label>
                                    <input type="text" class="form-control" id="occupation" name="occupation" required>
                                    <small class="form-text text-success">Put NA if not Applicable</small>
                                    </div>
                                    <div class="form-group col-md-3">
                                    <label for="guardian">Guardian Name</label>
                                    <input type="text" class="form-control" id="guardian" name="guardian">
                                    <small class="form-text text-success">Put NA if not Applicable</small>
                                    </div>
                                </div>

                                <!-- contact -->
                                <hr class="sidebar-divider">
                                <h5 class="card-title">Contact Information</h5>
                                <hr class="sidebar-divider">
                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                    <label for="cpnumber">Cellphone Number</label>
                                    <input type="text" class="form-control" id="cpnumber" name="cpnumber" placeholder="09#########" required>
                                    </div>
                                    <div class="form-group col-md-3">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email">
                                    <span id="emailFeedback"></span>
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
                                        <select class="form-control" id="region" name="region" required>
                                            <option value="">Select Region</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="province">Province</label>
                                        <select class="form-control" id="province" name="province" required>
                                            <option value="">Select Province</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="municipal">Municipal</label>
                                        <select class="form-control" id="municipal" name="municipal" required>
                                            <option value="">Select Municipal</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="barangay">Barangay</label>
                                        <select class="form-control" id="barangay" name="barangay" required>
                                            <option value="">Select Barangay</option>
                                        </select>
                                    </div>                  
                                </div>
                                    <input type="hidden" id="selectedRegionName" name="region">
                                    <input type="hidden" id="selectedProvinceName" name="province">
                                    <input type="hidden" id="selectedCityName" name="municipal">
                                    <input type="hidden" id="selectedBarangayName" name="barangay">

                                <hr class="sidebar-divider">
                                <h5 class="card-title">Appointment Date</h5>
                                <hr class="sidebar-divider">
                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <label for="dayPicker" class="fw-bold">Select a Day</label>
                                        <select class="form-control" id="dayPicker" name="dayPicker" required>
                                            <option value="">Choose...</option>
                                            <option value="2">Tuesday</option>
                                            <option value="5">Friday</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="datePicker" class="fw-bold">Appointment Date</label>
                                        <input type="text" class="form-control" id="datePicker" name="datePicker" required readonly>
                                    </div>
              
                                </div>
                                <div class="d-flex justify-content-end">
                                    <a href="index.php" class="btn btn-primary" style="margin-right: 10px;">
                                        Cancel
                                    </a>
                                    <button type="submit" class="btn btn-success" name="submit">Submit Appointment</button>
                                </div>
                            </form>
                            
                             <!-- end of form -->
                            
                        </div>
                    </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>



</body>

</html>

<script>
$(document).ready(function() {
    var isEmailAvailable = false; 

    $('form').on('submit', function(e) {
    e.preventDefault(); 

    function validateInput(input, isDatepicker) {
        var regex = isDatepicker ? /^[a-zA-Z0-9\s\/]+$/ : /^[a-zA-Z0-9\s]+$/;
        return regex.test(input);
    }

    var valid = true;
    var errorMsg = '';

    // Clear previous error messages
    $('.error-message').remove();
    $('input, textarea').removeClass('input-error');

    $('input[type="text"], textarea').each(function() {
        var fieldName = $(this).attr('name');
        var value = $(this).val().trim();
        
        var isDatepicker = (fieldName === 'datePicker');
        
        if (fieldName !== 'date_of_birth' && fieldName !== 'email') {
            if (!validateInput(value, isDatepicker)) {
                valid = false;
                errorMsg = 'Invalid input detected.';
                
                $(this).after('<span class="error-message" style="color: red; font-size: 12px;">' + errorMsg + '</span>');
                
                $(this).addClass('input-error');
            }
        }
    });

    if (!valid) {
        Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            text: 'Please correct the invalid fields.',
            confirmButtonColor: '#d33',
        });
        return; 
    }

    Swal.fire({
        title: 'Are you sure?',
        text: "Do you want to submit the appointment form?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, submit it!'
    }).then((result) => {
        if (result.isConfirmed) {
            var formData = $(this).serialize(); 
            
            console.log("Form Data:", formData);

            $.ajax({
                url: 'actions/save-register.php', 
                type: 'POST',
                data: formData,
                success: function(response) {
                    console.log("Success:", response); 
                    Swal.fire(
                        'Submitted!',
                        'Your appointment has been submitted.',
                        'success'
                    ).then(() => {
                        window.location.href = 'index.php'; 
                    });
                },
                error: function(xhr, status, error) {
                    console.error("Error:", error); 
                    Swal.fire(
                        'Error!',
                        'There was an error submitting your appointment.',
                        'error'
                    );
                }
            });
        }
    });
});

});
</script>


<script>
$(document).ready(function() {
    $('#date_of_birth').datepicker({
        changeMonth: true,
        changeYear: true,
        yearRange: "1900:+0",
        dateFormat: "mm/dd/yy",
        maxDate: new Date(),
        onSelect: function(value) {
            calculateAge(value);
        }
    });

    function calculateAge(dobStr) {
        var dob = new Date(dobStr);
        var today = new Date();
        var age = today.getFullYear() - dob.getFullYear();
        var monthDiff = today.getMonth() - dob.getMonth();
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
            age--;
        }
        $('#age').val(age);
    }
});
</script>

<script>
    $(document).ready(function() {
        $('#cpnumber').on('input', function() {
            var input = $(this).val();
            var numericInput = input.replace(/\D/g, '');

            
            if (numericInput.length > 0 && numericInput.substring(0, 2) !== '09') {
                numericInput = '09' + numericInput;
            }
            
            
            if (numericInput.length > 11) {
                numericInput = numericInput.substring(0, 11);
            }
            
            $(this).val(numericInput);
        });
    });
</script>

<script>
    $(document).ready(function() {
        loadData();
    });

    function loadData() {
        $.when(
            $.getJSON('ph-json/region.json'),
            $.getJSON('ph-json/province.json'),
            $.getJSON('ph-json/city.json'),
            $.getJSON('ph-json/barangay.json')
        ).done(function(regionData, provinceData, cityData, barangayData) {
            regions = regionData[0];
            provinces = provinceData[0];
            cities = cityData[0];
            barangays = barangayData[0];
            populateRegionDropdown();
        }).fail(function(xhr, status, error) {
            console.error('Failed to fetch data:', status, error);
            if (xhr.status === 404) {
                console.error('File not found. Check the path to the JSON file.');
            } else if (xhr.status === 500) {
                console.error('Server error. Check server logs for more information.');
            }
        });
    }

    function populateRegionDropdown() {
        const $region = $('#region');
        $region.empty();
        $region.append('<option value="">Select Region</option>');
        $.each(regions, function(index, region) {
            $region.append(`<option value="${region.region_code}">${region.region_name}</option>`);
        });

        $region.change(function() {
            const selectedRegionCode = $(this).val();
            const selectedRegionName = $(this).find('option:selected').text();
            $('#selectedRegionName').val(selectedRegionName);
            populateProvinceDropdown(selectedRegionCode);
        });
    }

    function populateProvinceDropdown(regionCode) {
        const $province = $('#province');
        $province.empty();
        $province.append('<option value="">Select Province</option>');
        if (regionCode) {
            const filteredProvinces = provinces.filter(p => p.region_code === regionCode);
            $.each(filteredProvinces, function(index, province) {
                $province.append(`<option value="${province.province_code}">${province.province_name}</option>`);
            });
        }

        $province.change(function() {
            const selectedProvinceCode = $(this).val();
            const selectedProvinceName = $(this).find('option:selected').text();
            $('#selectedProvinceName').val(selectedProvinceName);
            populateCityDropdown(selectedProvinceCode);
        });
    }

    function populateCityDropdown(provinceCode) {
        const $municipal = $('#municipal');
        $municipal.empty();
        $municipal.append('<option value="">Select Municipal</option>');
        if (provinceCode) {
            const filteredCities = cities.filter(c => c.province_code === provinceCode);
            $.each(filteredCities, function(index, city) {
                $municipal.append(`<option value="${city.city_code}">${city.city_name}</option>`);
            });
        }

        $municipal.change(function() {
            const selectedCityCode = $(this).val();
            const selectedCityName = $(this).find('option:selected').text();
            $('#selectedCityName').val(selectedCityName);
            populateBarangayDropdown(selectedCityCode);
        });
    }

    function populateBarangayDropdown(cityCode) {
        const $barangay = $('#barangay');
        $barangay.empty();
        $barangay.append('<option value="">Select Barangay</option>');
        if (cityCode) {
            const filteredBarangays = barangays.filter(b => b.city_code === cityCode);
            $.each(filteredBarangays, function(index, barangay) {
                $barangay.append(`<option value="${barangay.brgy_code}">${barangay.brgy_name}</option>`);
            });
        }

        $barangay.change(function() {
            const selectedBarangayCode = $(this).val();
            const selectedBarangayName = $(this).find('option:selected').text();
            $('#selectedBarangayName').val(selectedBarangayName);
        });
    }
</script>

<script>
$(document).ready(function() {

    function getNearestDate(dayOfWeek) {
        var today = moment();
        var daysUntilNext = (dayOfWeek + 7 - today.day()) % 7;
        if (daysUntilNext === 0) {
            daysUntilNext = 7;
        }
        return today.add(daysUntilNext, 'days').format('MM/DD/YYYY'); // Updated to match PHP format
    }

    function initializeDatePicker(selectedDay) {
        var daysOfWeekDisabled = selectedDay === 2 ? [0, 1, 3, 4, 5, 6] : [0, 1, 2, 3, 4, 6];

        $('#datePicker').datepicker('destroy').datepicker({
            beforeShowDay: function(date) {
                var day = date.getDay();
                return [daysOfWeekDisabled.indexOf(day) === -1];
            },
            dateFormat: 'mm/dd/yy', // Updated to match PHP format
            autoclose: true
        }).prop('disabled', false);

        var nearestDate = getNearestDate(selectedDay);
        $('#datePicker').datepicker('setDate', nearestDate);
    }

    $('#dayPicker').on('change', function() {
        var selectedDay = parseInt($(this).val(), 10);
        if (selectedDay) {
            initializeDatePicker(selectedDay);
        } else {
            $('#datePicker').datepicker('destroy').prop('disabled', true).val('');
        }
    });

    $('#datePicker').on('focus click', function(event) {
        event.preventDefault();
        event.stopImmediatePropagation();
        $(this).blur();
    });
});
</script>



