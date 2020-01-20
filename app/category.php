<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class category extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function toDoList(){
        return $this->hasMany('App\todolist', 'category_id', 'id')->orderBy('checked', 'asc')->orderBy('due_on', 'asc');
    }
}
