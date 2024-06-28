@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="mb-0">Add New Category</h2>
                <a class="btn btn-primary" href="{{ route('categories.index') }}">Back</a>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> Something went wrong.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('categories.store') }}" method="POST">
        @csrf

        <div class="row">
            <div class="col-md-12">
                <div class="mb-3">
                    <label for="title" class="form-label"><strong>Title:</strong></label>
                    <input type="text" name="name" class="form-control" id="name" placeholder="Name">
                </div>
            </div>            
            <div class="col-12 text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
@endsection