<div>
            <div class="row">
                <h2>Ticket Reply</h2>
                @if($updateMode)
                @include('livewire.update_comment')
                @else
                @include('livewire.create_comment')
                @endif
                
            </div>
            @foreach ($comments as $comment)
                <div class="row">
                    <div class="card mb-4">
                        <div class="card-body">
                            @if ($comment->attachment_id)
                                <a href="#"  wire:click="downloadFile({{$comment->attachment_id}})">{{$comment->commentAttachment->file_name}}</a>
                            @endif
                            <div class="d-flex justify-content-between">
                                <div class="d-flex flex-row align-items-center">
                                    <p class="small mb-0 ms-2"><strong> {{ $comment->body }}</strong></p><p   wire:click="edit({{$comment->id}})">edit</p>
                                </div>
                                <div class="d-flex flex-row align-items-center">
                                    <p class="small  mb-0 text-danger" wire:click="remove({{ $comment->id }})">remove
                                    </p>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <div class="d-flex flex-row align-items-center">
                                    <p class="small mb-0 ms-2">{{ $comment->creator->name }}</p>
                                </div>
                                <div class="d-flex flex-row align-items-center">
                                    <p class="small text-muted mb-0">
                                        {{ \Carbon\Carbon::parse($comment->created_at)->diffForhumans() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
            @endforeach

            {{ $comments->links() }}
</div>

<script>
    $(document).ready(function(){
            window.livewire.on('alert_remove',()=>{
                setTimeout(function(){ $(".alert-success").fadeOut('fast');
                }, 3000); // 3 secs
            });
        });
</script>
