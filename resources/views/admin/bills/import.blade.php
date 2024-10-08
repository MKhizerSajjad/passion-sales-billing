<x-app-layout>
    <div class="wrapper">
        <div class="content-page rtl-page">
            <div class="container-fluid">
                <h3>Import Bills</h3>
                <form action="{{ route('importBills') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="file">Choose File</label>
                        <input type="file" name="file" class="form-control" id="file" required accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>


</x-app-layout>