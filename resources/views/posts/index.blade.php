@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <form action="{{ route('generate.pdf') }}" method="POST">
                @csrf
                <div class="input-group">
                    <h3><label for="date">Report in pdf:</label></h3>
                    <input type="date" id="date" name="date" class="form-control mx-2" required>
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary mx-2">Report</button>
                    </div>
                </div>
            </form>
        </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <!-- Форма для поиска по дате -->
            <form action="{{ route('posts.searchDate') }}" method="GET" class="form-inline">
                <div class="input-group">
                    <h3><label for="date">Search by date:</label></h3>
                    <input type="date" name="date" value="{{ request('date') }}" class="form-control mx-2">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary mx-2">Search</button>
                    </div>
                </div>
            </form>
            <!-- Форма для поиска по постам -->
            <form action="{{ route('posts.search') }}" method="GET" class="form-inline">
                <div class="input-group">
                    <input type="text" name="query" class="form-control" placeholder="Search...">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary mx-1">Search</button>
                    </div>
                </div>
            </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>Tasks</h2>
                @can('post-create')
                    <a class="btn btn-success" href="{{ route('posts.create') }}">Create</a>
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
                <th scope="col">No</th>
                <th scope="col">Date</th>
                <th scope="col">User</th>
                <th scope="col">Mashine</th>
                <th scope="col">Task</th>
                <th scope="col" width="280px">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($posts as $post)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $post->created_at->format('d.m.Y') }}</td>
                    <td><a href="{{ route('users.posts.index', ['userId' => $post->user->id]) }}">{{ $post->user->name }}</a></td>
                    <td><a href="{{ route('categories.posts.index', ['categoryId' => $post->category->id]) }}">{{ $post->category->name }}</a></td>
                    <td>{{ $post->title }}</td>
                    <!-- <td>{{ $post->content }}</td> -->
                    <td>
                        <div class="d-flex gap-2" role="group" aria-label="User Actions">
                            <a class="btn btn-primary flex-fill" href="{{ route('posts.show', $post->id) }}">Show</a>
                            @can('update', $post)
                                <a class="btn btn-success flex-fill" href="{{ route('posts.edit', $post->id) }}">Edit</a>
                            @endcan
                            <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="d-flex flex-fill">
                                @csrf
                                @method('DELETE')
                                @can('delete', $post)
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

    {{ $posts->links('vendor.pagination.custom') }}
@endsection