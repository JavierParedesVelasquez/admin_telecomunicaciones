<?php include 'partials/header.php' ?>
<!-- Page Wrapper -->
<div id="wrapper">

    <?php include 'partials/sidebar.php' ?>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <?php include 'partials/topbar.php' ?>

            <!-- Begin Page Content -->
            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                </div>

                <div class="row">
                    <div class="col-12 d-flex justify-content-center">
                        <div>
                            <img src="img/banner_dashboard.svg" alt="Banner Dashboard" class="img-fluid">
                            <div class="pt-3">
                                <h1 class="h3 mb-0 text-gray-800 text-center pb-5">!Welcome <?= $_SESSION["user"]["nombre_usuario"] ?>¡</h1>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->

        <?php include 'partials/footer.php' ?>