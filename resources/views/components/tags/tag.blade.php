@props(['tag' => null])

<a href="{{ route('user.lists', $tag->id) }}" class="inline-flex items-center px-2.5 py-1.5 hover:bg-gray-300 focus:bg-gray-300 hover:text-gray-500 bg-white font-semibold text-s text-black tracking-normal transition-colors duration-200 ease-in-out">
    {{ $tag->name }}
</a>

