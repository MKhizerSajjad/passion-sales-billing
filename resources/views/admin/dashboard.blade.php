<x-app-layout>
    <div class="wrapper">
        <div class="content-page rtl-page">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-6">
                        <div class="page-title-box">
                            <div class="page-title-right">
                                @if (Auth::user()->user_type != 3 && 2 == 1)
                                    @include('admin.dashboard.comapny')
                                @else
                                    @include('admin.dashboard.customer')
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-6"></div>
                    <div class="col-12 mt-4">
                        <div class="row">
                            <div  class="col-sm-6 text-center border">
                                <label class="label label-success">Monthly Report</label>
                                <canvas id="monthly-chart"></canvas>
                            </div>
                            <div  class="col-sm-6 text-center border">
                                <label class="label label-success">Commision Report</label>
                                <canvas id="commission-chart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>                
            </div>
        </div>
    </div>
    @push('script')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            $(document).ready(function() {
                // Charts Data
                var labels = <?php echo json_encode($month['labels']); ?>;
                var energy = <?php echo json_encode($month['energy']); ?>;
                var telco = <?php echo json_encode($month['telco']); ?>;
                var energy_com = <?php echo json_encode($month['com_b']); ?>;
                var telco_com= <?php echo json_encode($month['com_t']); ?>;

                var monthly_chart = document.getElementById('monthly-chart')
                var commission_chart = document.getElementById('commission-chart')
                new Chart(monthly_chart, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [
                            {
                                label: 'Energy Bills',
                                data: energy,
                                borderColor: "rgb(89, 121, 191)",
                                backgroundColor: "rgb(89, 121, 191, 1)",
                                order: 0
                            },{
                                label: 'TELCO Bills',
                                data: telco,
                                borderColor: "rgb(40, 167, 69)",
                                backgroundColor:"rgba(102, 255, 130, 1)",
                                order: 1
                            }
                        ]
                    },
                    options: {
                        legend: {display: true,position:"bottom"},
                    }
                });
                new Chart(commission_chart, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [
                            {
                                label: 'Energy Commission',
                                data: energy_com,
                                borderColor: "rgb(89, 121, 191)",
                                backgroundColor: "rgb(89, 121, 191, 1)",
                                order: 0
                            },{
                                label: 'TELCO Commission',
                                data: telco_com,
                                borderColor: "rgb(40, 167, 69)",
                                backgroundColor:"rgba(102, 255, 130, 1)",
                                order: 1
                            }
                        ]
                    },
                    options: {
                        legend: {display: true,position:"bottom"},
                    }
                });
            })
        </script>
    @endpush
</x-app-layout>
