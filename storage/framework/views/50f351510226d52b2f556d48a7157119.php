<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['variant' => 'default']));

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

foreach (array_filter((['variant' => 'default']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $variants = [
        // Simple padded box — the dominant use case. Put any content inside directly.
        'default' => 'bg-card text-card-foreground rounded-xl border p-6 shadow-sm',
        // Sectioned layout for card-header / card-content / card-footer. Those parts each
        // supply their own px-6, so this adds py-6 only (never px) to avoid double padding.
        'sectioned' => 'bg-card text-card-foreground flex flex-col gap-6 rounded-xl border py-6 shadow-sm',
    ];

    $classes = $variants[$variant] ?? $variants['default'];
?>

<div data-slot="card" <?php echo e($attributes->twMerge($classes)); ?>>
    <?php echo e($slot); ?>

</div>
<?php /**PATH C:\laragon\www\zendesk\resources\views/components/ui/card.blade.php ENDPATH**/ ?>