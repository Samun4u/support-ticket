<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SupportTicket extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['subject','ticket_number','user_id','assign_to','attachment_id','status'];

    public function ticketAttachment()
    {
        return $this->belongsTo(Attachment::class,'attachment_id','id');
    }

    
}
