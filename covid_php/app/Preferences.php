<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Preferences extends Model
{
    protected $fillable = ['user_id', 'send_personal_mails', 'notify_prevention_measures', 'notify_vaccinations', 'notify_re_vaccinations'];
    protected $table = 'preferences';


    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
