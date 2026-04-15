<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Subtask;

class SubtaskCreatedEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $subtask;
    public $senderName;
    public $recipientName;
    public $isEdit;

    public function __construct(Subtask $subtask, $senderName, $recipientName, $isEdit = false)
    {
        $this->subtask = $subtask;
        $this->senderName = $senderName;
        $this->recipientName = $recipientName;
        $this->isEdit = $isEdit;
    }

    public function build()
    {
        $subject = $this->isEdit
            ? 'Updated Subtask: ' . $this->subtask->stask_name
            : 'New Subtask Assigned: ' . $this->subtask->stask_name;

        return $this->subject($subject)
                    ->view('dashboard.hr.email.subtasktemp')
                    ->with([
                        'subtask' => $this->subtask,
                        'senderName' => $this->senderName,
                        'recipientName' => $this->recipientName,
                        'isEdit' => $this->isEdit,
                    ]);
    }
}
