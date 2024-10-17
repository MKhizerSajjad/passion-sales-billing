<x-app-layout>
    <div class="wrapper">
        <div class="content-page rtl-page">
            <div class="container-fluid">
                @include('admin.helper.alert_success')
                <h3>Import Contrats Telco</h3>
                <form action="{{ route('importTelcoBillsSaved') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="file">Choose File</label>
                        <input type="file" name="file" class="form-control" id="file" required accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                        <p class="text-danger">{{ $errors->first('file') }}</p>
                    </div>
                    <div class="form-group  text-right">
                        <a href="{{ route('telcoListing') }}" class="btn btn-danger">Cancel</a>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>