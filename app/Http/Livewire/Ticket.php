<?php

namespace App\Http\Livewire;

use App\Models\Attachment;
use App\Models\SupportTicket;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Str;



class Ticket extends Component
{

    use WithFileUploads;
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $active;
    public $ticketFile;
    public $newTicket;
    public $updateMode = false;
    public $ticketUpdateId;
    public $ticketFilePreview;



    protected $listeners = [
        'ticketSelected'
    ];

    public function ticketSelected($ticketID)
    {
        $this->active = $ticketID;
    }



    public function mount()
    {
        $ticket = SupportTicket::orderBy('id', 'desc')->first();
        if ($ticket) {
            $this->active = $ticket->id;
        }
    }



    public function updated($filed)
    { 
        $this->validateOnly($filed, [
            'newTicket' => 'required|max:255'
        ]);
    }

    public function addTicket()
    {
        $this->validate([
            'newTicket' => 'required|max:255',
        ]);

        $storedFile = $this->storeFile();
        DB::beginTransaction();
        if ($storedFile) {
            $attachment = Attachment::create([
                'file_name' =>  $storedFile['name'],
                'file_path' =>  Storage::url($storedFile['name']),
                'file_size' =>  $storedFile['size'],
                'file_type' =>  $storedFile['type'],
            ]);
        }


        $createdTicket = SupportTicket::create([
            'subject' => $this->newTicket,
            'ticket_number' => Str::random(),
            'user_id' => 1,
            'assign_to' => 1,
            'attachment_id' => $attachment->id ?? null,
            'status' => 0,
        ]);
        DB::commit();

      

        $this->newTicket = "";
        $this->ticketFile = "";

        session()->flash('message', 'Successfully Add Ticket');
        $this->emit('alert_remove');
        return;
       
    }

    //add file upload
    public function storeFile()
    {
        if (!$this->ticketFile) return null;

        $file['name'] = Str::random() . '.' . $this->ticketFile->extension();
        $this->ticketFile->storeAs('public', $file['name']);
        $file['size'] = $this->ticketFile->getSize();
        $file['type'] = $this->ticketFile->extension();
        return $file;
    }

   

    //edit file upload
    public function editFile($ticketID)
    {
        if (!$this->ticketFile && !$this->ticketFilePreview) {
            $t=SupportTicket::where("id",$ticketID)->first();
            if($t->attachment_id)
            {
              $a= Attachment::where('id',$t->attachment_id)->first();
              $a->delete();
              return null;
            }
            return null;
        } elseif ($this->ticketFile && $this->ticketFilePreview) {
            $t=SupportTicket::where("id",$ticketID)->first();
            $a= Attachment::where('id',$t->attachment_id)->first();
            $a->delete();

            $file['name'] = Str::random() . '.' . $this->ticketFile->extension();
            $this->ticketFile->storeAs('public', $file['name']);
            $file['size'] = $this->ticketFile->getSize();
            $file['type'] = $this->ticketFile->extension();
            return $file;
        } elseif (!$this->ticketFile && $this->ticketFilePreview) {
            $attachment = Attachment::where('file_name', $this->ticketFilePreview)->firstOrFail();
            return $attachment->id;
        }elseif ($this->ticketFile && !$this->ticketFilePreview) {
            $t=SupportTicket::where("id",$ticketID)->first();
            if($t->attachment_id)
            {
              $a= Attachment::where('id',$t->attachment_id)->first();
              $a->delete();

              $file['name'] = Str::random() . '.' . $this->ticketFile->extension();
            $this->ticketFile->storeAs('public', $file['name']);
            $file['size'] = $this->ticketFile->getSize();
            $file['type'] = $this->ticketFile->extension();
            return $file;
            }
            $file['name'] = Str::random() . '.' . $this->ticketFile->extension();
            $this->ticketFile->storeAs('public', $file['name']);
            $file['size'] = $this->ticketFile->getSize();
            $file['type'] = $this->ticketFile->extension();
            return $file;
        }

       
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
        $this->ticketFilePreview = "";
        $ticket = SupportTicket::findOrFail($id);
        if ($ticket->attachment_id) {
            $attachment = Attachment::where('id', $ticket->attachment_id)->firstOrFail();
            $this->ticketFilePreview = $attachment->file_name;
        }

        $this->newTicket = $ticket->subject;
        $this->ticketUpdateId = $id;
        $this->updateMode = true;
    }

    public function removeUpdatePreviewFile()
    {
        $this->ticketFilePreview = "";
    }

    

    public function updateTicket()
    {
        $attachmentID=null;
        $this->newTicket;
        $this->ticketUpdateId;
        $this->ticketFile;
        $updateFile = $this->editFile($this->ticketUpdateId);
        DB::beginTransaction();
        $ticket = SupportTicket::findOrFail($this->ticketUpdateId);
        $ticket->subject = $this->newTicket;
        $ticket->ticket_number =  $ticket->ticket_number;
        $ticket->user_id = 2;
        $ticket->assign_to = 2;

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

        $ticket->attachment_id = $attachmentID;
        $ticket->save();
        DB::commit();
        $this->newTicket = "";
        $this->ticketFile = "";
        $this->updateMode = false;
        session()->flash('message', 'Successfully Updated Ticket');
        $this->emit('alert_remove');
        return;
    }

    public function cancel()
    {
        $this->newTicket = "";
        $this->ticketUpdateId = "";
        $this->ticketFile = "";
        $this->updateMode = false;
    }

    

    public function render()
    {

        return view('livewire.ticket', ['tickets' => SupportTicket::latest()->paginate(2)]);
    }
}
