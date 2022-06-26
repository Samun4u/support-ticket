<div>
   
    <div class="row">
        <h2>Support Ticket</h2>
        @if($updateMode)
        @include('livewire.update_ticket')
        @else
        @include('livewire.create_ticket')
        @endif
    </div>
    <div class="row mt-3">
        @foreach ($tickets as $ticket )
        <div wire:click = "$emit('ticketSelected',{{$ticket->id}})">
        @if($ticket->attachment_id)
        <a href="#"  wire:click="downloadFile({{$ticket->attachment_id}})">{{$ticket->ticketAttachment->file_name}}</a>
      
        @endif
       
        <div class="{{$active == $ticket->id ? 'bg-success p-2 text-white ':''}}">
            <h3>{{$ticket->subject}}</h3> <p  class="text-white" wire:click="edit({{$ticket->id}})">edit</p>
        </div>
    </div>
        @endforeach
        {{ $tickets->links() }}
    </div>
   
</div>
<script>
    ticketImage.onchange = evt => {
  const [file] = ticketImage.files
  if (file) {
    blah.src = URL.createObjectURL(file)
  }
}
</script>