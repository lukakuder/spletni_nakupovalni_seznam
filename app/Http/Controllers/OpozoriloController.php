<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Opozorilo;

// Seveda, potrebujete model za opozorila
use App\Events\OpozoriloOznacenoKotPrebrano;

class OpozoriloController extends Controller
{
    // Funkcija za prikaz opozoril
    public function index()
    {
        $opozorila = Opozorilo::where('user_id', Auth::id())->get();
        $neprebranaOpozorila = Opozorilo::where('user_id', Auth::id())
            ->where('prebrano', false)
            ->count();

        // Pošljite obe spremenljivki v pogled opozorila.index
        return view('opozorila.index', compact('opozorila', 'neprebranaOpozorila'));
    }


    public function oznaciPrebrano(Request $request)
    {
        $opozorilo = Opozorilo::where('id', $request->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($opozorilo) {
            $opozorilo->prebrano = true;
            $opozorilo->save();

            // Sproži dogodek
            event(new OpozoriloOznacenoKotPrebrano(Auth::user()));

            return redirect()->back()->with('success', 'Opozorilo označeno kot prebrano.');
        }

        return redirect()->back()->with('error', 'Opozorilo ni bilo najdeno.');
    }

    public function steviloNeprebranih()
    {
        $neprebranaOpozorila = Opozorilo::where('user_id', Auth::id())
            ->where('prebrano', false)
            ->count();

        return response()->json(['neprebranaOpozorila' => $neprebranaOpozorila]);
    }

}
