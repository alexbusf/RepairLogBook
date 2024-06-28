@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="mb-0">Edit</h2>
                <a class="btn btn-primary" href="{{ route('posts.index') }}">Back</a>
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


    <form action="{{ route('posts.update',$post->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-12">
                <div class="mb-3">
                        <label for="ategory_id" class="form-label"><strong>Mashine:</strong></label>
                        <select name="category_id" id="category_id">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $post->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div>{{ $message }}</div>
                        @enderror
                </div>
                <div class="mb-3">
                    <label for="title" class="form-label"><strong>Task:</strong></label>
                    <input type="text" name="title" value="{{ $post->title }}" class="form-control" id="title" placeholder="Task">
                </div>
                <div class="mb-3">
                    <label for="content" class="form-label"><strong>Content:</strong></label>
                    <textarea class="form-control" style="height:150px" name="content" id="content" placeholder="Content">{{ $post->content }}</textarea>
                </div>
            </div>
            <div class="col-12 text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>

@endsection