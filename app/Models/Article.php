<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
class Article extends Model
{
    use HasFactory;
    use Sluggable,SluggableScopeHelpers;
    protected $fillable = [
        'title',
        'slug',
        'content',
       ];

       

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
