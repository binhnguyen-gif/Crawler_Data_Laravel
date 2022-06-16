<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
    use HasFactory;
    protected $table = 'stories';
    protected $fillable = ['title', 'image','link', 'info', 'description'];


    public function chapter() {
        return $this->hasMany(Chapter::class);
    }
}
