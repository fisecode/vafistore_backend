<!-- resources/views/components/delete-form.blade.php -->

<form action="{{ $action }}" method="POST" style="display: inline;">
    @csrf
    @method('DELETE')
    <button type="submit" onclick="return confirm('Are you sure want to remove this data?');"
        class="btn btn-label-secondary btn-sm">Hapus</button>
</form>
