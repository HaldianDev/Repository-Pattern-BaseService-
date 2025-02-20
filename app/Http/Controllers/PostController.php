<?php

namespace App\Http\Controllers;

use App\Service\PostService;
use Illuminate\Http\Request;

class PostController extends Controller
{
    protected $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function index()
    {
        return view('posts.index');
    }

    public function getPosts()
    {
        return response()->json($this->postService->getAll());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
        ]);
        $post = $this->postService->create($data);
        return response()->json($post);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
        ]);
        $post = $this->postService->update($id, $data);
        return response()->json($post);
    }

    public function destroy($id)
    {
        $this->postService->delete($id);
        return response()->json(['message' => 'Deleted successfully']);
    }
}
