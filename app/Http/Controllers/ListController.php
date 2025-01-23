<?php

namespace App\Http\Controllers;

use App\Filters\ListFilters;
use App\Models\Group;
use App\Models\PurchasedItem;
use App\Models\ShoppingList;
use App\Models\ListItem;
use App\Models\Receipt;

use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Spatie\Tags\Tag;

class ListController extends Controller
{
    public function getLists()
    {
        //get all lists and its products
        $lists = ListItem::with('products')->get();

        //turn into a collection and return answer
        return $lists->map(function ($list) {
            return [
                'list' => $list,
                'products' => $list->products->items(),
            ];
        });
    }

    /**
     * Returns the view containing users lists
     *
     * @param ListFilters $filters
     * @return View
     */
    public function getUsersLists(ListFilters $filters): View
    {
        // Fetch the user's own shopping lists with applied filters.
        $userLists = Auth::user()
            ->lists()
            ->filter($filters)
            ->get();

        // Fetch the user's group shopping lists with applied filters.
        $groupLists = Auth::user()
            ->groups()
            ->with(['lists' => function ($query) use ($filters) {
                $query->filter($filters);
            }])
            ->get()
            ->pluck('lists') // Extract all lists from groups.
            ->flatten();

        $lists = $userLists->merge($groupLists);

        return view('user.lists', [
            'lists' => $lists,
        ]);
    }

    /**
     * Display the specified shopping list.
     *
     * @param int $id
     * @return View
     */
    public function show($id)
    {
        $list = ShoppingList::where('id', $id)
            //->where('user_id', auth()->id())
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
            'group_id' => $request->input('amp;group_id') ?? $request->input('group_id'),
            'tags' => Tag::all()
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
        // Handles the validation before the processing starts
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

        // Syncs the tags that should belong to the group
        if ($request->tags) {
            $list->syncTags($request->tags);
        }

        return redirect()->route('user.lists')->with('success', 'Seznam je bil uspešno ustvarjen!');
    }

    /**
     * takes and validates item from Add Item form and stores it in the database
     *
     * @param Request $request, int $id
     * @param int $id
     * @return RedirectResponse
     */
    public function storeItem(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|integer|min:1',
            'price_per_item' => 'required|numeric|min:0',
        ]);

        $list = ShoppingList::findOrFail($id);

        $list->items()->create([
            'name' => $request->name,
            'amount' => $request->amount,
            'price_per_item' => $request->price_per_item,
            'total_price' => $request->amount * $request->price_per_item,
        ]);

        return redirect()->route('lists.show', $id)->with('success', 'Izdelek je bil uspešno dodan!');
    }

    /**
     * Exports the shopping list to a text file, including already bought items.
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

        $fileContent = "Seznam: " . $list->name . "\n\n";
        $fileContent .= "Vsebina:\n";

        foreach ($list->items as $item) {
            $fileContent .= "- " . $item->name .
                " (Količina: " . $item->amount .
                ", Kupljeno: " . $item->purchased .
                ", Preostalo: " . ($item->amount - $item->purchased) .
                ", Cena na kos: " . number_format($item->price_per_item, 2) .
                ", Skupna cena: " . number_format($item->price_per_item * $item->amount, 2) .
                ")\n";
        }

        $fileName = 'seznam_' . $list->name . '.txt';

        return Response::make($fileContent, 200, [
            'Content-Type' => 'text/plain',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ]);
    }

    /**
     * Updates the reminder date of the shopping list
     *
     * @param Request $request, int $id
     * @param int $id
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
            ->with('success', 'Opomnik je bil uspešno posodobljen!');
    }

    /**
     * imports items from a text file to the shopping list
     *
     * @param Request $request, int $id
     * @param int $id
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
            ->with('success', 'Izdelki so bili uspešno uvoženi');
    }

    /**
     * Ustvari nov zapis za račun in ga poveže z nakupovalnim seznamom.
     */
    public function storeReceipt(Request $request, $listId)
    {
        // 1. Validacija - preveri ime računa in datoteko
        $request->validate([
            'name' => 'required|string|max:255',
            'file' => 'required|file|mimes:jpg,png,pdf|max:2048',
        ]);

        // 2. Shrani datoteko v imenik 'public/receipts'
        $filePath = $request->file('file')->store('receipts', 'public');

        // 3. Ustvari zapis v tabeli 'receipts'
        Receipt::create([
            'shopping_list_id' => $listId,
            'name' => $request->input('name'),
            'file_path' => $filePath,
        ]);

        // 4. Preusmeritev z obvestilom o uspehu
        return redirect()->route('lists.show', $listId)->with('success', 'Račun je bil uspešno dodan.');
    }

    /**
     * Izbriše račun in povezan zapis v bazi.
     */
    public function destroyReceipt($id)
    {
        // 1. Poišči račun v bazi
        $receipt = Receipt::findOrFail($id);

        // 2. Izbriši datoteko iz strežnika
        Storage::delete($receipt->file_path);

        // 3. Izbriši zapis iz baze
        $receipt->delete();

        // 4. Preusmeritev z obvestilom o uspehu
        return redirect()->back()->with('success', 'Račun je bil uspešno izbrisan.');
    }

    /**
     * Marks an item as purchased
     *
     * @param Request $request
     * @param $id
     * @return RedirectResponse
    */
    public function markAsPurchased(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'price_per_item' => 'nullable|numeric|min:0|max:10000', // Dodaj max ceno po potrebi
        ]);

        $item = ListItem::findOrFail($id);

        // Preveri, ali je količina v dovoljenem obsegu
        if ($item->amount < $item->purchased + $request->quantity) {
            return redirect()->back()->with('error', 'Količina presega dovoljeno mejo.');
        }

        // Preveri, ali je skupna cena v dovoljenem obsegu
        $totalPrice = $request->quantity * ($request->price_per_item ?? 0);
        if ($totalPrice > 10000) { // Max znesek določi po potrebi
            return redirect()->back()->with('error', 'Končni znesek presega dovoljeno mejo.');
        }

        // Posodobi število kupljenih izdelkov
        $item->purchased += $request->quantity;
        $item->save();

        // Zabeleži nakup
        PurchasedItem::create([
            'list_item_id' => $item->id,
            'user_id' => auth()->id(),
            'quantity' => $request->quantity,
            'price_per_item' => $request->price_per_item ?? 0,
        ]);

        return redirect()->back()->with('success', 'Izdelek je bil kupljen.');
    }


    /**
     * Izvozi podrobno poročilo o seznamu nakupov.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function export_report(int $id): \Illuminate\Http\Response
    {
        // Pridobivanje seznama in povezanih artiklov ter nakupov
        $list = ShoppingList::where('id', $id)
            ->where('user_id', auth()->id())
            ->with(['items.purchasedItems.user'])
            ->firstOrFail();

        // Osnovni podatki o seznamu
        $fileContent = "Poročilo o seznamu: " . $list->name . "\n\n";
        $fileContent .= "Podrobnosti nakupov:\n";

        $spending = [];
        $totalListCost = 0;

        foreach ($list->items as $item) {
            $totalItemCost = $item->price_per_item * $item->amount;
            $totalListCost += $totalItemCost;

            $fileContent .= "- Artikel: " . $item->name . "\n";
            $fileContent .= "  Količina: " . $item->amount . "\n";
            $fileContent .= "  Cena na kos: " . number_format($item->price_per_item, 2) . " €\n";
            $fileContent .= "  Skupna cena artikla: " . number_format($totalItemCost, 2) . " €\n";

            // Podatki o nakupih za ta artikel
            if ($item->purchasedItems->count() > 0) {
                $fileContent .= "  Nakupi:\n";
                foreach ($item->purchasedItems as $purchase) {
                    $userName = $purchase->user->name ?? 'Neznan uporabnik';
                    $quantity = $purchase->quantity;
                    $userTotal = $quantity * $item->price_per_item;

                    $fileContent .= "    - " . $userName . " kupil " . $quantity . "x (" .
                        number_format($userTotal, 2) . " €)\n";

                    if (!isset($spending[$userName])) {
                        $spending[$userName] = 0;
                    }
                    $spending[$userName] += $userTotal;
                }
            } else {
                $fileContent .= "  Nakupi: Ni podatkov o nakupih.\n";
            }

            $fileContent .= "\n";
        }

        // Dodajanje skupnih stroškov
        $fileContent .= "Skupni stroški za seznam: " . number_format($totalListCost, 2) . " €\n\n";

        // Dodajanje stroškov po uporabnikih
        $fileContent .= "Skupni stroški po uporabnikih:\n";
        foreach ($spending as $userName => $totalSpent) {
            $fileContent .= "- " . $userName . ": " . number_format($totalSpent, 2) . " €\n";
        }

        // Ustvarjanje imena datoteke
        $fileName = 'porocilo_' . $list->name . '.txt';

        // Vrnitev datoteke kot odgovor
        return Response::make($fileContent, 200, [
            'Content-Type' => 'text/plain',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ]);
    }

    /**
     * Divides the amount to be paid between the lists users
     *
     * @param int $id
     * @return View
     */
    public function divide(int $id): View
    {
        $list = ShoppingList::where('id', $id)
            ->with(['items.purchasedItems.user'])
            ->firstOrFail();

        $group = Group::findOrFail($list->group_id);

        $divided = $group->users->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'total_owed' => 0.00,
            ];
        })->toArray();

        foreach ($list->items as $item) {
            foreach ($item->purchasedItems as $purchase) {
                $cost = $purchase->quantity * (float)$item->price_per_item;

                foreach ($divided as $i => $user) {
                    if ($user['id'] === $purchase->user_id) {
                        $divided[$i]['total_owed'] += $cost;
                        break;
                    }
                }
            }
        }

        foreach ($divided as $i => $user) {
            $divided[$i]['total_owed'] = number_format($user['total_owed'] / count($divided), 2);
        }

        return view('lists.show', compact('list', 'divided'));
    }

    public function duplicate($id)
    {
        // Poišči obstoječi seznam
        $originalList = ShoppingList::with('items')->find($id);

        if (!$originalList) {
            return response()->json(['error' => 'Seznam ne obstaja.'], 404);
        }

        // Preveri, ali ima uporabnik pravico do kopiranja
        if ($originalList->user_id !== Auth::id()) {
            return response()->json(['error' => 'Nimate dovoljenja za kopiranje tega seznama.'], 403);
        }

        // Ustvari kopijo seznama
        $newList = $originalList->replicate();
        $newList->name = $originalList->name . ' (kopija)';
        $newList->save();

        // Kopiraj pripadajoče elemente seznama
        try {
            foreach ($originalList->items as $item) {
                $newItem = $item->replicate();
                $newItem->shopping_list_id = $newList->id; // Uporabite pravilno ime stolpca
                $newItem->save();
            }

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Prišlo je do napake pri kopiranju artiklov: ' . $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'message' => 'Seznam uspešno kopiran.',
            'new_list' => $newList,
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     */
    public function destroy(int $id)
    {
        $list = ShoppingList::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $list->delete();

        return redirect('/user/lists')->with('success', 'Seznam je bil uspešno izbrisan.');
    }

    /**
     * Store a new comment for the shopping list.
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function storeComment(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        $list = ShoppingList::findOrFail($id);

        // Add the new comment to the 'opombe' array
        $comments = $list->opombe ?? [];
        $comments[] = $request->comment;

        $list->opombe = $comments;
        $list->save();

        return redirect()->route('lists.show', $id)->with('success', 'Komentar je bil uspešno dodan.');
    }

}
