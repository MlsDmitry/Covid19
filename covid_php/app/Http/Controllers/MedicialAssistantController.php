<?php

namespace App\Http\Controllers;

use App\Mail\NotifyReVaccination;
use App\Mail\NotifyVaccination;
use App\Vaccination;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class MedicialAssistantController extends Controller
{
    public function register(Request $request)
    {
        $date_time = $request->get('date_time');
        $on_hme = $request->get('on_home');
        if (boolval($on_hme)) {
            $addr = $request->get('addr');
        }
        $user = Auth::user();
        $pref = $user->preferences();
        $format = DateTime::format('m/d/Y h:i:s a');
        $scheduled = DateTime::createFromFormat($format, $date_time);
        if ($pref->notify_vaccinations == 1){
            $mail = new NotifyVaccination(['user' => $user]);
            Mail::to($user->email)->later($scheduled->diff($scheduled->sub(new \DateInterval('P1D'))), $mail);
        }
        if($pref->notify_re_vaccinations == 1) {
            $mail = new NotifyReVaccination(['user' => $user]);
            Mail::to($user->email)->later($scheduled->diff($scheduled->sub(new \DateInterval('P15D'))), $mail);
        }
        Vaccination::create([
            'user_id' => $user->id,
            'vaccination' => new DateTime($date_time),
            're_vaccination' => (new DateTime($date_time))->add(new \DateInterval('P15D'))
        ]);

    }

//    public function delete()

}

/*
 *
 * $(function(){
    var store = store || {};

    /*
     * Sets the jwt to the store object
     */
store.setJWT = function(data){
    this.JWT = data;
}

    /*
     * Submit the login form via ajax
     */
	$("#frmLogin").submit(function(e){
        e.preventDefault();
        $.post('auth/token', $("#frmLogin").serialize(), function(data){
            store.setJWT(data.JWT);
        }).fail(function(){
            alert('error');
        });
    });
});
 */
