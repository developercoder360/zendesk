<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['type' => 'text', 'size' => 'default', 'toggle' => null, 'color' => null]));

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

foreach (array_filter((['type' => 'text', 'size' => 'default', 'toggle' => null, 'color' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $base = "file:text-foreground placeholder:text-muted-foreground selection:bg-primary selection:text-primary-foreground dark:bg-input/30 border-input flex w-full min-w-0 rounded-md border bg-transparent shadow-xs transition-[color,box-shadow] outline-none file:inline-flex file:border-0 file:bg-transparent file:font-medium disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50 focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive";

    $sizes = [
        'sm' => 'h-8 px-2.5 py-1 text-sm file:h-6 file:text-xs',
        'default' => 'h-9 px-3 py-1 text-base md:text-sm file:h-7 file:text-sm',
        'lg' => 'h-10 px-4 py-2 text-base file:h-8 file:text-sm',
    ];

    $classes = $base.' '.($sizes[$size] ?? $sizes['default']);

    // A password field opts into a show/hide eye toggle by default. Pass :toggle="false" to opt out.
    $isPassword = $type === 'password' && $toggle !== false;
    // Optional leading (prefix) / trailing (suffix) adornments — pass an icon via the named slots.
    $hasLeading = isset($leading) && $leading->isNotEmpty();
    $hasTrailing = isset($trailing) && $trailing->isNotEmpty();
    $wrap = $isPassword || $hasLeading || $hasTrailing;

    // Pad the field so text never sits under an adornment (logical props → RTL-safe).
    $pad = ($hasLeading ? ' ps-9' : '').(($isPassword || $hasTrailing) ? ' pe-10' : '');

    // `color` brands the focus ring + selection locally (overrides the ring/primary tokens).
    $colorStyle = $color ? "--ring: {$color}; --primary: {$color}; --primary-foreground: #ffffff;" : '';
    $userStyle = (string) $attributes->get('style', '');
    $fieldStyle = trim($colorStyle.($colorStyle && $userStyle ? ' ' : '').$userStyle);
    $attributes = $attributes->except('style');
?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($wrap): ?>
    
    <div
        data-slot="input-wrapper"
        <?php if($isPassword): ?> x-data="{ show: false }" <?php endif; ?>
        <?php echo e($attributes->only('class')->twMerge('relative w-full')); ?>

    >
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasLeading): ?>
            <span data-slot="input-leading" class="text-muted-foreground pointer-events-none absolute inset-y-0 start-0 flex items-center ps-3 [&_svg]:size-4 [&_svg]:shrink-0">
                <?php echo e($leading); ?>

            </span>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <input
            <?php if($isPassword): ?>
                type="password"
                x-bind:type="show ? 'text' : 'password'"
            <?php else: ?>
                type="<?php echo e($type); ?>"
            <?php endif; ?>
            data-slot="input"
            data-size="<?php echo e($size); ?>"
            <?php if($fieldStyle): ?> style="<?php echo e($fieldStyle); ?>" <?php endif; ?>
            <?php echo e($attributes->except('class')->twMerge($classes.$pad)); ?>

        />

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isPassword): ?>
            <button
                type="button"
                @click="show = !show"
                aria-label="Show password"
                x-bind:aria-label="show ? 'Hide password' : 'Show password'"
                x-bind:aria-pressed="show"
                class="text-muted-foreground hover:text-foreground focus-visible:ring-ring/50 absolute inset-y-0 end-0 flex items-center rounded-md px-3 outline-none transition-colors focus-visible:ring-[3px]"
            >
                
                <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" />
                    <circle cx="12" cy="12" r="3" />
                </svg>
                
                <svg x-show="show" style="display: none;" xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M10.733 5.076a10.744 10.744 0 0 1 11.205 6.575 1 1 0 0 1 0 .696 10.747 10.747 0 0 1-1.444 2.49" />
                    <path d="M14.084 14.158a3 3 0 0 1-4.242-4.242" />
                    <path d="M17.479 17.499a10.75 10.75 0 0 1-15.417-5.151 1 1 0 0 1 0-.696 10.75 10.75 0 0 1 4.446-5.143" />
                    <path d="m2 2 20 20" />
                </svg>
            </button>
        <?php elseif($hasTrailing): ?>
            <span data-slot="input-trailing" class="text-muted-foreground absolute inset-y-0 end-0 flex items-center pe-3 [&_svg]:size-4 [&_svg]:shrink-0">
                <?php echo e($trailing); ?>

            </span>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
<?php else: ?>
    <input
        type="<?php echo e($type); ?>"
        data-slot="input"
        data-size="<?php echo e($size); ?>"
        <?php if($fieldStyle): ?> style="<?php echo e($fieldStyle); ?>" <?php endif; ?>
        <?php echo e($attributes->twMerge($classes)); ?>

    />
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH C:\laragon\www\zendesk\resources\views/components/ui/input.blade.php ENDPATH**/ ?>