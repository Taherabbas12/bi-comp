</div> {{-- إغلاق container من head partial --}}

<script>
    function toggleHideExpired(hide) {
        const params = new URLSearchParams(window.location.search);
        if (hide) {
            params.set('hide_expired', '1');
        } else {
            params.delete('hide_expired');
        }
        window.location.search = params.toString();
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
