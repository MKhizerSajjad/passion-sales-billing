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
                </div>                
            </div>
        </div>
    </div>
</x-app-layout>
