<?php

namespace App\Http\Livewire;

use App\Models\Attachment;
use App\Models\Comment;
use App\Models\SupportTicket;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Intervention\Image\ImageManagerStatic;
use Illuminate\Support\Str;

class Comments extends Component
{
    use WithFileUploads;
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $image;
    public $newComment;
    public $ticketId;
    protected $listeners = [
        'fileUpload' => 'uploadFileHandle',
        'ticketSelected',
    ];

    public function ticketSelected($ticketID)
    {
        $this->ticketId = $ticketID;
    }
    public function mount()
    {
        $ticket=SupportTicket::orderBy('id','desc')->first();
        if($ticket){
            $this->ticketId = $ticket->id;
        }
       
       
    }
    protected $messages = [
        'newComment.required' => 'This comment field is required'
    ];
    public function uploadFileHandle($imageData)
    {
        $this->image = $imageData;
    }
    public function updated($field)
    {
        $this->validateOnly($field, [
            'newComment' => 'required|max:255'
        ]);
    }

    //Add Comment
    public function addComment()
    {

        $this->validate([
            'newComment' => 'required|max:255'
        ]);
        $image = $this->storeImage();
        if($image)
       {
        $attachment = Attachment::create([
            'file_name' =>  $image,
            'file_path' =>  Storage::url($image),
        ]);
       }
        $createdComment = Comment::create([
            'body' => $this->newComment,
            'user_id' => 1,
            'attachment_id' => $attachment->id ?? null,
            'ticket_id' => $this->ticketId,
        ]);

        $this->newComment = "";
        $this->image = "";

        session()->flash('message', 'Successfully Add Comment');
        $this->emit('alert_remove');
        return;
    }

    public function storeImage()
    {
        if (!$this->image) return null;

        $img = ImageManagerStatic::make($this->image)->encode('jpg');
        $name = Str::random() . '.jpg';
        Storage::disk('public')->put($name, $img);
        return $name;
    }

    public function remove($commentID)
    {

        $comment = Comment::find($commentID);
        $comment->delete();
        session()->flash('message', 'Successfully Remove Comment 😃');
        $this->emit('alert_remove');
        return;
    }


    public function render()
    {
        return view('livewire.comments', ['comments' => Comment::where('ticket_id',$this->ticketId ?? 1)->latest()->paginate(2)]);
    }
}
