@props(['tags' => []])

<div class="flex flex-wrap gap-2">
    <span class="inline-flex items-center px-2.5 py-1.5">
        Oznake:
    </span>

    @if($tags && count($tags) > 0)
        @foreach($tags as $tag)
            <x-tags.tag :tag="$tag" />
        @endforeach
    @else
        <span class="inline-flex items-center px-2.5 py-1.5">
            Ta seznam nima oznak
        </span>
    @endif
</div>
