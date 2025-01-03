<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1 class="text-2xl font-bold mb-4">Vaša opozorila</h1>

                    @if($opozorila->isEmpty())
                        <p class="text-gray-500">Nimate novih opozoril.</p>
                    @else
                        <table class="min-w-full border-collapse border border-gray-700">
                            <thead>
                            <tr class="bg-gray-700 text-white">
                                <th class="p-2 border border-gray-600">#</th>
                                <th class="p-2 border border-gray-600">Sporočilo</th>
                                <th class="p-2 border border-gray-600">Prebrano</th>
                                <th class="p-2 border border-gray-600">Označi kot prebrano</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($opozorila as $opozorilo)
                                <tr id="opozorilo-{{ $opozorilo->id }}" class="hover:bg-gray-700">
                                    <td class="p-2 border border-gray-600">{{ $opozorilo->id }}</td>
                                    <td class="p-2 border border-gray-600">{{ $opozorilo->message }}</td>
                                    <td class="p-2 border border-gray-600">{{ $opozorilo->prebrano ? 'Da' : 'Ne' }}</td>
                                    <td class="p-2 border border-gray-600">
                                        @if(!$opozorilo->prebrano)
                                            <button class="btn btn-success btn-sm oznaci-prebrano bg-green-500 text-white py-1 px-2 rounded" data-id="{{ $opozorilo->id }}">
                                                Označi kot prebrano
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>

                <!-- Skripte za AJAX -->
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script>
                    $(document).ready(function () {
                        $('.oznaci-prebrano').on('click', function () {
                            let opozoriloId = $(this).data('id');

                            $.ajax({
                                url: '{{ route("opozorila.oznaciPrebrano") }}',
                                method: 'POST',
                                data: {
                                    id: opozoriloId,
                                    _token: '{{ csrf_token() }}' // Za CSRF zaščito
                                },
                                success: function (response) {
                                    if (response.status === 'success') {
                                        let row = $('#opozorilo-' + opozoriloId);
                                        row.find('td:nth-child(3)').text('Da');
                                        row.find('.oznaci-prebrano').fadeOut();
                                        osveziSteviloNeprebranih();
                                    }
                                },
                                error: function () {
                                    alert('Prišlo je do napake pri označevanju opozorila.');
                                }
                            });
                        });

                        function osveziSteviloNeprebranih() {
                            $.get('{{ route("opozorila.steviloNeprebranih") }}', function (data) {
                                $('#neprebrana-opozorila').text('Neprebrana opozorila: ' + data.neprebranaOpozorila);
                            });
                        }
                    });
                </script>
            </div>
        </div>
    </div>
</x-app-layout>
