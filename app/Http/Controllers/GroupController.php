<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Notifications\GroupCreatedNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Spatie\Tags\Tag;

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
    public function detailedShow($groupId)
    {
        $group = Group::find($groupId); // Predpostavljam, da imaš model Group
        return view('groups.detailedShow', compact('group'));
    }

    /**
     * Returns the view for creating a new group.
     *
     * @return View
     */
    public function create(): View
    {
        return view('groups.create', [
            'tags' => Tag::all(),
        ]);
    }

    /**
     * Displays the view of the specified group.
     *
     * @param $id
     * @return View
     */
    public function show($id): View
    {
        $group = Group::findOrFail($id);

        return view('groups.show', [
            'group' => $group,
        ]);
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
        //$group->user_id = auth()->id();
        $group->save();

        if ($request->tags) {
            $group->syncTags($request->tags);
        }

        // Poveži prijavljenega uporabnika kot člana skupine
        $group->users()->attach(auth()->user()->id);

        // Dodaj opozorilo za trenutnega uporabnika
        Notification::create([
            'user_id' => Auth::id(),
            'message' => 'Skupina "' . $group->name . '" je bila uspešno ustvarjena.',
            'prebrano' => false,
        ]);

        // Pošlji obvestilo z uporabo Notification sistema (če želiš)
        auth()->user()->notify(new GroupCreatedNotification($group));

        return redirect()->route('user.groups')->with('success', 'Skupina uspešno ustvarjena!');
    }
    public function leave(Request $request, Group $group)
    {
        // Pridobi trenutno prijavljenega uporabnika
        $userId = auth()->id();

        if (!$userId) {
            return redirect()->back()->with('error', 'Niste prijavljeni.');
        }

        // Preveri, ali je uporabnik član skupine
        if (!$group->users()->where('users.id', $userId)->exists()) {
            return redirect()->back()->with('error', 'Niste član te skupine.');
        }

        // Odstrani uporabnika iz skupine
        $group->users1()->detach($userId);

        return redirect()->route('groups.index')->with('success', 'Uspešno ste zapustili skupino.');
    }


    public function addMembersForm($groupId)
    {
        $group = Group::findOrFail($groupId);
        $users = User::where('allow_group_invites', true)->get();

        return view('groups.add-member', compact('group', 'users'));
    }
    public function addMembers(Request $request, $groupId)
    {
        $group = Group::findOrFail($groupId);

        // Preverite, ali so uporabniki izbrani
        $request->validate([
            'users' => 'required|array',
            'users.*' => 'exists:users,id',
        ]);

        // Pošljite povabila izbranim uporabnikom
        $usersToInvite = $request->users;

        foreach ($usersToInvite as $userId) {
            // Preverite, ali je povabilo že bilo poslano
            $existingNotification = Notification::where('user_id', $userId)
                ->where('group_id', $group->id)
                ->where('prebrano', false)
                ->first();

            if (!$existingNotification) {
                Notification::createForUser(
                    $userId,
                    "Prejeli ste povabilo, da se pridružite skupini '{$group->name}'. Kliknite, da sprejmete povabilo.",
                    $group->id
                );
            }
        }

        return redirect()->route('groups.show', $group->id)
            ->with('success', 'Povabila so bila uspešno poslana!');
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
