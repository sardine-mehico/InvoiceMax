<style>
    .subcopy,
    .subcopy p,
    .subcopy td,
    .action,
    .action tr,
    .action td,
    .footer,
    .footer td {
        text-align: left !important;
    }
</style>

@component('mail::layout')
{{-- Header --}}
@slot('header')
@component('mail::header', ['url' => ''])
<div style="text-align: center; width: 100%;">
    {{$data['company']['name']}}
</div>
@endcomponent
@endslot

{{-- Body --}}
<!-- Body here -->

{{-- Subcopy --}}
@slot('subcopy')
@component('mail::subcopy')
<div style="text-align: left;">
    {!! $data['body'] !!}
    @if(!$data['attach']['data'])
        <div style="text-align: left;">
            @component('mail::button', ['url' => $data['url']])
            @lang('mail_view_invoice')
            @endcomponent
        </div>
    @endif
</div>
@endcomponent
@endslot

{{-- Footer --}}
@slot('footer')
@component('mail::footer')
<div style="text-align: left; width: 100%;"></div>
@endcomponent
@endslot
@endcomponent
