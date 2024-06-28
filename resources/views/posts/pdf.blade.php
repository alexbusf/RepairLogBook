<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>PDF Title</title>
    <style type="text/css">
        * {
        /*font-family: Helvetica, sans-serif;*/
        font-family: "DejaVu Sans", sans-serif;
        font-size: 10;
        }
    </style>
</head>
<body>
    <h3>Report: {{ $date }}</h3>

    @if($posts->isEmpty())
        <p>No accidents.</p>
    @else
        <h5>User: {{ $posts->first()->user->name }}</h5>
        @foreach ($posts as $post)
            <p>Mashine: <u>{{ $post->category->name }}</u> Accident: <b>{{ $post->title }}</b></p>
            <p>{{ $post->content }} </p>
        <hr>
        @endforeach
    @endif
</body>
</html>