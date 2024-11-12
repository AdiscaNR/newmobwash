@extends('layouts/master')
@section('title', 'Crew')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Crew List</h1>
            <a href="{{ route('crew.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                    class="fas fa-download fa-sm text-white-50"></i> Add Crew</a>
        </div>

        <!-- Content Row -->
        <div class="row">
            <div class="col mb-4">
                <!-- Approach -->
                <div class="card shadow mb-4">
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($crews as $key => $c)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $c->name }}</td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <div>
                                                        <a class="btn btn-warning" href="{{ route('crew.edit', $c->id) }}">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    </div>
                                                    <div>
                                                        <button class="btn btn-danger"
                                                            onclick="event.preventDefault(); document.getElementById('delete-form{{ $c->id }}').submit();">
                                                            <i class="fas
                                                            fa-trash"></i>
                                                            </a>
                                                            <form method="POST" id="delete-form{{ $c->id }}"
                                                                action="{{ route('crew.destroy', $c->id) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                            </form>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            {{ $crews->links() }}
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
    <!-- /.container-fluid -->

    <!-- Delete Modal-->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Are you sure?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Are you sure to delete this crew?</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-danger" id="submitDelete">Sure</button>
                </div>
            </div>
        </div>
    </div>

@endsection
