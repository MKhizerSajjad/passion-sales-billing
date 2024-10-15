<x-app-layout>
    <div class="wrapper">
        <div class="content-page rtl-page">
            <div class="container-fluid">
                @include('admin.helper.alert_success')
                <h3 class="mb-2">Telco Reports</h3>
                <div class="row">
                    @include('admin.telco.charts.graph')
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

                var prod_chart = document.getElementById('prod-chart')
                var prodLBL = <?php echo json_encode($chart['prod_lbl']); ?>;
                var prodVAL = <?php echo json_encode($chart['prod_val']); ?>;
                new Chart(prod_chart, {
                    type: 'doughnut',
                    data: {
                        labels: prodLBL,
                        datasets: [{
                            data: prodVAL,    
                        }]
                    }
                });
            })

            $(document).on('click', '.reset-form', function(event){
                window.location.href = '?reset'
            })

            function validation() {
                // var days = document.getElementById('days').value;
                // var agent = document.getElementById('agent').value;
                // _params = '?';
                // if(days != '') {
                //     _params += 'days='+days+'&';
                // }
                // if(agent != '') {
                //     _params += 'agent='+agent;
                // }
                // _params = _params.replace(/^&+|&+$/g, '');
                // window.location.href = _params
            }
        </script>
    @endpush
</x-app-layout>
