<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use PDF;


class PostController extends Controller
{
    function __construct()
    {
         //$this->middleware('permission:post-list|post-create|post-edit|post-delete', ['only' => ['index','show']]);
         $this->middleware('permission:post-create', ['only' => ['create','store']]);
         $this->middleware('permission:post-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:post-delete', ['only' => ['destroy']]);
    }
    /**
        * Display a listing of the resource.
    */

    public function byuser(Request $request, $userId)
    {
        // Находим пользователя по ID
        $user = User::findOrFail($userId);

        // Получаем посты пользователя с пагинацией, например, по 5 постов на страницу
        $posts = $user->posts()->paginate(5);

        // Вычисляем начальный индекс для постов на текущей странице
        $i = ($request->input('page', 1) - 1) * 5;

        // Возвращаем представление с постами и переменной i
        return view('posts.index', compact('posts'))->with('i', $i);
    }

    public function bycategory(Request $request, $categoryId)
    {
        // Находим категорию по ID
        $category = Category::findOrFail($categoryId);
    
        // Получаем посты по категории с пагинацией, например, по 5 постов на страницу
        $posts = $category->posts()->paginate(5);
    
        // Вычисляем начальный индекс для постов на текущей странице
        $i = ($request->input('page', 1) - 1) * 5;
    
        // Возвращаем представление с постами и переменной i
        return view('posts.index', compact('posts'))->with('i', $i);
    }

    public function index()
    {
        $posts = Post::latest()->paginate(5);

        return view('posts.index',compact('posts'))->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $posts = Post::where('title', 'like', '%'.$query.'%')
                     ->orWhere('content', 'like', '%'.$query.'%')
                     ->latest()
                     ->paginate(5); // Используем пагинацию для поисковых результатов

                     return view('posts.index',compact('posts'))->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function searchDate(Request $request)
    {
        $date = $request->input('date');

        $posts = Post::whereDate('created_at', $date)->latest()->paginate(5);

        $posts->appends(['date' => $date]);

        return view('posts.index',compact('posts'))->with('i', (request()->input('page', 1) - 1) * 5);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('posts.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = request()->validate([
            'title' => 'required',
            'content' => 'required',
            'category_id' => 'required|exists:categories,id',
        ]);

        $post = new Post();
        $post->title = $data['title'];
        $post->content = $data['content'];
        $post->category_id = $data['category_id'];
        $post->user_id = Auth::id();

        $post->save();
    
        //Post::create($request->all());
    
        return redirect()->route('posts.index')->with('success','Post created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return view('posts.show',compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        $categories = Category::all();
        return view('posts.edit',compact('post', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);

        request()->validate([
            'title' => 'required',
            'content' => 'required',
            'category_id' => 'required|exists:categories,id',
        ]);
    
        $post->update($request->all());
    
        return redirect()->route('posts.index')->with('success','Post updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        $post->delete();
    
        return redirect()->route('posts.index')->with('success','Post deleted successfully');
    }

    public function generatePDF(Request $request)
    {
        $date = $request->input('date');
        $posts = Post::whereDate('created_at', $date)->get();
        
        $data = [
            'date' => $date,
            'posts' => $posts,
        ];


        $pdf = PDF::loadView('posts.pdf', $data);

        return $pdf->download('posts_' . $date . '.pdf');
    }
}

//Auth::user()->getRoleNames()->contains('Admin')