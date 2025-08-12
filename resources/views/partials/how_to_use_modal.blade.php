<!-- How to Use Modal -->
<div class="modal fade" id="howToUseModal" tabindex="-1" aria-labelledby="howToUseLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="howToUseLabel">How to Use @appName</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        {{-- Your existing content goes here --}}
        {{-- BEGIN MODAL BODY --}}
        @include('partials.how_to_use_content')
        {{-- END MODAL BODY --}}
      </div>
    </div>
  </div>
</div>
