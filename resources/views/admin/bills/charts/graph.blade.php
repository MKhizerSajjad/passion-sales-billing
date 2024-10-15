<div class="col-12">
    <h4 class="page-title">Filters</h4>
    <form action="{{route('reportsBills')}}" method="get">
    <div class="row">
        <div class="form-group col-3">
            <label for="startDate">Start Date</label>
            <input type="date" name="startDate" id="startDate" class="form-control" value="{{request('startDate')}}">
        </div>
        <div class="form-group col-3">
            <label for="endDate">End Date</label>
            <input type="date" name="endDate" id="endDate" class="form-control" value="{{request('endDate')}}">
        </div>
        <div class="form-group col-3">
            <label for="status">Agent</label>
            <select class="form-control" name="agent" id="agent" onchange="validation()">
                <option value="">Select Agent</option>
                @foreach ($agentList as $agent)
                    <option value="{{$agent}}" {{request('agent') == $agent ? 'selected' : ''}}>{{$agent}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-3 pt-4 text-right">
            <button type="button" class="btn btn-warning reset-form">Reset</button>
            <button type="submit" class="btn btn-primary reset-form">Submit</button>
        </div>
    </div>
    </form>
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
                            <div class="text-white col-8">
                                Clients effectifs
                            </div>
                            <div class="text-white col-4 text-right">
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
                            <div class="text-white col-8">
                                Clients en attente
                            </div>
                            <div class="text-white col-4 text-right">
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
                            <div class="text-white col-8">
                                Commissions
                            </div>
                            <div class="text-white col-4 text-right">
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
                                Comm. en attente
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
        <div class="col-sm-6 text-center border bg-white">
            <label class="label label-success">Bills</label>
            <canvas id="bills-chart"></canvas>
        </div>
        <div  class="col-sm-6 text-center border bg-white">
            <label class="label label-success">Commissions</label>
            <canvas id="commissions-chart"></canvas>
        </div>
    </div>
</div>
<div class="col-12 mt-3">
    <div class="row">
        <div class="col-sm-4 text-center border bg-white">
            <label class="label label-success">Payments</label>
            <canvas id="payment-chart"></canvas>
        </div>
        <div class="col-sm-4 text-center border bg-white">
            <label class="label label-success">Bill</label>
            <canvas id="b-chart"></canvas>
        </div>
        <div class="col-sm-4 text-center border bg-white">
            <label class="label label-success">Contract Type</label>
            <canvas id="cont-chart"></canvas>
        </div>
    </div>
</div>
