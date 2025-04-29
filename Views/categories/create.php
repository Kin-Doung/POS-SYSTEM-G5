<?php require_once './views/layouts/side.php' ?>
<style>
    .navbar{
        width: 81.6%;
        margin-left: 250px;
    }
</style>
<body id="page-top">
    <!-- Page Wrapper -->
     
    <div id="wrapper" style="margin-left: 250px;">
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <div class="container">
                    <form action="/category/store" method="POST">
                        <div class="form-group">
                            <label for="" class="form-label">Name:</label>
                            <input type="text" value="" name="name" class="form-controll">
                        </div>
                        <button type="submit" class="btn btn-success mt-3">Submit</button>
                    </form>
                </div>

                <?php require_once 'views/layouts/footer.php'; ?>
            </div>
        </div>
    </div>
    <script src="../../views/assets/js/demo/chart-area-demo.js"></script>