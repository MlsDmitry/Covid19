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
        $scheduled = new DateTime($date_time);
        if ($pref->notify_vaccinations == 1){
            $mail = new NotifyVaccination(['user' => $user]);
            Mail::to($user->email)->later($scheduled->diff($scheduled->sub(new \DateInterval('P1D'))), $mail);
        }
        if($pref->notify_re_vaccinations == 1) {
            $mail = new NotifyReVaccination(['user' => $user]);
            Mail::to($user->email)->later($scheduled->diff($scheduled->sub(new \DateInterval('P1D'))), $mail);
        }
        Vaccination::create([
            'user_id' => $user->id,
            'vaccination' => new DateTime($date_time),
            're_vaccination' => (new DateTime($date_time))->add(new \DateInterval('P15D'))
        ]);

    }

//    public function delete()

}
