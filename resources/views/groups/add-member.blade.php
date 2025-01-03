<x-app-layout>
    <div class="container">
        <h2>Dodaj člane v skupino: {{ $group->name }}</h2>
        <form action="{{ route('groups.addMembers', $group->id) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="users">Izberite člane:</label>
                <select name="users[]" id="users" class="form-control" multiple>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-success mt-3">Dodaj člane</button>
        </form>
    </div>

</x-app-layout>
