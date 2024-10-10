<x-app-layout>
    {{-- <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div> --}}

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
                    <div class="col-6">
                        <h4 class="page-title">Filters</h4>
                        <div class="row">
                            <div class="form-group col-5">
                                <label for="days">Days</label>
                                <select class="form-control" name="days" id="days" onchange="validation()">
                                    <option value="">Select Days</option>
                                    <option value="7" {{request('days') == 7 ? 'selected' : ''}}>Last Week Days</option>
                                    <option value="15" {{request('days') == 15 ? 'selected' : ''}}>Last 15 Days</option>
                                    <option value="30" {{request('days') == 30 ? 'selected' : ''}}>Last Month Days</option>
                                    <option value="60" {{request('days') == 60 ? 'selected' : ''}}>Last 2 Month Days</option>
                                    <option value="90" {{request('days') == 90 ? 'selected' : ''}}>Last 3 Month Days</option>
                                </select>
                            </div>
                            <div class="form-group col-5">
                                <label for="status">Agent</label>
                                <select class="form-control" name="agent" id="agent" onchange="validation()">
                                    <option value="">Select Status</option>
                                    @foreach ($agentList as $agent)
                                        <option value="{{$agent}}" {{request('agent') == $agent ? 'selected' : ''}}>{{$agent}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-2 align-content-end">
                                <button type="button" class="btn btn-primary reset-form">Reset</button>
                            </div>
                        </div>
                    </div>
                </div>                
            </div>
            <div class="container-fluid pt-4">
                <div class="col-12">
                    <div class="row">
                        <div class="col-3">
                            <div class="row">
                                <div class="col-11 card bg-success w-25 p-3 mx-2">
                                    <div class="row">
                                        <div class="text-white col-12">
                                            <i class="fas fa-wallet fa-3x"></i>
                                        </div>
                                        <div class="text-white col-6">
                                            Clients effectifs
                                        </div>
                                        <div class="text-white col-6 text-right">
                                            {{$statusCount['Contrat effectif']}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="row">
                                <div class="col-11 card bg-info w-25 p-3 mx-2">
                                    <div class="row">
                                        <div class="text-white col-12">
                                            <i class="fas fa-file-invoice fa-3x"></i>
                                        </div>
                                        <div class="text-white col-6">
                                            Clients en ettente
                                        </div>
                                        <div class="text-white col-6 text-right">
                                            {{$statusCount['Contrat non effectif']}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="row">
                                <div class="col-11 card bg-dark w-25 p-3 mx-2">
                                    <div class="row">
                                        <div class="text-white col-12">
                                            <i class="fas fa-money-check fa-3x"></i>
                                        </div>
                                        <div class="text-white col-6">
                                            Commissions
                                        </div>
                                        <div class="text-white col-6 text-right">
                                            {{$payment['effectif']}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="row">
                                <div class="col-11 card bg-secondary w-25 p-3 mx-2">
                                    <div class="row">
                                        <div class="text-white col-12">
                                            <i class="fas fa-money-check-alt fa-3x"></i>
                                        </div>
                                        <div class="text-white col-8">
                                            Commissions en attente
                                        </div>
                                        <div class="text-white col-4 text-right">
                                            {{$payment['non effectif']}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="row">
                    <div  class="col-sm-6 text-center border">
                        <label class="label label-success">Commissions</label>
                        <canvas id="commissions-chart"></canvas>
                    </div>
                    <div class="col-sm-6 text-center">
                        <label class="label label-success">Bills</label>
                        <canvas id="bills-chart"></canvas>
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
            var labels = <?php echo json_encode($chart['labels']); ?>;
            var paid = <?php echo json_encode($chart['paid']); ?>;
            var unpaid = <?php echo json_encode($chart['unpaid']); ?>;

            var paidBills = <?php echo json_encode($chart['paidBills']); ?>;
            var unpaidBills = <?php echo json_encode($chart['unpaidBills']); ?>;

            var commissions_chart = document.getElementById('commissions-chart')
            new Chart(commissions_chart, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Commissions',
                            data: paid,
                            borderColor: "rgb(89, 121, 191)",
                            backgroundColor: "rgb(89, 121, 191, 1)",
                            order: 0
                        },{
                            label: 'Commissions en attente',
                            data: unpaid,
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

            var bills_chart = document.getElementById('bills-chart')
            new Chart(bills_chart, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Clients effectifs',
                            data: paidBills,
                            borderColor: "rgb(89, 121, 191)",
                            backgroundColor: "rgb(89, 121, 191, 1)",
                            order: 0
                        },{
                            label: 'Clients en attente',
                            data: unpaidBills,
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

        $(document).on('click', '.reset-form', function(event){
            window.location.href = '/'
        })

        function validation() {
            var days = document.getElementById('days').value;
            var agent = document.getElementById('agent').value;
            _params = '?';
            if(days != '') {
                _params += 'days='+days+'&';
            }
            if(agent != '') {
                _params += 'agent='+agent;
            }
            _params = _params.replace(/^&+|&+$/g, '');
            window.location.href = _params
        }
    </script>
    @endpush
</x-app-layout>
