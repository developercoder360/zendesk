<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\Plan;
use Illuminate\Support\Collection;

?>

<div>
 <?php $__env->slot('meta_description', null, []); ?> Simple, transparent pricing for teams of all sizes. Find the right Zendesk plan for your business. <?php $__env->endSlot(); ?>

    <section class="py-24 lg:py-32">
        <div class="container mx-auto px-4 md:px-6 max-w-7xl">
            <div class="text-center mb-16">
                <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight mb-6">Simple, transparent pricing</h1>
                <p class="text-xl text-muted-foreground max-w-2xl mx-auto mb-10">No hidden fees. No surprise charges. Choose the plan that best fits your needs.</p>
                
                <!-- Billing Toggle -->
                <div class="flex items-center justify-center space-x-4">
                    <span class="text-sm font-medium" :class="!$wire.annual ? 'text-foreground' : 'text-muted-foreground'">Monthly</span>
                    <button wire:click="toggleBilling" class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer items-center justify-center rounded-full border-2 border-transparent bg-primary transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:ring-offset-background">
                        <span class="sr-only">Toggle billing cycle</span>
                        <span aria-hidden="true" class="pointer-events-none block h-5 w-5 rounded-full bg-background shadow-lg ring-0 transition-transform" :class="$wire.annual ? 'translate-x-5' : 'translate-x-0'"></span>
                    </button>
                    <span class="text-sm font-medium flex items-center gap-1.5" :class="$wire.annual ? 'text-foreground' : 'text-muted-foreground'">
                        Annually
                        <span class="inline-flex items-center rounded-full border px-2 py-0.5 text-[10px] font-semibold transition-colors bg-primary/10 text-primary border-transparent">Save 20%</span>
                    </span>
                </div>
            </div>

            <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => ['class' => 'border-border/50 flex flex-col '.e($plan->is_popular ? 'border-primary shadow-md relative scale-105 z-10 bg-background' : 'bg-background/50').'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'border-border/50 flex flex-col '.e($plan->is_popular ? 'border-primary shadow-md relative scale-105 z-10 bg-background' : 'bg-background/50').'']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($plan->is_popular): ?>
                        <div class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2">
                            <span class="bg-primary text-primary-foreground text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">Most Popular</span>
                        </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        
                        <?php if (isset($component)) { $__componentOriginalac05ab5900e4a61633d685620e23e750 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalac05ab5900e4a61633d685620e23e750 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card-header','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                            <?php if (isset($component)) { $__componentOriginalc56124b9f1e7c719f3e4c157ff6c4c42 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc56124b9f1e7c719f3e4c157ff6c4c42 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card-title','data' => ['class' => 'text-xl']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card-title'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'text-xl']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>
<?php echo e($plan->name); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc56124b9f1e7c719f3e4c157ff6c4c42)): ?>
<?php $attributes = $__attributesOriginalc56124b9f1e7c719f3e4c157ff6c4c42; ?>
<?php unset($__attributesOriginalc56124b9f1e7c719f3e4c157ff6c4c42); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc56124b9f1e7c719f3e4c157ff6c4c42)): ?>
<?php $component = $__componentOriginalc56124b9f1e7c719f3e4c157ff6c4c42; ?>
<?php unset($__componentOriginalc56124b9f1e7c719f3e4c157ff6c4c42); ?>
<?php endif; ?>
                            <?php if (isset($component)) { $__componentOriginal470eb5d7b6eb6df5875f31f1aed7d459 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal470eb5d7b6eb6df5875f31f1aed7d459 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card-description','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card-description'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>
<?php echo e($plan->description); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal470eb5d7b6eb6df5875f31f1aed7d459)): ?>
<?php $attributes = $__attributesOriginal470eb5d7b6eb6df5875f31f1aed7d459; ?>
<?php unset($__attributesOriginal470eb5d7b6eb6df5875f31f1aed7d459); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal470eb5d7b6eb6df5875f31f1aed7d459)): ?>
<?php $component = $__componentOriginal470eb5d7b6eb6df5875f31f1aed7d459; ?>
<?php unset($__componentOriginal470eb5d7b6eb6df5875f31f1aed7d459); ?>
<?php endif; ?>
                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalac05ab5900e4a61633d685620e23e750)): ?>
<?php $attributes = $__attributesOriginalac05ab5900e4a61633d685620e23e750; ?>
<?php unset($__attributesOriginalac05ab5900e4a61633d685620e23e750); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalac05ab5900e4a61633d685620e23e750)): ?>
<?php $component = $__componentOriginalac05ab5900e4a61633d685620e23e750; ?>
<?php unset($__componentOriginalac05ab5900e4a61633d685620e23e750); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginalc746ce104dd1dce2fca3edd86e05f674 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc746ce104dd1dce2fca3edd86e05f674 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card-content','data' => ['class' => 'flex-1']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card-content'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'flex-1']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                            <div class="mb-6">
                                <span class="text-4xl font-extrabold" x-text="$wire.annual ? '$<?php echo e($plan->price_yearly); ?>' : '$<?php echo e($plan->price); ?>'">$<?php echo e($plan->price); ?></span>
                                <span class="text-muted-foreground">/agent/mo</span>
                            </div>
                            <ul class="space-y-3 mb-8">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $plan->features ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <li class="flex items-center gap-2">
                                    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('lucide-check'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-4 h-4 text-primary']); ?>
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
                                    <span class="text-sm <?php echo e($loop->first && str_contains($feature, 'Everything in') ? 'font-medium' : ''); ?>"><?php echo e($feature); ?></span>
                                </li>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </ul>
                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc746ce104dd1dce2fca3edd86e05f674)): ?>
<?php $attributes = $__attributesOriginalc746ce104dd1dce2fca3edd86e05f674; ?>
<?php unset($__attributesOriginalc746ce104dd1dce2fca3edd86e05f674); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc746ce104dd1dce2fca3edd86e05f674)): ?>
<?php $component = $__componentOriginalc746ce104dd1dce2fca3edd86e05f674; ?>
<?php unset($__componentOriginalc746ce104dd1dce2fca3edd86e05f674); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginal28e2743859c51529710c4beabe9ecf2b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal28e2743859c51529710c4beabe9ecf2b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card-footer','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card-footer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                                <a href="/dashboard" class="w-full">
                                    <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['class' => 'w-full','variant' => ''.e($plan->is_popular ? 'default' : 'outline').'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-full','variant' => ''.e($plan->is_popular ? 'default' : 'outline').'']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>
Current Plan <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
                                </a>
                            <?php else: ?>
                                <a href="<?php echo e($plan->cta_link ?? '/register'); ?>" class="w-full">
                                    <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['class' => 'w-full','variant' => ''.e($plan->is_popular ? 'default' : 'outline').'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-full','variant' => ''.e($plan->is_popular ? 'default' : 'outline').'']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>
<?php echo e($plan->cta_text ?? 'Get Started'); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
                                </a>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal28e2743859c51529710c4beabe9ecf2b)): ?>
<?php $attributes = $__attributesOriginal28e2743859c51529710c4beabe9ecf2b; ?>
<?php unset($__attributesOriginal28e2743859c51529710c4beabe9ecf2b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal28e2743859c51529710c4beabe9ecf2b)): ?>
<?php $component = $__componentOriginal28e2743859c51529710c4beabe9ecf2b; ?>
<?php unset($__componentOriginal28e2743859c51529710c4beabe9ecf2b); ?>
<?php endif; ?>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $attributes = $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $component = $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Yearly Billing Alert Modal -->
    <?php if (isset($component)) { $__componentOriginal9350f64a54c718b255fbc58bfe4d1b21 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9350f64a54c718b255fbc58bfe4d1b21 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.alert-dialog','data' => ['id' => 'yearly']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.alert-dialog'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'yearly']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

        <?php if (isset($component)) { $__componentOriginal97e23b989b5a6f93c841269dfc5973fc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal97e23b989b5a6f93c841269dfc5973fc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.alert-dialog-content','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.alert-dialog-content'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

            <?php if (isset($component)) { $__componentOriginal5d70d0948cceaff49d3e157af0337020 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5d70d0948cceaff49d3e157af0337020 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.alert-dialog-header','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.alert-dialog-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                <?php if (isset($component)) { $__componentOriginalb10fc5d7ad3e1d782b62d53626475b8b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb10fc5d7ad3e1d782b62d53626475b8b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.alert-dialog-title','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.alert-dialog-title'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>
Coming Soon <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb10fc5d7ad3e1d782b62d53626475b8b)): ?>
<?php $attributes = $__attributesOriginalb10fc5d7ad3e1d782b62d53626475b8b; ?>
<?php unset($__attributesOriginalb10fc5d7ad3e1d782b62d53626475b8b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb10fc5d7ad3e1d782b62d53626475b8b)): ?>
<?php $component = $__componentOriginalb10fc5d7ad3e1d782b62d53626475b8b; ?>
<?php unset($__componentOriginalb10fc5d7ad3e1d782b62d53626475b8b); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal9745631dba9ac1d04457ac470d6b0a79 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9745631dba9ac1d04457ac470d6b0a79 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.alert-dialog-description','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.alert-dialog-description'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                    Yearly billing is not available yet. This feature is currently under development and will be released in a future update.
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9745631dba9ac1d04457ac470d6b0a79)): ?>
<?php $attributes = $__attributesOriginal9745631dba9ac1d04457ac470d6b0a79; ?>
<?php unset($__attributesOriginal9745631dba9ac1d04457ac470d6b0a79); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9745631dba9ac1d04457ac470d6b0a79)): ?>
<?php $component = $__componentOriginal9745631dba9ac1d04457ac470d6b0a79; ?>
<?php unset($__componentOriginal9745631dba9ac1d04457ac470d6b0a79); ?>
<?php endif; ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5d70d0948cceaff49d3e157af0337020)): ?>
<?php $attributes = $__attributesOriginal5d70d0948cceaff49d3e157af0337020; ?>
<?php unset($__attributesOriginal5d70d0948cceaff49d3e157af0337020); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5d70d0948cceaff49d3e157af0337020)): ?>
<?php $component = $__componentOriginal5d70d0948cceaff49d3e157af0337020; ?>
<?php unset($__componentOriginal5d70d0948cceaff49d3e157af0337020); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal61a14df65a076fc2b859ca0c26747f22 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal61a14df65a076fc2b859ca0c26747f22 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.alert-dialog-footer','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.alert-dialog-footer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                <?php if (isset($component)) { $__componentOriginal6e68a30f51cfd5c385fbfdf1db994e68 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6e68a30f51cfd5c385fbfdf1db994e68 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.alert-dialog-cancel','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.alert-dialog-cancel'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>
OK <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6e68a30f51cfd5c385fbfdf1db994e68)): ?>
<?php $attributes = $__attributesOriginal6e68a30f51cfd5c385fbfdf1db994e68; ?>
<?php unset($__attributesOriginal6e68a30f51cfd5c385fbfdf1db994e68); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6e68a30f51cfd5c385fbfdf1db994e68)): ?>
<?php $component = $__componentOriginal6e68a30f51cfd5c385fbfdf1db994e68; ?>
<?php unset($__componentOriginal6e68a30f51cfd5c385fbfdf1db994e68); ?>
<?php endif; ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal61a14df65a076fc2b859ca0c26747f22)): ?>
<?php $attributes = $__attributesOriginal61a14df65a076fc2b859ca0c26747f22; ?>
<?php unset($__attributesOriginal61a14df65a076fc2b859ca0c26747f22); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal61a14df65a076fc2b859ca0c26747f22)): ?>
<?php $component = $__componentOriginal61a14df65a076fc2b859ca0c26747f22; ?>
<?php unset($__componentOriginal61a14df65a076fc2b859ca0c26747f22); ?>
<?php endif; ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal97e23b989b5a6f93c841269dfc5973fc)): ?>
<?php $attributes = $__attributesOriginal97e23b989b5a6f93c841269dfc5973fc; ?>
<?php unset($__attributesOriginal97e23b989b5a6f93c841269dfc5973fc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal97e23b989b5a6f93c841269dfc5973fc)): ?>
<?php $component = $__componentOriginal97e23b989b5a6f93c841269dfc5973fc; ?>
<?php unset($__componentOriginal97e23b989b5a6f93c841269dfc5973fc); ?>
<?php endif; ?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9350f64a54c718b255fbc58bfe4d1b21)): ?>
<?php $attributes = $__attributesOriginal9350f64a54c718b255fbc58bfe4d1b21; ?>
<?php unset($__attributesOriginal9350f64a54c718b255fbc58bfe4d1b21); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9350f64a54c718b255fbc58bfe4d1b21)): ?>
<?php $component = $__componentOriginal9350f64a54c718b255fbc58bfe4d1b21; ?>
<?php unset($__componentOriginal9350f64a54c718b255fbc58bfe4d1b21); ?>
<?php endif; ?>
</div><?php /**PATH C:\laragon\www\zendesk\resources\views\livewire/pages/marketing/pricing.blade.php ENDPATH**/ ?>