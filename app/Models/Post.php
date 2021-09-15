<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    const BORRADOR = 1;
    const PUBLICADO = 2;

    // RELACION UNO A MUCHOS INVERSA
    public function user(){
        $this->belongsTo(User::class);
    }

    public function category(){
        $this->belongsTo(Category::class);
    }

    // RELACION MUCHOS A MUCHOS
    public function tags(){
        $this->belongsToMany(Tag::class);
    }

    // RELACION UNO A MUCHOS POLIMORFICA
    public function images(){
        return $this->morphMany(Image::class, 'imageable');
    }
}
