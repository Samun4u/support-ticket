<div>
            <div class="row">
                <h2>Comments</h2>
                @include('livewire.create_comment')
            </div>
            @foreach ($comments as $comment)
                <div class="row">
                    <div class="card mb-4">
                        <div class="card-body">
                            @if ($comment->attachment_id)
                                <img src="{{ $comment->commentAttachment->file_path }}" class="img-fluid mb-1" alt="image">
                            @endif
                            <div class="d-flex justify-content-between">
                                <div class="d-flex flex-row align-items-center">
                                    <p class="small mb-0 ms-2"><strong> {{ $comment->body }}</strong></p>
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
    window.livewire.on('fileChoose', () => {
        let inputFile = document.getElementById('image');
        let file = inputFile.files[0];
        let reader = new FileReader();

        reader.onloadend = () => {
            window.livewire.emit('fileUpload', reader.result);
        }
        reader.readAsDataURL(file);


    });

    window.livewire.on('alert_remove',()=>{
                setTimeout(function(){ $(".alert-success").slideUp('slow');
                }, 3000); // 3 secs
            });

</script>
