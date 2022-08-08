
# Simple News CMS (Laravel + EditorJS) 

News CMS where clients can create articles with GIF images.


![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-323330?style=for-the-badge&logo=javascript&logoColor=F7DF1E)
![NodeJS](https://img.shields.io/badge/Node.js-339933?style=for-the-badge&logo=nodedotjs&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)

## Features

- User Authentication (Log in/ Sign up Pages).
- Create new articles,with title and content.
- List Added Articles.
- create unique slug for each article.
- Show any article by slug for all users and guests.
- Editor.js based content editor.
- GIF support for content creation using custom plugin.
- Search for GIF Images with Multiple insertions.




## API Reference

#### Tenor GIF API is used to fetch GIF images based on certian criteria

```https
  GET https://g.tenor.com/v1/search
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `key` | `string` | **Required**. API key |
| `q` | `string` | **Required**. search term |
| `limit` | `int` | **Optional**. search result limit |

Axios (HTTP Client for node.js) is used to perform calls


## Deployment

DB migration is needed first

```bash
  php artisan migrate
```

To deploy this project run

```bash
  npm run build
```


## Documentation

### Articles CRUD

#### Laravel Breeze is used to create all aspects of User Authentication.

#### Artcile Migration was build using below schema (MySQL DB)

```php
  Schema::create('articles', function (Blueprint $table) {
      $table->id();
      $table->string('title');
      $table->string('slug'); 
      $table->text('content');
      $table->timestamps();
  });
    
```    
#### Slug created using "Cviebrock\EloquentSluggable" Library

```php
class Article extends Model
{
  use HasFactory;
  use Sluggable,SluggableScopeHelpers;
  protected $fillable = ['title','slug','content'];      

  public function sluggable(): array
  {
      return [
          'slug' => [
              'source' => 'title'
          ]
      ];
  }

  public function getRouteKeyName(): string
  {
      return 'slug';
  }
}
```

and when article creation

```php
$insert = [
    'slug' => SlugService::createSlug(Article::class, 'slug', $request->title),
    'title' => $request->title,
    'content' => $request->content,
];
```

#### ArticleController.php contains all Article CRUD methods

```php
  public function index()
  {
      $data['articles'] = Article::orderBy('id','desc')->paginate(10);
      return view('article.list',$data);
  }

  public function create()
  {
      return view('article.create');
  }

  public function edit($id)
  {   
      return view('article.create',['article'=>Article::find($id)]);
  }

  public function destroy($id)
  {
      $a=Article::find($id);
      $a->delete();
      return Redirect::to('admin/articles');
  }

  public function store(Request $request)
  {       
    $request->validate([
        'title' => 'required',
        'content' => 'required',
    ]);
    $insert = [
        'slug' => SlugService::createSlug(Article::class, 'slug', $request->title),
        'title' => $request->title,
        'content' => $request->content,
    ];

    Article::insertGetId($insert);    
    return Redirect::to('admin/articles');
  }


  public function update(Request $request, $id)
  {
    $request->validate([
        'title' => 'required',
        'content' => 'required',
    ]);
    $article = Article::find($id);
    $article->title = $request['title'];
    $article->slug = SlugService::createSlug(Article::class, 'slug', $request->title);
    $article->content = $request['content'];
    $article->save();        
    return Redirect::to('admin/articles');
  }

```

#### Corresponding Views Calls those Methds by thire Routes
Views can be found at resources/views/article

### Editor.js

#### Base Editor is refernced as CDN link, then Component is added to Article creation Page

```javascript
<script src="https://cdn.jsdelivr.net/npm/@editorjs/editorjs@latest"></script>
```

#### Editor.js is extented via custom plugin 'GifImage' using methods:
- render(): creates custom '<div>' containing search input and result GIF gallery,multi-pick selection is implemented and insert button will leave only selected GIFs in Editor current Block.
- save(blockContent): saves block data (GIFs URLs) as Article 'content' field.

#### javascript is used to implement both methods. 

#### File resources/views/article/create.blade.php contatins all custom GIF plugin code (only page using it).
## Usage

Login Page is Served by 
```url
/admin/login
```
Login Page is Served by 
```url
/admin/register
```
Articles List Page is Served by 
```url
/admin/articles
```

Clicking on any article entry will show its content.
This URL is based on article slug

Edit Button is used to edit every Article entry.
Delete Button is used to delete every Article entry after confirmation.

Add Article Button is used to create new Article.

#### Full routes map can be found in 'auth.php' and 'web.php' files.

# One Last GIF as A Demo 
![](https://github.com/ExceedLimits/NewsifierTestCMS/blob/master/screen-capture.gif)




