<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Stamp;
use App\Models\Rest;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StampController extends Controller
{

    public function home()
    {
        return view('home');
    }

    public function index()
    {
        $user = Auth::user();
        $old_stamp = Stamp::where('user_id',$user->id)->latest()->first();

        return view('stamp', compact('old_stamp'));
    }

    //出勤アクション
    public function timeIn()
    {
        $user = Auth::user();
        $old_stamp = Stamp::where('user_id',$user->id)->latest()->first();

        //同じ日に2回出勤が押せない,退勤前に出勤を2度押せない制御,退勤後に再度出勤を押せない制御
        if(!empty($old_stamp)) {
            $old_created_at = new Carbon($old_stamp->created_at);
            $old_stamp_day = $old_created_at->startOfDay();
            $today = Carbon::today();
            if(($old_stamp_day == $today) && (!empty($old_stamp->work_start_at)) && (!empty($old_stamp->work_end_at))) {
                return redirect()->back()->with('message','退勤打刻済みです');
            }elseif(($old_stamp_day == $today) && (!empty($old_stamp->work_start_at)) && (empty($old_stamp->work_end_at))) {
                return redirect()->back()->with('message','出勤打刻済みです');
            }else{
                Stamp::create([
                    'user_id' => $user->id,
                    'stamp_date' => Carbon::today(),
                    'work_start_at' => Carbon::now(),
                ]);
            }
        }else{
                Stamp::create([
                    'user_id' => $user->id,
                    'stamp_date' => Carbon::today(),
                    'work_start_at' => Carbon::now(),
                ]);
        }

        return redirect('/')->with('message','おはようございます');
    }

    //休憩開始アクション
    public function restIn() {
        $user = Auth::user();
        $old_stamp = Stamp::where('user_id',$user->id)->latest()->first();

        if(!empty($old_stamp)) {
            $old_created_at = new Carbon($old_stamp->created_at);
            $old_stamp_day = $old_created_at->startOfDay();
            $today = Carbon::today();
        }else{
            return redirect()->back();
        }

        if(($old_stamp_day == $today) && !empty($old_stamp->work_start_at) && empty($old_stamp->work_end_at)) {
            if(empty($old_stamp->rest_id)) {
                $rest = Rest::create([
                    'rest_start_at' => Carbon::now()
                ]);

                $old_stamp->update([
                    'rest_id' => $rest->id
                ]);
            }elseif(!empty($old_stamp->rest->rest_start_at) && empty($old_stamp->rest->rest_end_at)) {
                return redirect()->back();
            }elseif((!empty($old_stamp->rest->rest_start_at) && !empty($old_stamp->rest->rest_end_at)) && (($old_stamp->rest->rest_start_at) < ($old_stamp->rest->rest_end_at))) {
                $old_stamp->rest->update([
                    'rest_start_at' => Carbon::now()
                ]);
            }else{
                return redirect()->back();
            }
        }else{
            return redirect()->back();
        }
        return redirect('/')->with('message','休憩を開始しました');
    }

    //休憩終了アクション
    public function restOut() {
        $user = Auth::user();
        $old_stamp = Stamp::where('user_id',$user->id)->latest()->first();

        if(!empty($old_stamp)) {
            $old_created_at = new Carbon($old_stamp->created_at);
            $old_stamp_day = $old_created_at->startOfDay();
            $today = Carbon::today();
        }else{
            return redirect()->back();
        }

        if(($old_stamp_day == $today) && !empty($old_stamp->work_start_at) && empty($old_stamp->work_end_at)) {
            if(empty($old_stamp->rest_id)) {
                return redirect()->back();
            }elseif(!empty($old_stamp->rest->rest_start_at) && empty($old_stamp->rest->rest_end_at)) {
                $rest_start_at = new Carbon($old_stamp->rest->rest_start_at);
                $rest_end_at = Carbon::now();
                $rest_time_seconds = $rest_start_at->diffInSeconds($rest_end_at);
                $old_rest_time = new Carbon('00:00:00');
                $rest_time = $old_rest_time->addSeconds($rest_time_seconds);

                $old_stamp->rest->update([
                    'rest_end_at' => Carbon::now(),
                    'rest_time' => $rest_time,
                ]);
            }elseif((!empty($old_stamp->rest->rest_start_at) && !empty($old_stamp->rest->rest_end_at)) && (($old_stamp->rest->rest_start_at) > ($old_stamp->rest->rest_end_at))) {
                $rest_start_at = new Carbon($old_stamp->rest->rest_start_at);
                $rest_end_at = Carbon::now();
                $rest_time_seconds = $rest_start_at->diffInSeconds($rest_end_at);
                $old_rest_time = new Carbon($old_stamp->rest->rest_time);
                $rest_time = $old_rest_time->addSeconds($rest_time_seconds);

                $old_stamp->rest->update([
                    'rest_end_at' => Carbon::now(),
                    'rest_time' => $rest_time,
                ]);
            }elseif((!empty($old_stamp->rest->rest_start_at) && !empty($old_stamp->rest->rest_end_at)) && (($old_stamp->rest->rest_start_at) < ($old_stamp->rest->rest_end_at))) {
                return redirect()->back();
            }else{
                return redirect()->back();
            }
        }else{
            return redirect()->back();
        }

        return redirect('/')->with('message','休憩を終了しました');
    }

    //退勤アクション
    public function timeOut() {
        $user = Auth::user();
        $old_stamp = Stamp::where('user_id',$user->id)->latest()->first();

        if(!empty($old_stamp)) {
            $old_created_at = new Carbon($old_stamp->created_at);
            $old_stamp_day = $old_created_at->startOfDay();
            $today = Carbon::today();
        }else{
            return redirect()->back();
        }

        if(($old_stamp_day == $today) && !empty($old_stamp->work_start_at) && empty($old_stamp->work_end_at)) {
            $now = new Carbon();
            $work_start_at = new Carbon($old_stamp->work_start_at);

            $stay_time_seconds = $work_start_at->diffInSeconds($now);
            $hours = floor($stay_time_seconds  / 3600);
            $minutes = floor(($stay_time_seconds  % 3600) / 60);
            $seconds = $stay_time_seconds  % 60;
            $stay_time = new Carbon($hours.":".$minutes.":".$seconds);

            if((!empty($old_stamp->rest->rest_start_at) && !empty($old_stamp->rest->rest_end_at)) && (($old_stamp->rest->rest_start_at) < ($old_stamp->rest->rest_end_at))) {
                $rest_time = new Carbon($old_stamp->rest->rest_time);
                $work_time_seconds = $stay_time->diffInSeconds($rest_time);
                $hours = floor($work_time_seconds  / 3600);
                $minutes = floor(($work_time_seconds  % 3600) / 60);
                $seconds = $work_time_seconds  % 60;
                $work_time = new Carbon($hours.":".$minutes.":".$seconds);
            }elseif(((!empty($old_stamp->rest->rest_start_at) && !empty($old_stamp->rest->rest_end_at)) && (($old_stamp->rest->rest_start_at) > ($old_stamp->rest->rest_end_at))) || (!empty($old_stamp->rest->rest_start_at) && empty($old_stamp->rest->rest_end_at))) {
                return redirect()->back();
            }else{
                $work_time = $stay_time;
            }
            $old_stamp->update([
                'work_end_at' => Carbon::now(),
                'work_time' => $work_time
            ]);
        }else{
            return redirect()->back();
        }
        return redirect('/')->with('message','お疲れ様でした');
    }

    public function attendance()
    {
        $stamps = Stamp::all();
        if(!empty($stamps)) {
            $stamp_day = Carbon::today();
            $stamps = Stamp::where('stamp_date', $stamp_day)->paginate(5);

            return view('attendance', compact('stamps', 'stamp_day'));
        }
    }

    public function daySearch(Request $request)
    {
        if($request->has('prev_stamp_day')) {

            $stamp_day = new Carbon($request->prev_stamp_day);
            $stamp_day->subDay();
            $stamps = Stamp::where('stamp_date', $stamp_day)->paginate(5);
            $search = request()->input('prev_stamp_day');

            return view('attendance', compact('stamp_day', 'stamps', 'search'));
        }

        if($request->has('next_stamp_day')) {
            $stamp_day = new Carbon($request->next_stamp_day);
            $stamp_day->addDay();
            $stamps = Stamp::where('stamp_date', $stamp_day)->paginate(5);
            $search = request()->input('next_stamp_day');

            return view('attendance', compact('stamp_day', 'stamps', 'search'));
        }
    }

    public function user()
    {
        $users = User::paginate(10);

        return view('user', compact('users'));
    }

    public function userSearch(Request $request)
    {
        $user = User::find($request->id);
        $stamps = Stamp::where('user_id', $user->id)->paginate(5);
        $search = request()->input('id');

        return view('user_attendance', compact('user', 'stamps', 'search'));
    }

    public function switch(Request $request) {

    $time = request()->get('time');
    $oneSecondAgo = new Carbon($time);
    $oneSecondAgo->subSeconds(1);

    $user = Auth::user();
    $old_stamp = Stamp::where('user_id',$user->id)->latest()->first();

    // 翌日の出勤操作に切り替える処理
    if (($time == "0:0:0") && !empty($old_stamp->work_start_at) && empty($old_stamp->work_end_at)) {
            $work_end_at = new Carbon($oneSecondAgo);
            $work_start_at = new Carbon($old_stamp->work_start_at);

            $stay_time_seconds = $work_start_at->diffInSeconds($work_end_at);
            $hours = floor($stay_time_seconds  / 3600);
            $minutes = floor(($stay_time_seconds  % 3600) / 60);
            $seconds = $stay_time_seconds  % 60;
            $stay_time = new Carbon($hours.":".$minutes.":".$seconds);

            if(((!empty($old_stamp->rest->rest_start_at) && !empty($old_stamp->rest->rest_end_at))) && (($old_stamp->rest->rest_start_at) < ($old_stamp->rest->rest_end_at))) {
                $rest_time = new Carbon($old_stamp->rest->rest_time);
                $work_time_seconds = $stay_time->diffInSeconds($rest_time);
                $hours = floor($work_time_seconds  / 3600);
                $minutes = floor(($work_time_seconds  % 3600) / 60);
                $seconds = $work_time_seconds  % 60;
                $work_time = new Carbon($hours.":".$minutes.":".$seconds);

                $old_stamp->update([
                    'work_end_at' => $work_end_at,
                    'work_time' => $work_time
                ]);

                // 翌日の出勤処理
                Stamp::create([
                'user_id' => $user->id,
                'stamp_date' => Carbon::today(),
                'work_start_at' => $time,
                ]);
            }elseif(((!empty($old_stamp->rest->rest_start_at) && !empty($old_stamp->rest->rest_end_at)) && (($old_stamp->rest->rest_start_at) > ($old_stamp->rest->rest_end_at))) || (!empty($old_stamp->rest->rest_start_at) && empty($old_stamp->rest->rest_end_at))) {
                $rest_start_at = new Carbon($old_stamp->rest->rest_start_at);
                $rest_end_at = new Carbon($oneSecondAgo);
                $rest_time_seconds = $rest_start_at->diffInSeconds($rest_end_at);
                if(empty($old_stamp->rest->rest_time)) {
                    $old_rest_time = new Carbon("00:00:00");
                    $rest_time = $old_rest_time->addSeconds($rest_time_seconds);
                }else{
                    $old_rest_time = new Carbon($old_stamp->rest->rest_time);
                    $rest_time = $old_rest_time->addSeconds($rest_time_seconds);
                }

                $rest_time = new Carbon($rest_time);
                $work_time_seconds = $stay_time->diffInSeconds($rest_time);
                $hours = floor($work_time_seconds  / 3600);
                $minutes = floor(($work_time_seconds  % 3600) / 60);
                $seconds = $work_time_seconds  % 60;
                $work_time = new Carbon($hours.":".$minutes.":".$seconds);

                $old_stamp->rest->update([
                    'rest_end_at' => $rest_end_at,
                    'rest_time' => $rest_time,
                ]);

                $old_stamp->update([
                    'work_end_at' => $work_end_at,
                    'work_time' => $work_time
                ]);

                // 翌日の休憩開始処理
                $rest = Rest::create([
                    'rest_start_at' => $time,
                ]);

                // 翌日の出勤処理
                Stamp::create([
                'user_id' => $user->id,
                'rest_id' => $rest->id,
                'stamp_date' => Carbon::today(),
                'work_start_at' => $time,
                ]);
            }else{
                $work_time = $stay_time;

                $old_stamp->update([
                    'work_end_at' => $work_end_at,
                    'work_time' => $work_time
                ]);

                // 翌日の出勤処理
                Stamp::create([
                'user_id' => $user->id,
                'stamp_date' => Carbon::today(),
                'work_start_at' => $time,
                ]);
            }
    }

    return response()->json(['time' => $time]);
    }
}