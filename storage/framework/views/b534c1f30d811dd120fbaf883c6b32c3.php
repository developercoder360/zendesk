<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'variant' => 'default',
    'size' => 'default',
    'href' => null,
    'type' => 'button',
    'as' => null,
    'color' => null,            // any CSS color → recolors the button (overrides the primary token locally)
    'colorForeground' => null,  // optional label colour for `color`; defaults to white
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'variant' => 'default',
    'size' => 'default',
    'href' => null,
    'type' => 'button',
    'as' => null,
    'color' => null,            // any CSS color → recolors the button (overrides the primary token locally)
    'colorForeground' => null,  // optional label colour for `color`; defaults to white
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $base = "inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium transition-all disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg:not([class*='size-'])]:size-4 shrink-0 [&_svg]:shrink-0 outline-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive";

    // Local theme override: setting `color` rewrites the primary/secondary/ring tokens on this
    // element only, so the existing token-driven classes recolor — no bespoke styling needed.
    // (The same trick works for any subtree: wrap it in a div with `style="--primary: ..."`.)
    $colorStyle = '';
    if ($color) {
        $colorStyle = "--primary: {$color}; --secondary: {$color}; --ring: {$color}; --primary-foreground: ".($colorForeground ?: '#ffffff').';';
    }
    $userStyle = (string) $attributes->get('style', '');
    $style = trim($colorStyle.($colorStyle && $userStyle ? ' ' : '').$userStyle);
    $attributes = $attributes->except('style');

    $variants = [
        'default' => 'bg-primary text-primary-foreground shadow-xs hover:bg-primary/90',
        'destructive' => 'bg-destructive text-white shadow-xs hover:bg-destructive/90 focus-visible:ring-destructive/20 dark:focus-visible:ring-destructive/40 dark:bg-destructive/60',
        'outline' => 'border bg-background shadow-xs hover:bg-accent hover:text-accent-foreground dark:bg-input/30 dark:border-input dark:hover:bg-input/50',
        'secondary' => 'bg-secondary text-secondary-foreground shadow-xs hover:bg-secondary/80',
        'ghost' => 'hover:bg-accent hover:text-accent-foreground dark:hover:bg-accent/50',
        'link' => 'text-primary underline-offset-4 hover:underline',
    ];

    $sizes = [
        'default' => 'h-9 px-4 py-2 has-[>svg]:px-3',
        'xs' => 'h-7 gap-1 rounded-md px-2.5 text-xs has-[>svg]:px-2 [&_svg:not([class*=size-])]:size-3.5',
        'sm' => 'h-8 rounded-md gap-1.5 px-3 has-[>svg]:px-2.5',
        'lg' => 'h-10 rounded-md px-6 has-[>svg]:px-4',
        'icon' => 'size-9',
        'icon-xs' => 'size-7 [&_svg:not([class*=size-])]:size-3.5',
        'icon-sm' => 'size-8',
        'icon-lg' => 'size-10',
    ];

    $classes = $base.' '.($variants[$variant] ?? $variants['default']).' '.($sizes[$size] ?? $sizes['default']);

    // Element polymorphism: explicit `as`/`tag`, else <a> when `href`, else <button>.
    // `type` is emitted only for a real <button>; `href` only for an <a>.
    $tag = $as ?: ($href ? 'a' : 'button');
?>


<<?php echo e($tag); ?>

    data-slot="button"
    <?php if($tag === 'a' && $href): ?> href="<?php echo e($href); ?>" <?php endif; ?>
    <?php if($tag === 'button'): ?> type="<?php echo e($type); ?>" <?php endif; ?>
    <?php if($style): ?> style="<?php echo e($style); ?>" <?php endif; ?>
    <?php echo e($attributes->twMerge($classes)); ?>

>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($before)): ?><?php echo e($before); ?><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php echo e($slot); ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($after)): ?><?php echo e($after); ?><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</<?php echo e($tag); ?>>
<?php /**PATH C:\laragon\www\zendesk\resources\views/components/ui/button.blade.php ENDPATH**/ ?>