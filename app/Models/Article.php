<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = ['slug','title','user_id','description', 'excerpts', 'status','published_at','tag_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function files()
    {
        return $this->hasMany(ArticlesFile::class);
    }

    public function comments()
    {
        return $this->hasMany(ArticleComment::class);
    }
}
