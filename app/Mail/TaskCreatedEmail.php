<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Task;

class TaskCreatedEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $task;
    public $senderName;
    public $recipientName;
    public $isEdit;

    public function __construct(Task $task, $senderName, $recipientName, $isEdit = false)
    {
        $this->task = $task;
        $this->senderName = $senderName;
        $this->recipientName = $recipientName;
        $this->isEdit = $isEdit;
    }

    public function build()
    {
        $subject = $this->isEdit
            ? 'Updated Task: ' . $this->task->task_name
            : 'New Task Assigned: ' . $this->task->task_name;

        return $this->subject($subject)
                    ->view('dashboard.hr.email.tasktemp')
                    ->with([
                        'task' => $this->task,
                        'senderName' => $this->senderName,
                        'recipientName' => $this->recipientName,
                        'isEdit' => $this->isEdit,
                    ]);
    }
}
