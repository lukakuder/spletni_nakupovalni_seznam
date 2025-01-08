@props(['group' => null])

<div class="card mb-3">
    <div class="card-body">
        <a href="{{ route('groups.show', $group->id) }}" class="text-lg font-semibold text-gray-800 dark:text-gray-200 hover:text-gray-600 dark:hover:text-gray-300">
            {{ $group->name }}
        </a>
        <p class="card-text">{{ $group->description ?? 'Opis ni na voljo' }}</p>
        <p class="text-muted">
            @foreach($group->users->take(5) as $member)
               {{ $member->name }}
            @endforeach
        </p>

{{--        <p class="text-muted">--}}
{{--            @foreach($group->taggable_type->take(5) as $tag)--}}
{{--                {{ $tag->name }}--}}
{{--            @endforeach--}}
{{--        </p>--}}
        <p class="text-muted">
            Ustvarjeno:
            {{ $group && $group->created_at ? $group->created_at->format('d.m.Y') : 'Datum ni na voljo' }}
        </p>
    </div>
</div>


