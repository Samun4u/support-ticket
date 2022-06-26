<form wire:submit.prevent="updateTicket" >
    @if ($ticketFilePreview)
        <div class="mb-3">
            <embed src="http://127.0.0.1:8000/storage/{{$ticketFilePreview}}" type="">
                <p wire:click="removeUpdatePreviewFile">remove</p>
        </div>
    @endif
    @if (session()->has('message'))
    <div class="alert alert-success" >
        {{ session('message') }}
    </div>
@endif
        <div class="mb-3">
            <input type="hidden"  wire:model="ticketUpdateId" class="form-control">
        </div>
        
    <div class="mb-3">
        <input type="file" id="ticketImage" wire:model="ticketFile"  class="form-control">
    </div>
    <div class="mb-3">
        <input class="form-control" type="text" wire:model="newTicket">
        @error('newTicket')
            <span style="color: red">{{ $message }}</span>
        @enderror
    </div>
    <div class="d-grid gap-2">
        <button class="btn btn-primary" type="submit">Update</button>
        <button class="btn btn-primary" type="button" wire:click="cancel">Cancel</button>
    </div>
</form>