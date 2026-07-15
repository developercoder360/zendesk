<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'side' => 'right',
    'showClose' => true,
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
    'side' => 'right',
    'showClose' => true,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $sideClasses = [
        'right' => 'inset-y-0 right-0 h-full w-3/4 border-l sm:max-w-sm',
        'left' => 'inset-y-0 left-0 h-full w-3/4 border-r sm:max-w-sm',
        'top' => 'inset-x-0 top-0 h-auto border-b',
        'bottom' => 'inset-x-0 bottom-0 h-auto border-t',
    ];

    $enterStart = [
        'right' => 'translate-x-full',
        'left' => '-translate-x-full',
        'top' => '-translate-y-full',
        'bottom' => 'translate-y-full',
    ];

    $base = 'bg-background fixed z-50 flex flex-col gap-4 shadow-lg';
    $classes = $base.' '.($sideClasses[$side] ?? $sideClasses['right']);
    $start = $enterStart[$side] ?? $enterStart['right'];
?>

<template x-teleport="body">
    <div x-show="open" x-cloak class="fixed inset-0 z-50">
        <div
            x-show="open"
            @click="open = false"
            role="presentation"
            aria-hidden="true"
            data-slot="sheet-overlay"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 bg-black/50"
        ></div>

        <div
            x-show="open"
            x-trap.noscroll.inert="open"
            @keydown.escape.window="open = false"
            :id="$id('blat-sheet')"
            x-blat-labelledby="{ label: '[data-slot=sheet-title]', description: '[data-slot=sheet-description]' }"
            role="dialog"
            aria-modal="true"
            tabindex="-1"
            data-slot="sheet-content"
            data-side="<?php echo e($side); ?>"
            x-transition:enter="transition ease-in-out duration-500"
            x-transition:enter-start="<?php echo e($start); ?>"
            x-transition:enter-end="translate-x-0 translate-y-0"
            x-transition:leave="transition ease-in-out duration-300"
            x-transition:leave-start="translate-x-0 translate-y-0"
            x-transition:leave-end="<?php echo e($start); ?>"
            <?php echo e($attributes->twMerge($classes)); ?>

        >
            <?php echo e($slot); ?>


            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showClose): ?>
                <button
                    type="button"
                    @click="open = false"
                    class="ring-offset-background focus:ring-ring absolute top-4 right-4 rounded-xs opacity-70 transition-opacity hover:opacity-100 focus:ring-2 focus:ring-offset-2 focus:outline-hidden disabled:pointer-events-none"
                >
                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-x'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'size-4','aria-hidden' => 'true']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $attributes = $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $component = $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
                    <span class="sr-only">Close</span>
                </button>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</template>
<?php /**PATH C:\laragon\www\zendesk\resources\views/components/ui/sheet-content.blade.php ENDPATH**/ ?>