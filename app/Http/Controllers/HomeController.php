<?php

namespace App\Http\Controllers;

use App\todolist;
use Illuminate\Http\Request;
use App\category;
use Auth;
use UxWeb\SweetAlert\SweetAlert;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $user = Auth::User();

        $data = $request->all();

        $search = isset($data['search']) ? '%' . $data['search'] . '%': '%%';
        $search_raw =  isset($data['search']) ? $data['search']: '';

        $today = todolist::with('category')
                                        ->where([['user_id', $user->id], ['due_on' , '<=' ,  Carbon::now()->endOfDay()]])
                                        ->orderBy('checked', 'asc')->orderBy('due_on', 'asc')
                                        ->whereHas('category', function ($query2) use ($search) {
                                            $query2->where('name','like', $search);
                                        })
                                        ->get();

        $tomorrow = todolist::with('category')
            ->where([['user_id', $user->id]])
            ->whereBetween('due_on', [Carbon::now()->addDay()->startOfDay(), Carbon::now()->addDay()->endOfDay()])
            ->orderBy('checked', 'asc')->orderBy('due_on', 'asc')
            ->whereHas('category', function ($query2) use ($search) {
                $query2->where('name','like', $search);
            })
            ->get();

        $other = todolist::with('category')
            ->where([['user_id', $user->id], ['due_on' , '>' ,  Carbon::now()->addDay()->startOfDay()]])
            ->orderBy('checked', 'asc')->orderBy('due_on', 'asc')
            ->whereHas('category', function ($query2) use ($search) {
                    $query2->where('name','like', $search);
                })
            ->get();
        return view('home',compact('today','tomorrow','other', 'search_raw'));
    }
}
