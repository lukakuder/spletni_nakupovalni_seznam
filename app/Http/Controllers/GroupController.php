<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{

    /** Get all Groups **/
    public function index()
    {
        return view('groups.index', [
            'groups' => Group::all(),
        ]);
    }

    /** Get specific group **/
    public function show(Group $group)
    {
        return view('groups.show', [
            'group' => $group,
        ]);
    }

    /** Create bew group **/
    public function create()
    {
        return view('groups.create');
    }

}
