<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleComment extends Model
{
    protected $table = 'article_comments';
    protected $fillable = ['user_id','article_id','content', 'commented_at'];
    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
