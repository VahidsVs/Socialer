<x-layout docTitle="Manage Avatar">
    <div class="container container--narrow py-md-5">
<h2 class="text=center-mb3">Upload an Avatar Image</h2>
<form action="/manage-avatar" method="POST" enctype="multipart/form-data">
@csrf
<div class="mb3">
    <input type="file" name="avatar" >
    @error('avatar')
    <p class="m-0 small alert alert-danger shadow-dm">{{ $message }}</p>

    @enderror
</div>
<br>
<button class="btn btn-primary">Save</button>
</form>


    </div>
</x-layout>