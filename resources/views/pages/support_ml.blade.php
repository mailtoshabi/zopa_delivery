@extends('layouts.app')

@section('title', 'സഹായം - ' . config('app.name'))

@section('content')
<div class="container mt-5">
    <div class="text-center mb-4">
        <h2 class="position-relative d-inline-block px-4 py-2">
            സഹായം
        </h2>
        <div class="mt-1" style="width: 120px; height: 2px; background: #000000; margin: auto; border-radius: 2px;"></div>
    </div>
    <p class="text-center text-muted mt-3">നിങ്ങളുടെ സംശയങ്ങൾക്കും ആശങ്കകൾക്കും പിന്തുണ നൽകാൻ ഞങ്ങൾ ഇവിടെ ഉണ്ടാകുന്നു.</p>

    <section class="mb-5">
        <h3>ബന്ധപ്പെടാൻ</h3>
        <p>താങ്കൾക്ക് സഹായം ആവശ്യമായാൽ, താഴെപ്പറയുന്ന മാർഗങ്ങളിൽ നിന്നൊന്നിലൂടെ ഞങ്ങളെ ബന്ധപ്പെടുക:</p>
        <ul>
            <li><strong>ഇമെയിൽ:</strong> support@zopa.in</li>
            <li><strong>ഫോൺ:</strong> +91 XXXXXXXXXX</li>
            <li><strong>ലൈവ് ചാറ്റ്:</strong> ഞങ്ങളുടെ വെബ്സൈറ്റിൽ ബിസിനസ് സമയത്ത് ലഭ്യമാണ്.</li>
        </ul>
    </section>

    <section class="mb-5">
        <h3>പതിവായി ചോദിക്കപ്പെടുന്ന ചോദ്യങ്ങൾ (FAQs)</h3>
        <p>ബന്ധപ്പെടുന്നതിനുമുമ്പ്, നിങ്ങളുടെ ചോദ്യങ്ങൾക്ക് ഉത്തരം ഞങ്ങളുടെ <a href="{{ route('faq') }}">FAQs വിഭാഗത്തിൽ</a> ലഭ്യമായിരിക്കാം.</p>
    </section>

    <section class="mb-5">
        <h3>ഓർഡർ & സബ്സ്ക്രിപ്ഷൻ പിന്തുണ</h3>
        <p>നിങ്ങളുടെ ഓർഡറുമായി അല്ലെങ്കിൽ സബ്സ്ക്രിപ്ഷനുമായി ബന്ധപ്പെട്ട സഹായം വേണ്ടെങ്കിൽ, നിങ്ങളുടെ <a href="{{ route('customer.profile') }}">പ്രൊഫൈൽ</a> പേജ് സന്ദർശിച്ച് ഭക്ഷണങ്ങൾ മാനേജ് ചെയ്യുക.</p>
    </section>

    <section class="mb-5">
        <h3>സാങ്കേതിക സഹായം</h3>
        <p>വെബ്സൈറ്റ് അല്ലെങ്കിൽ മൊബൈൽ ആപ്പ് സംബന്ധിച്ച ഏതെങ്കിലും സാങ്കേതിക പ്രശ്നങ്ങൾ നേരിടുന്നുവെങ്കിൽ, ദയവായി പ്രശ്നത്തിന്റെ വിശദമായ വിവരണത്തോടെ ഞങ്ങൾക്ക് ഒരു ഇമെയിൽ അയയ്ക്കുക.</p>
    </section>

    <section>
        <h3>ബിസിനസ് സമയം</h3>
        <p>താഴെ കാണുന്ന സമയങ്ങളിൽ ഞങ്ങൾ സഹായത്തിനായി ലഭ്യരാണ്:</p>
        <ul>
            <li><strong>തിങ്കൾ - ശനി:</strong> രാവിലെ 9:00 മുതൽ വൈകിട്ട് 5:30 വരെ</li>
            {{-- <li><strong>ഞായർ:</strong> രാവിലെ 10:00 മുതൽ വൈകിട്ട് 6:00 വരെ</li> --}}
        </ul>
    </section>
</div>
@endsection
