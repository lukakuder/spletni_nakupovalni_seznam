<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <x-slot name="header">
                    <div class="flex justify-between items-center">
                        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                            Vaša opozorila
                        </h2>
                    </div>
                </x-slot>
                @if($opozorila->isEmpty())
                    <p class="text-gray-500">Nimate novih obvestil.</p>
                @else
                    <table class="table-auto border-separate border-spacing-4 w-full">
                        <thead>
                        <tr>
                            <th class="px-4 py-2">Datum</th>
                            <th class="px-4 py-2">Sporočilo</th>
                            <th class="px-4 py-2">Prebrano</th>
                            <th class="px-4 py-2">Akcijski gumb</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($opozorila->take(10) as $opozorilo)
                            <tr id="opozorilo-{{ $opozorilo->id }}">
                                <td class="px-4 py-2">{{ $opozorilo->created_at }}</td>
                                <td class="px-4 py-2">{{ $opozorilo->message }}</td>
                                <td class="px-4 py-2">{{ $opozorilo->prebrano ? 'Da' : 'Ne' }}</td>
                                <td class="px-4 py-2">
                                    @if(!$opozorilo->prebrano)
                                        <button
                                            class="btn btn-success btn-sm oznaci-prebrano bg-green-500 text-white py-1 px-2 rounded"
                                            data-id="{{ $opozorilo->id }}"
                                            onclick="window.location.href='{{ route('opozorila.index') }}'">
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

            <!-- Skripte AJAX-->
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
                    })

                    function osveziSteviloNeprebranih() {
                        $.get('{{ route("opozorila.steviloNeprebranih") }}', function (data) {
                            $('#neprebrana-opozorila').text('Neprebrana obvestila: ' + data.neprebranaOpozorila);
                        });
                    }
                });
            </script>
        </div>
    </div>

</x-app-layout>
