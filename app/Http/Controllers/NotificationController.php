<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use App\Models\Group;
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


    /**
     * funkcija se sklicuje na tabelo opozoril uporabnika samo tabelo sortira in jo vrne.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function prikaziOpozorila()
    {
        $opozorila = Notification::where('user_id', Auth::id()) // Filtriranje po trenutnem uporabniku
        ->orderBy('prebrano', 'asc')//neprebrana na vrhu
            ->orderBy('created_at', 'desc')//od novejsih nazaj
            ->get();

        return view('opozorila.index', compact('opozorila'));
    }

    public function posljiPovabilo(Request $request, $groupId)
    {
        $userId = $request->input('user_id'); // ID uporabnika, ki ga povabite
        $group = Group::findOrFail($groupId); // Preverite, če skupina obstaja

        // Preverite, če opozorilo za to skupino že obstaja
        $obstaja = Notification::where('user_id', $userId)
            ->where('group_id', $groupId)
            ->where('prebrano', false)
            ->first();

        if ($obstaja) {
            return response()->json(['status' => 'error', 'message' => 'Uporabnik je že bil povabljen.']);
        }

        // Ustvarite novo opozorilo za povabilo
        Notification::createForUser($userId, "Ste povabljeni v skupino: {$group->name}", $groupId);

        return response()->json(['status' => 'success', 'message' => 'Povabilo poslano.']);
    }

    public function sprejmiPovabilo($notificationId)
    {
        $opozorilo = Notification::where('id', $notificationId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Preverite, ali je povabilo povezano s skupino
        if (!$opozorilo->group_id) {
            return redirect()->route('opozorila.index')
                ->with('error', 'Povabilo ni veljavno.');
        }

        // Dodajte uporabnika v skupino
        $group = $opozorilo->group;
        $group->users()->attach(Auth::id());

        // Označite opozorilo kot prebrano
        $opozorilo->prebrano = true;
        $opozorilo->save();

        return redirect()->route('groups.show', $group->id)
            ->with('success', "Pridružili ste se skupini '{$group->name}'.");
    }



    public function steviloNeprebranih()
    {
        $neprebranaOpozorila = Notification::where('user_id', Auth::id())
            ->where('prebrano', false)
            ->count();

        return response()->json(['neprebranaOpozorila' => $neprebranaOpozorila]);
    }
}
