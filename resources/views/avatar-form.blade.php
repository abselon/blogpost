<x-layout>
    <div class="container container--narrow py-md-5">
    <h2 class="text-center mb-3">Upload a new avatar</h2>
    <form action="/manage-avatar" method="POST" enctype="multipart/form-data"> <!-- MUST INCLUDE THIS FOR ATTACHING FILES enctype="multipart/form-data-->
    @csrf
    <div class="mb-3">
        <input type="file" name="avatar">
        @error('avatar')
        <p class="alert small alert-danger shadow-sm">Avatar is required</p>
        @enderror
    </div>
    <button class="btb btn-primary">Save</button>
</form>
    </div>
</x-layout>