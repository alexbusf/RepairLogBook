@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>Categories</h2>
                @can('category-create')
                    <a class="btn btn-success" href="{{ route('categories.create') }}">Create New Category</a>
                @endcan
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <label>{{ $message }}</label>
        </div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col" width="280px">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($categories as $category)
                <tr>
                    <td>{{ $category->name }}</td>
                    <td>
                        <div class="d-flex gap-2" role="group" aria-label="User Actions">
                            <!-- <a class="btn btn-primary flex-fill" href="{{ route('categories.show', $category->id) }}">Show</a> -->
                            @can('category-edit')
                                <a class="btn btn-success flex-fill" href="{{ route('categories.edit', $category->id) }}">Edit</a>
                            @endcan
                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="d-flex flex-fill">
                                @csrf
                                @method('DELETE')
                                @can('category-delete')
                                    <button type="submit" class="btn btn-danger flex-fill">Delete</button>
                                @endcan
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">No records found</td>
                </tr>
            @endforelse

        </tbody>
    </table>
@endsection