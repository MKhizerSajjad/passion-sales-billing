<div class="col-8">
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
                <option value="">Select Agent</option>
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
                                Clients en ettente
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
        <div class="col-sm-6 text-center">
            <label class="label label-success">Bills</label>
            <canvas id="bills-chart"></canvas>
        </div>
        <div  class="col-sm-6 text-center border">
            <label class="label label-success">Commissions</label>
            <canvas id="commissions-chart"></canvas>
        </div>
    </div>
</div>
