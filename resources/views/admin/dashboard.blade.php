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
                @if (Auth::user()->user_type != 3 && 2 == 1)
                    @include('admin.dashboard.comapny')
                @else
                    @include('admin.dashboard.customer')
                @endif
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
                                            Paid Bills
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
                                            Pending Bills
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
                                            Paid Commission
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
                                            Pending Commission
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
                        <label class="label label-success">Commissions Chart</label>
                        <canvas id="chart1"></canvas>
                    </div>
                    <div class="col-sm-6 text-center">
                        <label class="label label-success">Bills Chart</label>
                        <canvas id="chart2"></canvas>
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

            var chart1 = document.getElementById('chart1')
            new Chart(chart1, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Paid Commission',
                            data: paid,
                            borderColor: "rgb(89, 121, 191)",
                            backgroundColor: "rgb(89, 121, 191, 1)",
                            order: 0
                        },{
                            label: 'UnPaid Commission',
                            data: unpaid,
                            borderColor: "rgb(40, 167, 69)",
                            backgroundColor:"rgba(102, 255, 130, 1)",
                            order: 1
                        }
                    ]
                },
                options: {
                    legend: {display: true,position:"bottom"},
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero:true
                            }
                        }]
                    },
                    title: {
                        display: true,
                        text: 'Article Stats Past 10 Days',
                        position:"bottom"
                    }
                }
            });
            
            var chart2 = document.getElementById('chart2')
            new Chart(chart2, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Paid Bills',
                            data: paidBills,
                            borderColor: "rgb(89, 121, 191)",
                            backgroundColor: "rgb(89, 121, 191, 1)",
                            order: 0
                        },{
                            label: 'UnPaid Bills',
                            data: unpaidBills,
                            borderColor: "rgb(40, 167, 69)",
                            backgroundColor:"rgba(102, 255, 130, 1)",
                            order: 1
                        }
                    ]
                },
                options: {
                    legend: {display: true,position:"bottom"},
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero:true
                            }
                        }]
                    },
                    title: {
                        display: true,
                        text: 'Article Stats Past 10 Days',
                        position:"bottom"
                    }
                }
            });
        })
    </script>
    @endpush
</x-app-layout>
