<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Technology;
use Illuminate\Support\Facades\Auth;
use App\Models\Type;
use Illuminate\Support\Facades\Storage;
use App\Mail\NewContact;
use App\Models\Lead;
use Illuminate\Support\Facades\Mail;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::all();

        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $types = Type::all();
        $technologies = Technology::all();

        return view('admin.posts.create', compact('types', 'technologies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePostRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePostRequest $request)
    {
        $form_data = $request->validated();

        $slug = Post::generateSlug($request->title);

        $form_data['slug'] = $slug;
        
        if ($request->hasFile('cover_image')) {
            $path = Storage::disk('public')->put('post_images', $request->cover_image);

            $form_data['cover_image'] = $path;
        }

        $newPost = Post::create($form_data);

        if($request->has('technologies')) {
            $newPost->technoligies()->attach($request->technologies);
        }

        $new_lead = new Lead();

        $new_lead->title  = $form_data['title'];
        $new_lead->content  = $form_data['content'];
        $new_lead->slug  = $form_data['slug'];

        $new_lead->save();

        Mail::to('info@boolpress.com')->send(new NewContact($new_lead));

        return redirect()->route('admin.posts.show', ['post' => $newPost['slug']])->with('message', 'Post creato correttamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return view('admin.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        $types = Type::all();
        $technologies = Technology::all();

        return view('admin.posts.edit', compact('post', 'types', 'technologies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePostRequest  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $form_data = $request->validated();

        $slug = Post::generateSlug($request->title);

        $form_data['slug'] = $slug;

        if ($request->hasFile('cover_image')) {

            if ($post->cover_image) {
                Storage::delete($post->cover_image);
            } 

            $path = Storage::disk()->put('post_images', $request->cover_image);

            $form_data['cover_image'] = $path;
        }

        $post->update($form_data);

        if($request->has('technologies')) {
            $post->technoligies()->sync($request->technologies);
        }

        return redirect()->route('admin.posts.show', ['post' => $post['slug']])->with('message', 'Post modificato correttamente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        // $post->technoligies()->sync([]);

        $post->delete();

        return redirect()->route('admin.posts.index')->with('message', 'Post cancellato correttamente');
    }
}
