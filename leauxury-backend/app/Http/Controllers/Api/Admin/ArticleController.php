<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    public function index()
    {
        return ArticleResource::collection(Article::with('images')->latest()->paginate(10));
    }

    public function store(StoreArticleRequest $request)
    {
        $data = $request->only(['title', 'excerpt', 'content', 'status']);
        $data['content'] = clean($data['content']);
        $data['slug'] = Article::generateUniqueSlug($request->title);

        if ($request->status === 'published') {
            $data['published_at'] = now();
        }

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('articles', 'public');
        }

        $article = Article::create($data);

        foreach ($request->file('images', []) as $image) {
            $path = $image->store('articles', 'public');
            $article->images()->create(['path' => $path]);
        }

        return new ArticleResource($article->load('images'));
    }

    public function show(Article $article)
    {
        return new ArticleResource($article->load('images'));
    }

    public function update(UpdateArticleRequest $request, Article $article)
    {
        $data = $request->only(['title', 'excerpt', 'content', 'status']);
        $data['content'] = clean($data['content']);

        if ($request->title !== $article->title) {
            $data['slug'] = Article::generateUniqueSlug($request->title, $article->id);
        }

        if ($request->status === 'published' && $article->status !== 'published') {
            $data['published_at'] = now();
        }

        if ($request->hasFile('thumbnail')) {
            if ($article->thumbnail) {
                Storage::disk('public')->delete($article->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('articles', 'public');
        }

        $article->update($data);

        foreach ($request->file('images', []) as $image) {
            $path = $image->store('articles', 'public');
            $article->images()->create(['path' => $path]);
        }

        return new ArticleResource($article->load('images'));
    }

    public function destroy(Article $article)
    {
        if ($article->thumbnail) {
            Storage::disk('public')->delete($article->thumbnail);
        }

        foreach ($article->images as $image) {
            Storage::disk('public')->delete($image->path);
        }

        $article->delete();

        return response()->json(['message' => 'Article deleted']);
    }
}