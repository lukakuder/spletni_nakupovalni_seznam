<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Notifications\GroupCreatedNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\User;

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
            'name' => 'required|string|max:255|unique:groups,name', // Dodano: preverjanje unikatnosti imena
            'description' => 'nullable|string',
        ]);

        // Preverite, ali skupina z istim imenom že obstaja
        $existingGroup = Group::where('name', $request->name)->first();

        if ($existingGroup) {
            return redirect()->back()->with('error', 'Skupina z tem imenom že obstaja.');
        }

        // Create and save the new group
        $group = new Group();
        $group->name = $request->name;
        $group->description = $request->description;
        $group->save(); // Save the group first to get the ID

        // Attach the authenticated user as a member of the group
        $group->users()->attach(auth()->user()->id); // Attach the current user as a member

        // Send notification to the user who created the group
        auth()->user()->notify(new GroupCreatedNotification($group));

        return redirect()->route('user.groups')->with('success', 'Skupina uspešno ustvarjena!');
    }

    public function addMembersForm($groupId)
    {
        $group = Group::findOrFail($groupId);
        $users = User::all(); // Pridobite vse uporabnike, ki jih lahko dodate v skupino

        return view('groups.add-members', compact('group', 'users'));
    }
    public function addMembers(Request $request, $groupId)
    {
        $group = Group::findOrFail($groupId);

        // Preverite, ali so uporabniki izbrani
        $request->validate([
            'users' => 'required|array',
            'users.*' => 'exists:users,id',
        ]);

        // Dodajte izbrane uporabnike v skupino
        $group->users()->attach($request->users);

        return redirect()->route('groups.show', $group->id)->with('success', 'Člani so bili uspešno dodani!');
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
        $group->updated_at = $request->updated_at;
        $group->save();

        return redirect()->route('user.groups')->with('success', 'Group updated successfully!');
    }

    public function getGroupShoppingLists($groupId)
    {
        $group = Group::findOrFail($groupId);

        // function from the model
        return $group->getShoppingLists();
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
