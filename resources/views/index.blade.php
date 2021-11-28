@include("partials.main")

<head>

    @include("partials.title-meta")

    @include("partials.head-css")

</head>

@include("partials.body")

<!-- Begin page -->
<div id="layout-wrapper">

    @include("partials.menu")

    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">

                @include("partials.page-title")

                <div class="row align-items-end">
                    <div class="col-xl-3">
                        <div>
                            <label for="example-date-input" class="form-label">Дата від</label>
                            <input class="form-control" type="date" value="2021-06-29" id="example-date-input">
                        </div>
                    </div>
                    <div class="col-xl-3">
                        <div>
                            <label for="example-date-input" class="form-label">Дата до</label>
                            <input class="form-control" type="date" value="2021-11-29" id="example-date-input">
                        </div>
                    </div>
                    <div class="col-xl-3">
                        <div class="mt-2 visible-xs">
                            <button type="submit" class="btn btn-primary w-md">Застосувати</button>
                        </div>
                    </div>
                </div>

                <br>

                <div class="row">
                    <div class="col-xl-6">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title mb-0">Середня доля розробки і доставки</h4>
                            </div>
                            <div class="card-body">

                                <div id="line_chart_datalabel" data-colors='["#5156be", "#2ab57d"]' class="apex-charts" dir="ltr"></div>
                            </div>
                        </div><!--end card-->
                    </div>

                    <div class="col-xl-6">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title mb-0">Time-to-Market</h4>
                            </div>
                            <div class="card-body">

                                <div id="line_chart_dashed" data-colors='["#339966"]' class="apex-charts" dir="ltr"></div>
                            </div>
                        </div><!--end card-->
                    </div>
                </div>
                <!-- end row -->

                <div class="row">
                    <div class="col-xl-6">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title mb-0">Тривалість перебування задач у статусах</h4>
                            </div>
                            <div class="card-body">
                                <div id="spline_area" data-colors='["#009999"]' class="apex-charts" dir="ltr"></div>
                            </div>
                        </div><!--end card-->
                    </div>

                    <div class="col-xl-6">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title mb-0">Частота змін опису задач після початку спринта</h4>
                            </div>
                            <div class="card-body">
                                <div id="column_chart" data-colors='["#2ab57d", "#5156be", "#fd625e"]' class="apex-charts" dir="ltr"></div>
                            </div>
                        </div><!--end card-->
                    </div>
                </div>
                <!-- end row -->

                <div class="row">
                    <div class="col-xl-6">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title mb-0">Швидкість команди</h4>
                            </div>
                            <div class="card-body">
                                <div id="column_chart_datalabel" data-colors='["#5156be"]' class="apex-charts" dir="ltr"></div>
                            </div>
                        </div><!--end card-->
                    </div>
                    <div class="col-xl-6">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title mb-0">Середня доля багів</h4>
                            </div>
                            <div class="card-body">
                                <div id="bar_chart" data-colors='["#2ab57d"]' class="apex-charts" dir="ltr"></div>
                            </div>
                        </div><!--end card-->
                    </div>
                </div>
                <!-- end row -->

                <div class="row">
                    <div class="col-xl-6">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title mb-0">Тривалість життя багів</h4>
                            </div>
                            <div class="card-body">
                                <div id="mixed_chart" data-colors='["#fd625e", "#5156be", "#2ab57d"]' class="apex-charts" dir="ltr"></div>
                            </div>
                        </div><!--end card-->
                    </div>
                    <div class="col-xl-6">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title mb-0">Кількість переводів в статус ToDo з різних статусів

                                </h4>
                            </div>
                            <div class="card-body">
                                <div id="radial_chart" data-colors='["#ff9900"]' class="apex-charts" dir="ltr"></div>
                            </div>
                        </div><!--end card-->

                    </div>
                </div>
                <!-- end row -->

            </div> <!-- container-fluid -->
        </div>
        <!-- End Page-content -->


        @include("partials.footer")
    </div>
    <!-- end main content-->

</div>
<!-- END layout-wrapper -->

@include("partials.right-sidebar")

@include("partials.vendor-scripts")

<!-- apexcharts js -->
<script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>

<!-- apexcharts init -->
<script src="{{ asset('js/pages/apexcharts.init.js') }}"></script>

<script src="{{ asset('assets/js/app.js') }}"></script>

</body>
</html>
