@props(['color' => null])

@php
    $base = "file:text-foreground placeholder:text-muted-foreground selection:bg-primary selection:text-primary-foreground dark:bg-input/30 border-input flex w-full min-w-0 rounded-md border bg-transparent shadow-xs transition-[color,box-shadow] outline-none disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50 focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive";

    $classes = $base . " min-h-[80px] px-3 py-2 text-base md:text-sm";

    $colorStyle = $color ? "--ring: {$color}; --primary: {$color}; --primary-foreground: #ffffff;" : '';
    $userStyle = (string) $attributes->get('style', '');
    $fieldStyle = trim($colorStyle.($colorStyle && $userStyle ? ' ' : '').$userStyle);
    $attributes = $attributes->except('style');
@endphp

<textarea
    data-slot="textarea"
    @if ($fieldStyle) style="{{ $fieldStyle }}" @endif
    {{ $attributes->twMerge($classes) }}
>{{ $slot }}</textarea>
