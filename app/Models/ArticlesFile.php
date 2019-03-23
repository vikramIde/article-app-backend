<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticlesFile extends Model
{
    protected $table = 'article_files';
    protected $fillable = ['user_id','article_id','file_name', 'file_path'];

    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
