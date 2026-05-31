@props(['white' => false])

<div {{ $attributes->merge(['class' => 'xiway-brand-lockup']) }}>
    <x-xiway-logo :white="$white" class="xiway-brand-lockup-logo" />
    <span class="xiway-brand-lockup-text">xiway<em>pos</em></span>
</div>
