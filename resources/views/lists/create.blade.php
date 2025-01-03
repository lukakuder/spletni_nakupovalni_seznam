<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Ustvari nov seznam') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Create list form -->
                    <form action="{{ route('lists.store') }}" method="POST">
                        @csrf

                        <!-- Name of the list -->
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Ime Seznama</label>
                            <input type="text" id="name" name="name" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" value="{{ old('name') }}" required>
                        </div>

                        <!-- Description of the list -->
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Opis Seznama</label>
                            <textarea id="description" name="description" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>{{ old('description') }}</textarea>
                        </div>

                        <!-- Group Selection (only if the list belongs to a group) -->
                        <input type="hidden" id="belongs_to_a_group" name="belongs_to_a_group" value="{{ $belongs_to_a_group }}">

                        @if ($belongs_to_a_group)
                            <input type="hidden" id="group_id" name="group_id" value="{{ $group_id }}">
                        @endif

                        <div class="form-group mb-4">
                            <label for="select2Multiple">Izberi oznake</label>
                            <select class="select2-multiple form-control" name="tags[]" multiple="multiple"
                                    id="select2Multiple">
                                @foreach($tags as $tag)
                                    <option value="{{ $tag->slug }}">{{ $tag->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Submit Button -->
                        <x-primary-button> Ustvari seznam </x-primary-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Select2 Multiple
            $('.select2-multiple').select2({
                placeholder: "Select",
                allowClear: true
            });

        });

    </script>
</x-app-layout>
