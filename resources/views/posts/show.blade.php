@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>Show</h2>
                <a class="btn btn-primary" href="{{ route('posts.index') }}">Back</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="mb-3">
                <strong>User:</strong>
                {{ $post->user->name }}
            </div>
        </div>
        <div class="col-md-4">
            <div class="mb-3">
                <strong>Mashine:</strong>
                {{ $post->category->name }}
            </div>
        </div>
        <div class="col-md-4">
            <div class="mb-3">
                <strong>Task:</strong>
                {{ $post->title }}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="mb-3">
                <strong>Content:</strong>
                {{ $post->content }}
            </div>
        </div>
    </div>
@endsection