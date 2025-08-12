<?php $__env->startSection('title', 'പതിവുചോദ്യങ്ങൾ - ' . config('app.name')); ?>

<?php $__env->startSection('content'); ?>
<div class="container mt-5">

    <div class="text-center mb-4">
        <h2 class="position-relative d-inline-block px-4 py-2">
            പതിവുചോദ്യങ്ങൾ (FAQ)
        </h2>
        <div class="mt-1" style="width: 120px; height: 2px; background: #000000; margin: auto; border-radius: 2px;"></div>
    </div>
    <p class="text-center text-muted mt-3"> <?php echo config('app.name'); ?> സംബന്ധിച്ച പതിവായ ചോദ്യങ്ങൾക്ക് ഇവിടെ ഉത്തരം കണ്ടെത്താം</p>

    <div class="accordion" id="faqAccordion">

        <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    <?php echo config('app.name'); ?> എന്താണ്?
                </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    <?php echo config('app.name'); ?> ഒരു വീട്ടിൽ പാചകം ചെയ്ത ഭക്ഷണം വീട്ടിലേയ്ക്ക് തന്നെ എത്തിക്കുന്ന സേവനമാണ്. ഞങ്ങൾ ശുചിത്വമുള്ള അടുക്കളകളിൽ പ്രാദേശികമായി ലഭ്യമാക്കുന്ന പച്ചവസ്തുക്കുകൾ ഉപയോഗിച്ച് പാകം ചെയ്യുന്നു.
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header" id="headingTwo">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                    ഞാൻ എങ്ങനെ ഓർഡർ നൽകാം?
                </button>
            </h2>
            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    ഞങ്ങളുടെ വെബ്സൈറ്റ് വഴി നിങ്ങൾക്ക് ഭക്ഷണം അല്ലെങ്കിൽ സബ്സ്ക്രിപ്ഷൻ പ്ലാൻ തിരഞ്ഞെടുക്കാം, കാർട്ടിൽ ചേർക്കുക, തുടർന്ന് ചെക്കൗട്ട് പൂര്‍ത്തിയാക്കുക.
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header" id="headingThree">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                    നിങ്ങൾ എവയവയോ പേയ്മെന്റ് മോഡുകൾ പിന്തുണയ്ക്കുന്നു?
                </button>
            </h2>
            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    ഞങ്ങൾ UPI, ക്രെഡിറ്റ്/ഡെബിറ്റ് കാർഡുകൾ, നെറ്റ് ബാങ്കിംഗ്, വാലറ്റുകൾ, കാഷ് ഓൺ ഡെലിവറി (കുറച്ച് പ്രദേശങ്ങളിൽ മാത്രം) എന്നിവ പിന്തുണയ്ക്കുന്നു.
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header" id="headingFour">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                    ഞാൻ എന്റെ മീൽ പ്ലാൻ കസ്റ്റമൈസ് ചെയ്യാമോ?
                </button>
            </h2>
            <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    അതെ, നിങ്ങൾ ഇഷ്ടപ്പെടുന്ന ഭക്ഷണങ്ങൾ തിരഞ്ഞെടുക്കാനും ആവശ്യം പോലെ ആവർത്തനം ക്രമീകരിക്കാനും കഴിയും. ചില ഭക്ഷണങ്ങൾക്ക് സംവാദം നൽകാനും ഘടകങ്ങൾ മാറ്റാനും സൗകര്യമുണ്ട്.
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header" id="headingFive">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                    റദ്ദാക്കൽ/റീഫണ്ട് നയം എന്താണ്?
                </button>
            </h2>
            <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    കട്ട് ഓഫ് സമയത്തിനു മുൻപായി (പൊതുവായി വിതരണം ചെയ്യുന്നതിന് മണിക്കൂറുകൾക്ക് മുൻപ്) നിങ്ങൾക്ക് ഓർഡർ റദ്ദാക്കാം. റദ്ദാക്കൽ സമയപരിധിക്ക് ശേഷം ഉണ്ടായ ഇനങ്ങളും നിലവാര പ്രശ്നങ്ങൾ ഉണ്ടായാൽ റീഫണ്ട് നൽകാം.
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header" id="headingSix">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                    നിങ്ങൾ എവിടെയെല്ലാം ഡെലിവറി നടത്തുന്നു?
                </button>
            </h2>
            <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    നിലവിൽ ഞങ്ങൾ തെരഞ്ഞെടുത്ത ജില്ലകളിലും നഗരങ്ങളിലും ഡെലിവറി ചെയ്യുന്നു. രജിസ്ട്രേഷനിലോ ഓർഡർ പേജിലോ ലൊക്കേഷൻ നൽകുന്ന സമയത്ത് സേവനം ലഭ്യമാണോ എന്ന് പരിശോധിക്കാം.
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header" id="headingSeven">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                    ഭക്ഷണം ഏത് സമയത്താണ് എത്തിക്കുന്നത്?
                </button>
            </h2>
            <div id="collapseSeven" class="accordion-collapse collapse" aria-labelledby="headingSeven" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    സാധാരണയായി ഉച്ചഭക്ഷണ സമയത്താണ് വിതരണം. കിച്ചൻ ലോക്കേഷൻ അനുസരിച്ചും നിങ്ങളുടെ സ്ഥലവും ആശ്രയിച്ചായിരിക്കും സമയം വ്യത്യാസപ്പെടുക.
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header" id="headingEight">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                    ഞാൻ ഡെലിവറി നിർത്തിയിടാനോ ഒഴിവാക്കാനോ കഴിയുമോ?
                </button>
            </h2>
            <div id="collapseEight" class="accordion-collapse collapse" aria-labelledby="headingEight" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    അതെ, നിങ്ങളുടെ ഡാഷ്ബോർഡ് വഴി നിങ്ങൾക്ക് ചില ദിവസങ്ങൾക്ക് മുൻകൂർ ലീവ് മാർക്ക് ചെയ്യാം. എന്നാൽ ഇത് കട്ട് ഓഫിന് മുൻപ് ചെയ്യേണ്ടതാണ്.
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header" id="headingNine">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseNine" aria-expanded="false" aria-controls="collapseNine">
                    സഹായം ആവശ്യപ്പെട്ടാൽ എങ്ങനെയാണ് ബന്ധപ്പെടാം?
                </button>
            </h2>
            <div id="collapseNine" class="accordion-collapse collapse" aria-labelledby="headingNine" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    WhatsApp, ഫോൺ, അല്ലെങ്കിൽ ഞങ്ങളുടെ കോൺടാക്റ്റ് പേജിലൂടെയും നിങ്ങളുടെ ക്വറി സമർപ്പിച്ച് ഞങ്ങളുമായി ബന്ധപ്പെടാം. സാധാരണയായി 24 മണിക്കൂറിനുള്ളിൽ പ്രതികരിക്കുന്നതാണ്.
                </div>
            </div>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\pages\faq_ml.blade.php ENDPATH**/ ?>