<?php

namespace App\Http\Controllers\Article;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticlesFile;
use App\Http\Resources\ArticleResource;
use Image;
class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ArticleResource::collection(Article::with('files')->paginate(25));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:200',
            'description' => 'required|max:20000',
            'excerpts' => 'required|max:400',
        ]);
        // var_dump($request->user()->id);die;

        $article = Article::create([
            'slug' => str_slug($request->title, '-'),
            'user_id' => $request->user()->id,
            'tag_id' => 1,
            'status' => 0,
            'title' => $request->title,
            'description' => $request->description,
            'published_at'=> date("Y-m-d H:i:s"),
            'excerpts' => $request->excerpts,
        ]);
        
        //Upload a file
        if($request->hasFile('blog_file'))
        {
            $image = $request->file('blog_file');
            $fileName = time().'.'.$image->getClientOriginalName();
            $location = public_path('images/'.$fileName);
            Image::make($image)->resize(800,400)->save($location);

            $file = ArticlesFile::create([
                'user_id' => $request->user()->id,
                'article_id' => $article->id,
                'file_name' => $fileName,
                'file_path' => $location,
            ]);
        }
        

        return new ArticleResource($article);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        return new ArticleResource($article);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Article $article)
    {
        // check if currently authenticated user is the owner of the book
        if ($request->user()->id !== $article->user_id) {
            return response()->json(['error' => 'You can only edit your own books.'], 403);
        }

        $article->update($request->only(['title', 'description']));

        return new ArticleResource($article);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        $article->delete();

        return response()->json(null, 204);
    }
}
