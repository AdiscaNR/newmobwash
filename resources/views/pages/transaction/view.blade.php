@extends('layouts/master')
@section('title', 'Transaction')

@section('content')
<!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Order Form Link</h1>
                        <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                        <div class="col mb-4">
                            <!-- Approach -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Link</h6>
                                </div>
                                <div class="card-body">
                                    <form method="POST">
                                        @csrf
                                        @method('POST')
                                        <div class="input-group d-flex gap-2">
                                            <input type="text" name="link" class="form-control" id="link" value="{{ url('/' . 'order/' . $id) }}">
                                            <button class="btn btn-primary" onclick="copyToClipboard()" type="button">Copy</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

@endsection
@section('page-script')
<script>
    $(document).ready(function() {
        $('#crew').select2({
            placeholder: "Select Crew",
            allowClear: true
        });
    });

    function copyToClipboard() {
        // Ambil elemen input
        var copyText = document.getElementById("link");
        
        // Pilih teks di dalam input
        copyText.select();
        copyText.setSelectionRange(0, 99999); // Untuk perangkat mobile

        // Salin teks ke clipboard
        navigator.clipboard.writeText(copyText.value).then(() => {
            alert("Link copied: " + copyText.value);
        }).catch((err) => {
            alert("Failed to copy link: ", err);
        });
    }
</script>
@endsection