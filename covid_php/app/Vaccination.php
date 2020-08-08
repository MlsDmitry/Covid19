<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vaccination extends Model
{
    //
    protected $fillable = ['user_id', 'vaccination', 're_vaccination'];
}
