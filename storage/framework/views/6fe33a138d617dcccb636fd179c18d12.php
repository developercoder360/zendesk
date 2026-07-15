
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['open' => false, 'id' => null]));

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

foreach (array_filter((['open' => false, 'id' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div
    data-slot="sheet"
    x-data="{ open: <?php echo \Illuminate\Support\Js::from((bool) $open)->toHtml() ?> }"
    <?php if($id): ?>
        @open-sheet-<?php echo e($id); ?>.window="open = true"
        @close-sheet-<?php echo e($id); ?>.window="open = false"
    <?php endif; ?>
    x-id="['blat-sheet']"
    <?php echo e($attributes); ?>

>
    <?php echo e($slot); ?>

</div>
<?php /**PATH C:\laragon\www\zendesk\resources\views/components/ui/sheet.blade.php ENDPATH**/ ?>