@extends('layouts/master')
@section('title', 'Add Crew')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Add Crew</h1>
            <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                                                    class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
        </div>

        <!-- Content Row -->
        <div class="row">
            <div class="col mb-4">
                <!-- Approach -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Form</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('crew.store') }}" method="POST">
                            @csrf
                            @method('POST')
                            <div class="form-group dropdown">
                                <label for="name">Crew Name</label>
                                <input type="text" name="name" class="form-control" id="name"
                                    placeholder="Crew Name">
                            </div>
                            <hr />

                            <div class="form-group row">
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>

    </div>
    <!-- /.container-fluid -->

@endsection