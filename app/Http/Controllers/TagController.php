<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\ShoppingList;
use Illuminate\Contracts\View\View;
use Spatie\Tags\Tag;

class TagController extends Controller
{
    /**
     * Returns the view that displays all the lists and groups with the given tag.
     *
     * @param $id
     * @return View
     */
    public function index($id): View
    {
        $tag = Tag::find($id);

        return view('components.tags.show', [
            'tag' => $tag,
            'lists' => ShoppingList::withAnyTags($tag->slug)->get(),
            'groups' => Group::withAnyTags($tag->slug)->get(),
        ]);
    }
}
