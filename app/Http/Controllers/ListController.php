<?php

namespace App\Http\Controllers;

use App\Models\ShoppingList;
use App\Models\ListItem;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Response;

class ListController extends Controller
{
    /**
     * Display the specified shopping list.
     *
     * @param int $id
     * @return View
     */
    public function show($id)
    {
        $list = ShoppingList::where('id', $id)
            ->where('user_id', auth()->id())
            ->with('items') // Use -> here instead of ::
            ->findOrFail($id);

        return view('lists.show', compact('list'));
    }


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

    /**
     * takes and validates item from Add Item form and stores it in the database
     *
     * @param Request $request, int $id
     * @return RedirectResponse
     */
    public function storeItem(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|integer|min:1',
            'price_per_item' => 'nullable|numeric|min:0',
        ]);

        $list = ShoppingList::findOrFail($id);

        $list->items()->create([
            'name' => $request->name,
            'amount' => $request->amount,
            'price_per_item' => $request->price_per_item,
            'total_price' => $request->amount * $request->price_per_item,
        ]);

        return redirect()->route('lists.show', $id)->with('success', 'Item added successfully!');
    }

    /**
     * Exports the shopping list to a text file
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function export(int $id): \Illuminate\Http\Response
    {
        $list = ShoppingList::where('id', $id)
            ->where('user_id', auth()->id())
            ->with('items')
            ->firstOrFail();

        $fileContent = "Shopping List: " . $list->name . "\n\n";
        $fileContent .= "Items:\n";

        foreach ($list->items as $item) {
            $fileContent .= "- " . $item->name . " (Amount: " . $item->amount . ", Price Per Item: " . number_format($item->price_per_item, 2) . ")\n";
        }

        $fileName = 'shopping_list_' . $list->id . '.txt';

        return Response::make($fileContent, 200, [
            'Content-Type' => 'text/plain',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ]);
    }

    /**
     * Updates the reminder date of the shopping list
     *
     * @param Request $request, int $id
     * @return RedirectResponse
     */
    public function updateReminder(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'reminder_date' => 'nullable|date|after_or_equal:today',
        ]);

        $list = ShoppingList::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $list->update(['reminder_date' => $request->reminder_date]);

        return redirect()->route('lists.show', $id)
            ->with('success', 'Reminder updated successfully!');
    }

    /**
     * imports items from a text file to the shopping list
     *
     * @param Request $request, int $id
     * @return RedirectResponse
     */
    public function import(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'import_file' => 'required|file|mimes:txt|max:2048', // Validate file type and size
        ]);

        $file = $request->file('import_file');
        $content = file_get_contents($file);

        // Split the content by lines
        $lines = explode("\n", $content);

        foreach ($lines as $line) {
            // Skip empty lines
            if (trim($line) === '') {
                continue;
            }

            // Parse the line (item,quantity,price)
            $data = str_getcsv($line);

            // Ensure the correct number of fields
            if (count($data) !== 3) {
                continue; // Skip invalid lines
            }

            // Add item to the shopping list
            ListItem::create([
                'shopping_list_id' => $id,
                'name' => trim($data[0]),
                'amount' => (int) trim($data[1]),
                'price_per_item' => (float) trim($data[2]),
                'total_price' => (int) trim($data[1]) * (float) trim($data[2]),
            ]);
        }

        return redirect()->route('lists.show', $id)
            ->with('success', 'Items imported successfully!');
    }

}
