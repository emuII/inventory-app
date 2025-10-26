    </div>
    <!-- /.container-fluid -->

    </div>
    <!-- End of Main Content -->
    <!-- Footer -->
    <footer class="sticky-footer bg-white">
        <div class="container my-auto">
            <div class="copyright text-center my-auto">
                <span>
                    <?php echo date('Y'); ?> - Sistem Inventory Management |
                    by <b><a href="#" target="_blank">_404_</a></b>
                </span>
            </div>
        </div>
    </footer>
    <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    <!--Select 2-->
    <link rel="stylesheet" href="public/select2/css/select2.min.css">
    <script src="public/select2/js/select2.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="public/sb-admin/js/sb-admin-2.min.js"></script>
    <script src="public/sb-admin/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="public/sb-admin/vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <script type="text/javascript">

    </script>
    <script type="application/javascript">
        //angka 500 dibawah ini artinya pesan akan muncul dalam 0,5 detik setelah document ready
        $(document).ready(function() {
            setTimeout(function() {
                $(".alert-danger").fadeIn('slow');
            }, 500);

            $('.select2').select2();
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true
            });
        });
        //angka 3000 dibawah ini artinya pesan akan hilang dalam 3 detik setelah muncul
        setTimeout(function() {
            $(".alert-danger").fadeOut('slow');
        }, 5000);

        $(document).ready(function() {
            setTimeout(function() {
                $(".alert-success").fadeIn('slow');
            }, 500);
        });
        setTimeout(function() {
            $(".alert-success").fadeOut('slow');
        }, 5000);

        $(document).ready(function() {
            setTimeout(function() {
                $(".alert-warning").fadeIn('slow');
            }, 500);
        });
        setTimeout(function() {
            $(".alert-success").fadeOut('slow');
        }, 5000);
    </script>
    <script>
        $(".modal-create").hide();
        $(".bg-shadow").hide();
        $(".bg-shadow").hide();

        function clickModals() {
            $(".bg-shadow").fadeIn();
            $(".modal-create").fadeIn();
        }

        function cancelModals() {
            $('.modal-view').fadeIn();
            $(".modal-create").hide();
            $(".bg-shadow").hide();
        }
    </script>

    </body>

    </html>