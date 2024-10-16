<x-app-layout>
    <div class="wrapper">
        <div class="content-page rtl-page">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-6">
                        <div class="page-title-box mb-4">
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
                    <div class="col-12">
                        <form action="{{route('index')}}" method="get">
                        <div class="row">
                            <div class="form-group col-3">
                                <label for="status">Filter By Energy Agent</label>
                                <select class="form-control" name="agent" id="agent">
                                    <option value="">Select Agent</option>
                                    @foreach ($agentList as $agent)
                                        <option value="{{$agent}}" {{request('agent') == $agent ? 'selected' : ''}}>{{$agent}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-3">
                                <label for="status">Filter By Telco Supervisor</label>
                                <select class="form-control" name="supervisor" id="supervisor">
                                    <option value="">Select Supervisor</option>
                                    @foreach ($supervisorList as $agent)
                                        @if ($agent != '')
                                            <option value="{{$agent}}" {{request('supervisor') == $agent ? 'selected' : ''}}>{{$agent}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-6 pt-4 text-right">
                                <a href="{{route('index')}}" class="btn btn-warning reset-form">Reset</a>
                                <button type="submit" class="btn btn-primary reset-form">Submit</button>
                            </div>
                        </div>
                        </form>
                    </div>
                    <div class="col-12">
                        <div class="row pb-2">
                            <div class="col-sm-8 text-center border px-0">
                                <div class="graph bg-white px-2">
                                    <label class="label label-success">Monthly Report</label>
                                    <canvas id="monthly-chart"></canvas>
                                </div>
                            </div>
                            <div class="col-sm-4 text-center border px-0">
                                <div class="graph bg-white px-2">
                                    <label class="label label-success">Energy Current Month</label>
                                    <canvas id="eng-month-chart"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-8 text-center border px-0">
                                <div class="graph bg-white px-2">
                                    <label class="label label-success">Commision Report</label>
                                    <canvas id="commission-chart"></canvas>
                                </div>
                            </div>
                            <div class="col-sm-4 text-center border px-0">
                                <div class="graph bg-white px-2">
                                    <label class="label label-success">Telco Current Month</label>
                                    <canvas id="tel-month-chart"></canvas>
                                </div>
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
                var telco_com = <?php echo json_encode($month['com_t']); ?>;

                var pieEgLb = <?php echo json_encode($month['bill_pie']['label']); ?>;
                var pieEgVal = <?php echo json_encode($month['bill_pie']['values']); ?>;

                var pieTelLb = <?php echo json_encode($month['telso_pie']['label']); ?>;
                var pieTelVal = <?php echo json_encode($month['telso_pie']['values']); ?>;
                
                var monthly_chart = document.getElementById('monthly-chart')
                var commission_chart = document.getElementById('commission-chart')
                var month_chartEg = document.getElementById('eng-month-chart')
                var month_chartTl = document.getElementById('tel-month-chart')
                new Chart(monthly_chart, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [
                            {
                                label: 'Energy Contract',
                                data: energy,
                                borderColor: "rgb(89, 121, 191)",
                                backgroundColor: "rgb(89, 121, 191, 1)",
                                order: 0
                            },{
                                label: 'TELCO Contract',
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
                new Chart(month_chartEg, {
                    type: 'doughnut',
                    data: {
                        labels: pieEgLb,
                        datasets: [{
                            data: pieEgVal,    
                        }]
                    }
                });
                new Chart(month_chartTl, {
                    type: 'doughnut',
                    data: {
                        labels: pieTelLb,
                        datasets: [{
                            data: pieTelVal,    
                        }]
                    }
                });
            })
        </script>
    @endpush
</x-app-layout>
