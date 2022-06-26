<form wire:submit.prevent="addTicket">
    @if ($ticketFile)
        <div class="mb-3">
            
        </div>
    @endif
    @if (session()->has('message'))
    <div class="alert alert-success" >
        {{ session('message') }}
    </div>
@endif
    <div class="mb-3">
        <input type="file" id="ticketImage" wire:model="ticketFile" class="form-control">
    </div>
    <div class="mb-3">
        <input class="form-control" type="text" wire:model="newTicket">
        @error('newTicket')
            <span style="color: red">{{ $message }}</span>
        @enderror
    </div>
    <div class="d-grid gap-2">
        <button class="btn btn-primary" type="submit">Add</button>
    </div>
</form>