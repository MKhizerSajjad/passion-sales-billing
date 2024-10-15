<x-app-layout>
    <div class="wrapper">
        <div class="content-page rtl-page">
            <div class="container-fluid">
                @include('admin.helper.alert_success')
                <h3 class="mb-2">Telco Bill Listing</h3>
                <div class="row">
                    <div class="col-lg-12 mb-3">
                        <a href="{{route('importTelcoBills')}}" class="btn btn-primary btn-rounded waves-effect waves-light float-right mt-2">Import TELCO Contract</a>
                        <a href="{{route('reportsTelcoBills')}}" class="btn btn-primary btn-rounded waves-effect waves-light float-right mt-2 mr-1">Analytics Reports</a>
                    </div>
                    <div class="col-lg-12 px-2">
                        <table class="table table-bordered table-centered mt-2 mb-0 data-table">
                            <thead>
                                <tr>
                                    <th width="10%">Orrder ID</th>
                                    <th width="10%">Scenario</th>
                                    <th width="20%">Product Name</th>
                                    <th width="10%">Supervisor</th>
                                    <th width="10%">Status</th>
                                    <th width="15%">Registration Date</th>
                                    <th width="15%" >Activation Date</th>
                                    <th width="10%">Commission</th>
                                </tr>
                            </thead>
                                @foreach ($telco as $bill)
                                    <tr>
                                        <td>{{$bill->order_id}}</td>
                                        <td>{{$bill->scenario}}</td>
                                        <td>{{$bill->base_product_name}}</td>
                                        <td>{{$bill->supervisor_firstname}}</td>
                                        <td>{{$bill->status}}</td>
                                        <td>{{$bill->registration_date}}</td>
                                        <td>{{$bill->activation_date}}</td>
                                        <td>
                                            @if ($bill->status == 'ACTIVATED')
                                                {{$bill->commission}}
                                            @else
                                                0
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
