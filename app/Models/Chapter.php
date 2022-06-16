<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Chapter extends Model
{
    use HasFactory;
    protected $table = 'chapters';
    protected $fillable = ['stories_id', 'title', 'link', 'description'];

    public function story() {
        return $this->belongsTo(Story::class);
    }

    public function getStoryAttribute() {
        DB::table('stories')->join('chapters', 'stories.id', '=', 'chapters..stories_id')->select('stories.title', 'chapters.title', 'chapter.link', 'chapters.description')->get();
    }
}
