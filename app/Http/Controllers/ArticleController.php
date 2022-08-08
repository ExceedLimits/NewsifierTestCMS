<?php

namespace App\Http\Controllers;
   
use App\Models\Article;
 
use Illuminate\Http\Request;
 
use Redirect;
 
use Cviebrock\EloquentSluggable\Services\SlugService;


class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['articles'] = Article::orderBy('id','desc')->paginate(10);
        return view('article.list',$data);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('article.create');
    }
   
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
        $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);
        //dd($request);
        $insert = [
            'slug' => SlugService::createSlug(Article::class, 'slug', $request->title),
            'title' => $request->title,
            'content' => $request->content,
        ];

        Article::insertGetId($insert);
    
        return Redirect::to('admin/articles');
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\article $article
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
         
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\article $article
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        return view('article.create',['article'=>Article::find($id)]);
    }
   
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\article $article
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);
        //dd($request);

        $article = Article::find($id); 
        $article->title = $request['title'];
        $article->slug = SlugService::createSlug(Article::class, 'slug', $request->title);
        $article->content = $request['content'];
        $article->save();    
    
        return Redirect::to('admin/articles');
    }
   
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $a=Article::find($id);
        $a->delete();
        return Redirect::to('admin/articles');
    }
}
