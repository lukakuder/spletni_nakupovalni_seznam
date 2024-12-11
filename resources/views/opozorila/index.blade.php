<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1>Vaša opozorila</h1>

                    @if($opozorila->isEmpty())
                        <p>Nimate novih opozoril.</p>
                    @else
                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Sporočilo</th>
                                <th>Prebrano</th>
                                <th>Označi kot prebrano</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($opozorila as $opozorilo)
                                <tr id="opozorilo-{{ $opozorilo->id }}">
                                    <td>{{ $opozorilo->id }}</td>
                                    <td>{{ $opozorilo->sporočilo }}</td>
                                    <td>{{ $opozorilo->prebrano ? 'Da' : 'Ne' }}</td>
                                    <td>
                                        @if(!$opozorilo->prebrano)
                                            <button class="btn btn-success btn-sm oznaci-prebrano" data-id="{{ $opozorilo->id }}">
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
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function (response) {
                                    if (response.status === 'success') {
                                        $('#opozorilo-' + opozoriloId).find('td:nth-child(3)').text('Da');
                                        $('#opozorilo-' + opozoriloId).find('.oznaci-prebrano').remove();
                                        osveziSteviloNeprebranih();
                                    }
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
