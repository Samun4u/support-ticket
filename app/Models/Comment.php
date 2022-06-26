<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Comment extends Model
{
    use HasFactory;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['body','user_id','image','ticket_id','attachment_id'];

    public function creator()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function commentAttachment()
    {
        return $this->belongsTo(Attachment::class,'attachment_id','id');
    }
    
}
