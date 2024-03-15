<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Node extends Model
{
    use HasFactory;

    public function nodeType(){
        return $this->belongsTo(NodeType::class, 'type_id');
    }
}
