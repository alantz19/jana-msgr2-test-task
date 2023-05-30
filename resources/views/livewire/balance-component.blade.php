<div class="w-32 h-5">
    <div>Balance: <b>{{ $balance }}</b></div>
</div>


<script>
    document.addEventListener('livewire:load', function () {
        setInterval(function () {
            Livewire.emit('balanceUpdated')
        }, 5000);
    });
</script>