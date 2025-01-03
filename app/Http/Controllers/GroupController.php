<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Notifications\GroupCreatedNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Opozorilo;
use Illuminate\Support\Facades\Auth;

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
        // Validacija podatkov
        $request->validate([
            'name' => 'required|string|max:255|unique:groups,name',
            'description' => 'nullable|string',
        ]);

        // Preveri, ali skupina z istim imenom že obstaja
        $existingGroup = Group::where('name', $request->name)->first();

        if ($existingGroup) {
            return redirect()->back()->with('error', 'Skupina z tem imenom že obstaja.');
        }

        // Kreiraj skupino
        $group = new Group();
        $group->name = $request->name;
        $group->description = $request->description;
        $group->user_id = auth()->id();

        if ($request->tags) {
            $group->syncTags($request->tags);
        }

        $group->save();

        // Poveži prijavljenega uporabnika kot člana skupine
        $group->users()->attach(auth()->user()->id);

        // Dodaj opozorilo za trenutnega uporabnika
        Opozorilo::create([
            'user_id' => Auth::id(),
            'message' => 'Skupina "' . $group->name . '" je bila uspešno ustvarjena.',
            'prebrano' => false,
        ]);

        // Pošlji obvestilo z uporabo Notification sistema (če želiš)
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
