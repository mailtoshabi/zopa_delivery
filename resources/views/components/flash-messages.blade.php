<div class="flash-messages">
<script>
    @if (session('success'))
        showToast('{{ session('success') }}', 'success');
    @endif

    @if (session('error'))
        showToast('{{ session('error') }}', 'danger');
    @endif
</script>
</div>
