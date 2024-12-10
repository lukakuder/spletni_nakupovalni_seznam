<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GroupController extends Controller
{
    /**
     * Displays all groups that the user is a part of.
     *
     * @return View
     */
    public function index(): View
    {
        // Retrieve the groups that the authenticated user belongs to
        $groups = auth()->user()->groups; // Assuming there's a 'groups' relationship on the User model

        return view('user.groups', compact('groups'));
    }

    /**
     * Returns the view for creating a new group.
     *
     * @return View
     */
    public function create(): View
    {
        return view('groups.create');
    }

    /**
     * Stores the new group in the database.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate the group data
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Create and save the new group
        $group = new Group();
        $group->name = $request->name;
        $group->description = $request->description;
        $group->user_id = auth()->id();  // Store the creator as the group owner
        $group->save();

        return redirect()->route('user.groups')->with('success', 'Group created successfully!');
    }

    /**
     * Show the form for editing the specified group.
     *
     * @param Group $group
     * @return View
     */
    public function edit(Group $group): View
    {
        // Ensure the user owns the group (or has permission to edit)
        $this->authorize('update', $group);

        return view('user.edit-group', compact('group'));
    }

    /**
     * Update the specified group in the database.
     *
     * @param Request $request
     * @param Group $group
     * @return RedirectResponse
     */
    public function update(Request $request, Group $group): RedirectResponse
    {
        // Ensure the user has permission to update the group
        $this->authorize('update', $group);

        // Validate and update the group data
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $group->name = $request->name;
        $group->description = $request->description;
        $group->save();

        return redirect()->route('user.groups')->with('success', 'Group updated successfully!');
    }

    /**
     * Delete the specified group from the database.
     *
     * @param Group $group
     * @return RedirectResponse
     */
    public function destroy(Group $group): RedirectResponse
    {
        // Ensure the user has permission to delete the group
        $this->authorize('delete', $group);

        $group->delete();

        return redirect()->route('user.groups')->with('success', 'Group deleted successfully!');
    }
}
