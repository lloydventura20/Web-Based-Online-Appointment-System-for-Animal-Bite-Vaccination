            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Animal Bite Treatment Center</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->





        </body>

</html>

<script>  	
$('#dataTable').DataTable();
</script>

<script>
    $(document).ready(function() {
        // Handle logout button click
        $('#logoutButton').on('click', function() {
            // Show loading alert
            Swal.fire({
                title: 'Logging out...',
                text: 'Please wait while we end your session.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading(); // Start loading animation
                }
            });

            // Simulate a delay before redirecting
            setTimeout(() => {
                window.location.href = "logout.php";
            }, 1500); // Adjust the delay as needed
        });
    });
</script>

<!-- <script>
    if ($.fn.DataTable.isDataTable('#dataTableStatus3')) {
    $('#dataTableStatus3').DataTable().destroy();
}
$('#dataTableStatus3').DataTable({
    "paging": true,
    "lengthChange": true,
    "searching": true,
    "ordering": true,
    "info": true,
    "autoWidth": false,
    "responsive": true
});
</script> -->

