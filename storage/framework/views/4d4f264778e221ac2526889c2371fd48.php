<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'type' => 'single',
    'collapsible' => false,
    'value' => null,
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
    'type' => 'single',
    'collapsible' => false,
    'value' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div
    data-slot="accordion"
    x-data="{
        type: <?php echo \Illuminate\Support\Js::from($type)->toHtml() ?>,
        collapsible: <?php echo \Illuminate\Support\Js::from((bool) $collapsible)->toHtml() ?>,
        open: <?php echo \Illuminate\Support\Js::from($type === 'multiple' ? (array) ($value ?? []) : $value)->toHtml() ?>,
        toggle(v) {
            if (this.type === 'multiple') {
                this.open = this.open.includes(v) ? this.open.filter(x => x !== v) : [...this.open, v];
            } else {
                this.open = this.open === v ? (this.collapsible ? null : this.open) : v;
            }
        },
        isOpen(v) {
            return this.type === 'multiple' ? this.open.includes(v) : this.open === v;
        },
    }"
    x-id="['blat-accordion-trigger', 'blat-accordion-panel']"
    @keydown="$blatNav($event, { selector: '[data-slot=accordion-trigger]', orientation: 'vertical', loop: false, requireMatch: true })"
    <?php echo e($attributes); ?>

>
    <?php echo e($slot); ?>

</div>
<?php /**PATH C:\laragon\www\zendesk\resources\views/components/ui/accordion.blade.php ENDPATH**/ ?>