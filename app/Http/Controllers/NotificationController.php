<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

// Seveda, potrebujete model za opozorila
use App\Events\NotificationMarkedAsRead;

class NotificationController extends Controller
{
    // Funkcija za prikaz opozoril
    public function index()
    {
        $opozorila = Notification::where('user_id', Auth::id())->get();
        $neprebranaOpozorila = Notification::where('user_id', Auth::id())
            ->where('prebrano', false)
            ->count();

        // Pošljite obe spremenljivki v pogled opozorila.index
        return view('opozorila.index', compact('opozorila', 'neprebranaOpozorila'));
    }


    public function oznaciPrebrano(Request $request)
    {
        $opozorilo = Notification::where('id', $request->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($opozorilo) {
            $opozorilo->prebrano = true;
            $opozorilo->save();

            return response()->json(['status' => 'success']);
        }

        return response()->json(['status' => 'error', 'message' => 'Notification ni bilo najdeno.']);
    }


    public function steviloNeprebranih()
    {
        $neprebranaOpozorila = Notification::where('user_id', Auth::id())
            ->where('prebrano', false)
            ->count();

        return response()->json(['neprebranaOpozorila' => $neprebranaOpozorila]);
    }
}
