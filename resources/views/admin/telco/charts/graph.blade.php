<div class="col-12">
    <h3 class="page-title mb-4">Filters</h3>
    <form action="{{route('reportsTelcoBills')}}" method="get">
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
            <label for="status">Supervisor</label>
            <select class="form-control" name="agent" id="agent" onchange="validation()">
                <option value="">Select Supervisor</option>
                @foreach ($agentList as $agent)
                    @if ($agent != '')
                        <option value="{{$agent}}" {{request('agent') == $agent ? 'selected' : ''}}>{{$agent}}</option>
                    @endif
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
            <div class="col-sm-12 col-md-3 col-lg">
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
                                {{$statusCount['Active']}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-3 col-lg">
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
                                {{$statusCount['Other']}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-3 col-lg">
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
                                {{$payment['Active']}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-3 col-lg">
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
                                {{$payment['Other']}}
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
        <div  class="col-sm-6 text-center border border bg-white">
            <label class="label label-success">Commissions</label>
            <canvas id="commissions-chart"></canvas>
        </div>
    </div>
</div>
<div class="col-12 mt-3">
    <div class="row">
        <div class="col-sm-4 text-center border bg-white">
            <label class="label label-success">Product</label>
            <canvas id="prod-chart"></canvas>
        </div>
        <div class="col-sm-4 text-center border bg-white">
            <label class="label label-success">Status</label>
            <canvas id="stat-chart"></canvas>
        </div>
        <div class="col-sm-4 text-center border bg-white">
            <label class="label label-success">Scenario</label>
            <canvas id="src-chart"></canvas>
        </div>
    </div>
</div>