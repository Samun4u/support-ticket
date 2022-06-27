<?php

namespace App\Http\Livewire;

use App\Models\Attachment;
use App\Models\Comment;
use App\Models\SupportTicket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class Comments extends Component
{
    use WithFileUploads;
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $commentFile;
    public $newComment;
    public $ticketId;
    public $updateMode=false;
    public $commentUpdateId;
    public $commentFilePreview;
    protected $listeners = [
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

       
        $storedFile = $this->storeFile();
        if($this->ticketId == null)
        {
            session()->flash('message', 'Ticket Not Found! Please Select Ticket');
            $this->emit('alert_remove');
            return;
           
        }
        DB::beginTransaction();
        if ($storedFile) {
            $attachment = Attachment::create([
                'file_name' =>  $storedFile['name'],
                'file_path' =>  Storage::url($storedFile['name']),
                'file_size' =>  $storedFile['size'],
                'file_type' =>  $storedFile['type'],
            ]);
        }
       
        $createdComment = Comment::create([
            'body' => $this->newComment,
            'user_id' => 1,
            'attachment_id' => $attachment->id ?? null,
            'ticket_id' => $this->ticketId,
        ]);
        DB::commit();

        

        $this->newComment = "";
        $this->commentFile = "";

        session()->flash('message', 'Successfully Add Comment');
        $this->emit('alert_remove');
        return;
    }

    public function storeFile()
    {
        if (!$this->commentFile) return null;

        $file['name'] = Str::random() . '.' . $this->commentFile->extension();
        $this->commentFile->storeAs('public', $file['name']);
        $file['size'] = $this->commentFile->getSize();
        $file['type'] = $this->commentFile->extension();
        return $file;
    }

     //edit file upload
     public function editFile()
     {
         if (!$this->commentFile && !$this->commentFilePreview) {
             return null;
         } elseif ($this->commentFile && $this->commentFilePreview) {
             $file['name'] = Str::random() . '.' . $this->commentFile->extension();
             $this->commentFile->storeAs('public', $file['name']);
             $file['size'] = $this->commentFile->getSize();
             $file['type'] = $this->commentFile->extension();
             return $file;
         } elseif (!$this->commentFile && $this->commentFilePreview) {
             $attachment = Attachment::where('file_name', $this->commentFilePreview)->firstOrFail();
             return $attachment->id;
         }
 
         $file['name'] = Str::random() . '.' . $this->commentFile->extension();
         $this->commentFile->storeAs('public', $file['name']);
         $file['size'] = $this->commentFile->getSize();
         $file['type'] = $this->commentFile->extension();
         return $file;
     }

     //File download
    public function downloadFile($attachmentID)
    {
        $attachment = Attachment::where('id', $attachmentID)->firstOrFail();

        $filePath = storage_path("app/public/" . $attachment->file_name);
        $headers = ['Content-Type: application/' . $attachment->file_type];
        $fileName = $attachment->file_name;
        return response()->download($filePath, $fileName, $headers);
    }

    public function edit($id)
    {
        $this->commentFilePreview = "";
        $comment = Comment::findOrFail($id);
        if ($comment->attachment_id) {
            $attachment = Attachment::where('id', $comment->attachment_id)->firstOrFail();
            $this->commentFilePreview = $attachment->file_name;
        }

        $this->newComment = $comment->body;
        $this->commentUpdateId = $id;
        $this->updateMode = true;
    }

    public function removeUpdatePreviewFile()
    {
        $this->commentFilePreview = "";
    }

    public function updateComment()
    {
        $attachmentID=null;
        $this->newComment;
        $this->commentUpdateId;
        $this->commentFile;

        DB::beginTransaction();
        $comment = Comment::findOrFail($this->commentUpdateId);
        $comment->body = $this->newComment;
        $comment->ticket_id =  $this->ticketId;
        $comment->user_id = 2;

        $updateFile = $this->editFile();


        if (gettype($updateFile) == "array") {

            $attachment = Attachment::create([
                'file_name' =>  $updateFile['name'],
                'file_path' =>  Storage::url($updateFile['name']),
                'file_size' =>  $updateFile['size'],
                'file_type' =>  $updateFile['type'],
            ]);

            $attachmentID =$attachment->id;
        } elseif (gettype($updateFile) == "integer") {
            $attachmentID = $updateFile;
        }
        $comment->attachment_id = $attachmentID;
        $comment->save();
        DB::commit();
        $this->newComment = "";
        $this->commentFile = "";
        $this->updateMode = false;
        session()->flash('message', 'Successfully Updated Ticket');
        $this->emit('alert_remove');
        return;
    }

    public function cancel()
    {
        $this->newComment = "";
        $this->commentUpdateId = "";
        $this->commentFile = "";
        $this->updateMode = false;
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
        return view('livewire.comments', ['comments' => Comment::where('ticket_id',$this->ticketId)->latest()->paginate(2)]);
    }
}
