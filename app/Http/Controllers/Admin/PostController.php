<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;

use Illuminate\Support\Facades\Storage;

use App\Http\Requests\PostRequest;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Cache;

class PostController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:admin.posts.index')->only('index');
        $this->middleware('can:admin.posts.create')->only('create', 'store');
        $this->middleware('can:admin.posts.edit')->only('edit', 'update');
        $this->middleware('can:admin.posts.destroy')->only('destroy');
    }


    public function index()
    {
        return view('admin.posts.index');
    }

    public function create()
    {
        $categories = Category::pluck('name', 'id');
        $tags = Tag::all();

        return view('admin.posts.create', compact('categories', 'tags'));
    }

    public function store(PostRequest $request)
    {
        $post = Post::create($request->all());

        if ($request->file('file')) {

            // CLOUDINARY
            $uploadedFileUrl = Cloudinary::upload(
                $request->file('file')->getRealPath(),
                ['folder' => 'LaravelBlog']
            );

            $post->image()->create([
                'url' => $uploadedFileUrl->getSecurePath(),
                'publicId' => $uploadedFileUrl->getPublicId()
            ]);
        }


        if ($request->tags) {
            $post->tags()->attach($request->tags);
        }

        Cache::flush();

        return redirect()->route('admin.posts.edit', $post);
    }

    public function edit(Post $post)
    {

        $this->authorize('author', $post);

        $categories = Category::pluck('name', 'id');
        $tags = Tag::all();

        return view('admin.posts.edit', compact('post', 'categories', 'tags'));
    }

    public function update(PostRequest $request, Post $post)
    {

        $this->authorize('author', $post);

        $post->update($request->all());

        if ($request->file('file')) {
            // CLOUDINARY UPLOAD
            $uploadedFileUrl = Cloudinary::upload(
                $request->file('file')->getRealPath(),
                ['folder' => 'LaravelBlog']
            );

            if ($post->image) {
                // CLOUDINARY DELETE
                $deleteImage = Cloudinary::destroy(
                    $post->image->publicId
                );

                $post->image->update([
                    'url' => $uploadedFileUrl->getSecurePath(),
                    'publicId' => $uploadedFileUrl->getPublicId()
                ]);
            } else {
                $post->image()->create([
                    'url' => $uploadedFileUrl->getSecurePath(),
                    'publicId' => $uploadedFileUrl->getPublicId()
                ]);
            }
        }

        if ($request->tags) {
            $post->tags()->sync($request->tags);
        }

        Cache::flush();

        return redirect()->route('admin.posts.edit', $post)->with('info', 'El post se actualizo con exito');
    }

    public function destroy(Post $post)
    {
        $this->authorize('author', $post);

        $post->delete();

        $deleteImage = Cloudinary::destroy(
            $post->image->publicId
        );

        Cache::flush();

        return redirect()->route('admin.posts.index', $post)->with('info', 'El post se elimino con exito');
    }
}
