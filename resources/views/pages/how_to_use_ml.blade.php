@extends('layouts.app')

@section('title', 'Zopa Food Drop ഉപയോഗിക്കുന്ന വിധം')

@section('content')
<div class="container my-4">
    <div class="text-center mb-4">
        <h2 class="position-relative d-inline-block px-4 py-2">
            Zopa Food Drop ഉപയോഗിക്കുന്ന വിധം
        </h2>
        <div class="mt-1" style="width: 120px; height: 2px; background: #27ae60; margin: auto; border-radius: 2px;"></div>
    </div>

    <div class="row">
        <div class="col-md-12 p-4 lh-lg">

            <h5 class="text-secondary mb-3"><i class="fa-solid fa-circle-user"></i> രജിസ്റ്റർ ചെയ്യുക അല്ലെങ്കിൽ ലോഗിൻ ചെയ്യുക</h5>
            <p>നിങ്ങളുടെ രജിസ്റ്റർ ചെയ്ത ഫോൺ നമ്പർയും പാസ്‌വേഡും ഉപയോഗിച്ച് ലോഗിൻ പേജിൽ പ്രവേശിക്കുക. നിങ്ങളുടെ അക്കൗണ്ട് <strong>അനുമോദിച്ചിട്ടും സജീവവുമാകണം</strong> നിങ്ങളുടെ ഡാഷ്ബോർഡിലേക്ക് പ്രവേശിക്കാൻ.</p>

            <hr>

            <h5 class="text-secondary mb-3"><i class="fa-solid fa-cart-plus"></i> ഭക്ഷണങ്ങൾ വാങ്ങുക</h5>
            <p>ഭക്ഷണങ്ങൾ രണ്ട് രീതിയിൽ വാങ്ങാം:</p>
            <ul>
                <li><strong>പ്ലാൻ വാങ്ങുക:</strong> <em>Zopa Meals → Buy A Plan</em> ലേക്ക് പോകുക. പ്രീപെയ്ഡ് പ്ലാൻ തിരഞ്ഞെടുക്കുക. ഓൺലൈൻ അല്ലെങ്കിൽ കാഷ് ഓൺ ഡെലിവറി വഴി പണമടയ്ക്കുക. ഭക്ഷണങ്ങൾ നിങ്ങളുടെ <strong>Meal Wallet</strong>-ലേക്ക് ചേർക്കും.</li>
                <li><strong>ഒറ്റത്തവണ ഭക്ഷണം വാങ്ങുക:</strong> <em>Zopa Meals → Buy Single</em> ൽ നിന്ന് ഭക്ഷണം, അളവ് തിരഞ്ഞെടുക്കുക, ഒറ്റത്തവണ ഓർഡർ നൽകുക.</li>
            </ul>

            <hr>

            <h5 class="text-secondary mb-3"><i class="fa-solid fa-utensils"></i> അധിക ഭക്ഷണങ്ങൾ (Add-ons) വാങ്ങുക (ഐച്ഛികം)</h5>
            <p>നിങ്ങളുടെ ദിവസം പ്രതിദിന ഭക്ഷണങ്ങളോടൊപ്പം ബീഫ് ഫ്രൈ, ഫിഷ് ഫ്രൈ തുടങ്ങിയ സൈഡ് ഡിഷുകൾ വാങ്ങാൻ <em>Add-ons</em> സന്ദർശിക്കുക.</p>

            <hr>

            <h5 class="text-secondary mb-3"><i class="fa-solid fa-calendar-day"></i> പ്രതിദിന ഭക്ഷണങ്ങൾ എങ്ങനെ വിതരണം ചെയ്യുന്നു</h5>
            <p>പ്രതിദിനം രാവിലെ, സിസ്റ്റം:</p>
            <ul>
                <li>നിങ്ങളുടെ <strong>Meal Wallet</strong> ബാലൻസ് പരിശോധിക്കും.</li>
                <li>നിങ്ങൾക്കു <strong>കുറഞ്ഞത് 1 ഭക്ഷണം</strong> ഉണ്ടെങ്കിൽ, കൂടാതെ നിങ്ങൾ അവധി അപേക്ഷിച്ചിട്ടില്ലെങ്കിൽ, ഒരു ഭക്ഷണം ഓട്ടോമാറ്റിക് ആയി നൽകും.</li>
                <li><strong>ഞായറാഴ്ചകൾക്ക് ഭക്ഷണം നൽകുന്നില്ല.</strong></li>
                <li>ഭക്ഷണങ്ങൾ <em>Daily Orders</em>-ൽ കാണാം.</li>
            </ul>

            <hr>

            <h5 class="text-secondary mb-3"><i class="fa-solid fa-plus"></i> അധിക ഭക്ഷണം അപേക്ഷിക്കുക</h5>
            <p>My Meals പേജിൽ <strong>“Request Extra Meal”</strong> ക്ലിക്ക് ചെയ്ത്, എത്ര അധിക ഭക്ഷണങ്ങൾ വേണമെന്ന് രേഖപ്പെടുത്തുക. അത് നിങ്ങളുടെ വാലറ്റിൽ നിന്നു കുറച്ചു Daily Orders-ലേക്ക് ചേർക്കും.</p>

            <hr>

            <h5 class="text-secondary mb-3"><i class="fa-solid fa-calendar-xmark"></i> ഭക്ഷണ അവധി അപേക്ഷിക്കുക</h5>
            <p>കഴിഞ്ഞ ദിവസം അല്ലെങ്കിൽ അടുത്ത് ഭക്ഷണം ആവശ്യമില്ലെങ്കിൽ <em>My Leaves</em>-ൽ പോകുക, ആ ദിവസം അവധിയായി മാർക്ക് ചെയ്യുക.</p>
            <ul>
                <li>നിങ്ങൾക്ക് <strong>{{ Utility::MAX_LEAVE_DAYS_AHEAD }}</strong> ദിവസത്തോളം മുൻകൂട്ടി അവധി അപേക്ഷിക്കാം.</li>
                <li><strong>{{ App\Helpers\FileHelper::convertTo12Hour(Utility::CUTOFF_TIME) }}</strong> കഴിഞ്ഞാൽ ആ ദിവസം അവധി റദ്ദാക്കാനാകില്ല.</li>
            </ul>

            <hr>

            <h5 class="text-secondary mb-3"><i class="fa-solid fa-wallet"></i> ഓർഡറുകളും വാലറ്റും ട്രാക്ക് ചെയ്യുക</h5>
            <ul>
                <li><strong>Daily Orders</strong> പേജിൽ അടുത്തും കഴിഞ്ഞും ഉള്ള എല്ലാ ഭക്ഷണങ്ങൾ കാണാം.</li>
                <li>നിങ്ങളുടെ <strong>Meal Wallet</strong> ബാലൻസ് മുകളിലേ മെനുവിൽ 항상 കാണാം.</li>
                <li><em>My Purchases</em>-ൽ പാസ്റ് ട്രാൻസാക്ഷനുകൾ കാണാം.</li>
                <li>കൂടുതൽ meals വാങ്ങാൻ <em>Buy A Plan</em> → പുതിയ പ്ലാൻ വാങ്ങുക.</li>
            </ul>

            <hr>

            <h5 class="text-secondary mb-3"><i class="fa-solid fa-gear"></i> നിങ്ങളുടെ പ്രൊഫൈൽ മാനേജുചെയ്യുക</h5>
            <ul>
                <li><em>My Profile</em>-ൽ വ്യക്തിഗത വിവരങ്ങൾ അപ്ഡേറ്റ് ചെയ്യുക.</li>
                <li>പഴയ വാങ്ങലുകളും Leaves-ഉം പരിശോധിക്കുക.</li>
                <li>സുരക്ഷിതമായി ലോഗ്ഔട്ട് ചെയ്യുക.</li>
            </ul>

            <hr>

            <h5 class="text-secondary mb-3"><i class="fa-solid fa-comment-dots"></i> അഭിപ്രായങ്ങൾ അയയ്ക്കുക</h5>
            <p><em>Feedbacks</em> പേജിൽ സന്ദേശം അയയ്ക്കുക.</p>

            <hr>

            <h5 class="text-secondary mb-3"><i class="fa-solid fa-circle-question"></i> പ്രശ്നപരിഹാരങ്ങൾ</h5>
            <ul>
                <li><strong>ലോഗിൻ ചെയ്യാൻ കഴിയുന്നില്ലേ?</strong> നിങ്ങളുടെ അക്കൗണ്ട് അനുമോദിച്ചിട്ടും സജീവമാണോ എന്ന് പരിശോധിക്കുക. സഹായത്തിന് ഞങ്ങളെ ബന്ധപ്പെടുക.</li>
                <li><strong>ഭക്ഷണം അനുവദിച്ചിട്ടില്ലേ?</strong> Wallet-ൽ ബാലൻസ് പരിശോധിക്കുക, Leave അപേക്ഷിച്ചിട്ടുണ്ടോ എന്ന് നോക്കുക.</li>
                <li><strong>Cutoff സമയം കഴിഞ്ഞു?</strong> <strong>{{ App\Helpers\FileHelper::convertTo12Hour(Utility::CUTOFF_TIME) }}</strong> കഴിഞ്ഞാൽ ഓർഡർ അല്ലെങ്കിൽ Leave മാറ്റം സാധ്യമല്ല.</li>
            </ul>

            <hr>

            <h5 class="text-secondary mb-3"><i class="fa-solid fa-lightbulb"></i> സഹായം (Tips)</h5>
            <ul>
                <li><a href="{{ route('site_map') }}">Site Map</a> ഉപയോഗിച്ച് എളുപ്പത്തിൽ നീങ്ങാം.</li>
                <li>നിങ്ങളുടെ Meal Wallet എപ്പോഴും മുഴുവനായിരിക്കാൻ ശ്രദ്ധിക്കുക.</li>
                <li>Leave-കൾ <strong>{{ App\Helpers\FileHelper::convertTo12Hour(Utility::CUTOFF_TIME) }}</strong> -ന് മുമ്പ് അപേക്ഷിക്കുക.</li>
            </ul>

            <a class="btn btn-zopa" href="{{ route('how_to_use_pdf') }}">
                <i class="fas fa-download me-1"></i> PDF ഡൗൺലോഡ് ചെയ്യുക
            </a>
        </div>
    </div>
</div>
@endsection
