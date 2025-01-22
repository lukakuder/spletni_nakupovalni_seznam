<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class HomeController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function index()
    {
        $neprebranaOpozorila = Notification::where('user_id', Auth::id())
            ->where('prebrano', false)
            ->count();

        return view('dashboard', compact('neprebranaOpozorila'));
    }
}
