<x-app-layout>
    <div class="wrapper">
        <div class="content-page rtl-page">
            <div class="container-fluid">
                @include('admin.helper.alert_success')
                <h3 class="mb-2">Bills Listing</h3>
                <div class="row">
                    <div class="col-lg-12 px-2">
                        <table class="table table-bordered table-centered mt-2 mb-0 data-table">
                            <thead>
                                <tr>
                                    <th width="10%">Bill ID</th>
                                    <th width="20%">Status</th>
                                    <th width="10%">Userfield Agent</th>
                                    <th width="10%" >Payment</th>
                                    <th width="5%">B2C/B2B</th>
                                    <th width="10%">Consumption</th>
                                    <th width="5%">Commission</th>
                                    <th width="10%">Contract</th>
                                    <th width="20%">Product</th>
                                    <th width="10%">Bill</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bills as $bill)
                                    <tr>
                                        <td>{{ $bill->bill_id }}</td>
                                        <!-- <td>{{ date('F j, Y, g:i A', strtotime($bill->inscription_date)) }} </td> -->
                                        <td>{{ $bill->status }}</td>
                                        <td>{{ $bill->userfield_agent }}</td>
                                        <td>{{ $bill->payment_type }}</td>
                                        <td>{{ $bill->b2c_b2b }}</td>
                                        <td>{{ $bill->consumption }}</td>
                                        <td>{{ $bill->commission }}</td>
                                        <td>{{ $bill->contract_type }}</td>
                                        <td>{{ $bill->product_type }}</td>
                                        <td>{{ $bill->bill }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
