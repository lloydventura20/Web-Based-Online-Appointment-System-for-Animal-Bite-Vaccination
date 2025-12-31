
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
                        <h1 class="h3 mb-0 text-gray-800">Walk-In Registration</h1>
                        <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
                    </div>
                    
                    <div class="form-group d-flex justify-content-end align-items-center">
                        <!-- <label for="search_patient" class="mr-2">Search Patient</label> -->
                        <div class="input-group" style="width: 250px;">
                            <input type="text" class="form-control" id="search_patient" placeholder="Enter Patient Name">
                            <div class="input-group-append">
                                <button class="btn btn-primary" id="search_button" type="button">Search</button>
                            </div>
                        </div>
                    </div>


                <!-- walk-in form -->
                    <div class="card">
                        <div class="card-header bg-info">
                            <p></p>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Personal Information</h5>
                            <hr class="sidebar-divider">
                            <!-- form -->
                            <form action="actions/save-walk-in.php" method="post">
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
                                <input type="text" class="form-control" id="age" name="age" required readonly>
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
                                    <input type="text" class="form-control" id="guardian" name="guardian">
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
                                    <input type="email" class="form-control" id="email" name="email" required>
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
                                    <!-- end of adres -->
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
                                    </select>
                                    </div>

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
                                        <label for="d2030">Day 28/30</label>
                                        <input type="date" class="form-control" id="d2030" name="d2030">
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

                                <button name="submit" type="submit" class="btn btn-success float-right">Proceed</button>
                            </form>
                             <!-- end of form -->
                            
                        </div>
                    </div>
                <!-- end of  walk-in form -->



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

    function hasSpecialChars(input) {
        const regex = /[^a-zA-Z0-9\s.,-]/; 
        return regex.test(input);
    }

    function hasInvalidBPChars(input) {
        const regex = /[^0-9\/]/; 
        return regex.test(input);
    }

    function hasInvalidDOBChars(input) {
        const regex = /[^0-9\/]/; 
        return regex.test(input);
    }

    $('form').on('submit', function(event) {
        event.preventDefault(); 

        // Show confirmation dialog before proceeding
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to submit the form?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, submit it!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Proceed with form validation
                let isValid = true; 

                $('input[type="text"]').not('#bp, #date_of_birth').each(function() {
                    const inputVal = $(this).val(); 

                    if (hasSpecialChars(inputVal)) { 
                        isValid = false; 
                        $(this).addClass('is-invalid'); 
                        $(this).next('.invalid-feedback').remove(); 
                        $(this).after('<div class="invalid-feedback">No special characters allowed.</div>'); 
                    } else {
                        $(this).removeClass('is-invalid'); 
                        $(this).next('.invalid-feedback').remove(); 
                    }
                });

                const bpVal = $('#bp').val();
                if (hasInvalidBPChars(bpVal)) {
                    isValid = false;
                    $('#bp').addClass('is-invalid');
                    $('#bp').next('.invalid-feedback').remove();
                    $('#bp').after('<div class="invalid-feedback">Only digits and "/" are allowed.</div>');
                } else {
                    $('#bp').removeClass('is-invalid');
                    $('#bp').next('.invalid-feedback').remove();
                }

                const dobVal = $('#date_of_birth').val();
                if (hasInvalidDOBChars(dobVal)) {
                    isValid = false;
                    $('#date_of_birth').addClass('is-invalid');
                    $('#date_of_birth').next('.invalid-feedback').remove();
                    $('#date_of_birth').after('<div class="invalid-feedback">Only digits and "/" are allowed.</div>');
                } else {
                    $('#date_of_birth').removeClass('is-invalid');
                    $('#date_of_birth').next('.invalid-feedback').remove();
                }

                if (isValid) {
                    $.ajax({
                        url: 'actions/save-walk-in.php', 
                        type: 'POST',
                        data: $('form').serialize(), 
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'success') {
                                Swal.fire(
                                    'Saved!',
                                    response.message,
                                    'success'
                                ).then(() => {
                                    window.location.reload(); 
                                });
                            } else {
                                Swal.fire(
                                    'Error!',
                                    response.message,
                                    'error'
                                );
                            }
                        },
                        error: function() {
                            Swal.fire(
                                'Error!',
                                'There was an error processing your request.',
                                'error'
                            );
                        }
                    });
                }
            } else {
                
                Swal.fire(
                    'Cancelled',
                    'The form was not submitted.',
                    'error'
                );
            }
        });
    });

    $('input[type="text"]').not('#bp, #date_of_birth').on('input', function() {
        const inputVal = $(this).val();
        
        if (hasSpecialChars(inputVal)) {
            $(this).addClass('is-invalid');
            $(this).next('.invalid-feedback').remove();
            $(this).after('<div class="invalid-feedback">No special characters allowed.</div>');
        } else {
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').remove();
        }
    });

    $('#bp').on('input', function() {
        const bpVal = $(this).val();
        
        if (hasInvalidBPChars(bpVal)) {
            $(this).addClass('is-invalid');
            $(this).next('.invalid-feedback').remove();
            $(this).after('<div class="invalid-feedback">Only digits and "/" are allowed.</div>');
        } else {
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').remove();
        }
    });

    $('#date_of_birth').on('input', function() {
        const dobVal = $(this).val();
        
        if (hasInvalidDOBChars(dobVal)) {
            $(this).addClass('is-invalid');
            $(this).next('.invalid-feedback').remove();
            $(this).after('<div class="invalid-feedback">Only digits and "/" are allowed.</div>');
        } else {
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').remove();
        }
    });
});
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Add SweetAlert2 -->
<script>
    $(document).ready(function () {
        $('#search_button').click(function () {
            var query = $('#search_patient').val();

            if (query.trim() !== '') {
                $.ajax({
                    url: 'actions/fetch-patient.php',
                    method: 'POST',
                    data: { query: query },
                    dataType: 'json',
                    success: function (data) {
                        if (data.status === 'not_found') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Patient not found. Please try again!',
                            });
                        } else {
                            $('#firstname').val(data.firstname);
                            $('#midname').val(data.midname);
                            $('#lastname').val(data.lastname);
                            $('#sufix').val(data.sufix);

                            var dob = new Date(data.date_of_birth);
                            var formattedDob =
                                (dob.getMonth() + 1).toString().padStart(2, '0') + '/' +
                                dob.getDate().toString().padStart(2, '0') + '/' +
                                dob.getFullYear();
                            $('#date_of_birth').val(formattedDob);
                            $('#age').val(data.age);
                            $('#place_of_birth').val(data.place_of_birth);
                            $('#gender').val(data.gender);
                            $('#cpnumber').val(data.cpnumber);
                            $('#email').val(data.email);
                            $('#civil_status').val(data.civil_status);
                            $('#guardian').val(data.guardian_name);
                            $('#nationality').val(data.nationality);
                            $('#religion').val(data.religion);
                            $('#occupation').val(data.occupation);
                            $('#region').val(data.region);
                            $('#province').val(data.province);
                            $('#municipal').val(data.municipal);
                            $('#barangay').val(data.barangay);

                            $('#exposure').val(data.exposure);
                            $('#dose').val(data.dose);
                            $('#animal_type').val(data.animal_type);
                            $('#category').val(data.category);
                            $('#vaccine_type').val(data.vaccine_type);
                            $('#wound_type').val(data.wound_type);
                            $('#sob').val(data.sob);
                            $('#dob').val(data.dob);
                            $('#pob').val(data.pob);
                            $('#wound_wash').val(data.wound_wash);
                            $('#tandok').val(data.tandok);
                            $('#animal_class').val(data.animal_class);
                            $('#pcec').val(data.pcec);
                            $('#pvrv').val(data.pvrv);
                            $('#erig').val(data.erig);

                            function formatDate(dateString) {
                                if (!dateString) return ''; 
                                const date = new Date(dateString);
                                const month = String(date.getMonth() + 1).padStart(2, '0'); 
                                const day = String(date.getDate()).padStart(2, '0');
                                const year = date.getFullYear();
                                return `${month}/${day}/${year}`;
                            }

                            $('#d1').val(formatDate(data.d1));
                            $('#d3').val(formatDate(data.d3));
                            $('#d7').val(formatDate(data.d7));
                            $('#d2030').val(formatDate(data.d2030));
                            $('#weight').val(data.weight);
                            $('#bp').val(data.bp);
                            $('#pr').val(data.pr);
                            $('#rr').val(data.rr);
                            $('#temp').val(data.temp);
                            $('#ats').val(data.ats);
                        }
                    }
                });
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Attention!',
                    text: 'Please enter a Patient Name or ID.',
                });
            }
        });
    });
</script>




 

