<?php

namespace App\Http\Controllers;

use App\Models\ShoppingList;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ListController extends Controller
{
    /**
     * Returns the view to the list creation form
     *
     * @param Request $request
     * @return View
     */
    public function create(Request $request): View
    {
        return view('lists.create', [
            'belongs_to_a_group' => $request->input('belongs_to_a_group', 0),
        ]);
    }

    /**
     * Stores the list in the database and redirects to the users lists
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'belongs_to_a_group' => 'required|boolean',
            'group_id' => 'nullable|exists:groups,id',
        ]);

        // Create the new list
        $list = new ShoppingList();
        $list->name = $request->name;
        $list->description = $request->description;

        // Checks if it should fill the user_id or group_id
        if ($request->belongs_to_a_group) {
            $list->group_id = $request->group_id;
            $list->user_id = null;
        } else {
            $list->group_id = null;
            $list->user_id = auth()->id();
        }

        $list->belongs_to_a_group = $request->belongs_to_a_group;
        $list->save();

        return redirect()->route('user.lists')->with('success', 'List created successfully!');
    }
}
