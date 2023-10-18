<form action="{{ route('user.destroy', $post->id) }}" method="post">
  @csrf
  @method('DELETE')
  <h5 class="text-center">Are you sure you want to delete ?</h5>
  <button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>
  <button type="submit" class="btn btn-light">Yes, Delete User</button>
</form>