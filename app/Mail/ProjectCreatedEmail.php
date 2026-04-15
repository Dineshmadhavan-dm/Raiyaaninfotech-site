<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Project;

class ProjectCreatedEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $project;
    public $senderName;
    public $recipientName;
    public $isEdit;

    public function __construct(Project $project, $senderName, $recipientName, $isEdit = false)
    {
        $this->project = $project;
        $this->senderName = $senderName;
        $this->recipientName = $recipientName;
        $this->isEdit = $isEdit;
    }

    public function build()
    {
        $subject = $this->isEdit
            ? 'Updated Project: ' . $this->project->pro_name
            : 'New Project Created: ' . $this->project->pro_name;

        return $this->subject($subject)
                    ->view('dashboard.hr.email.projecttemp')
                    ->with([
                        'project' => $this->project,
                        'senderName' => $this->senderName,
                        'recipientName' => $this->recipientName,
                        'isEdit' => $this->isEdit,
                    ]);
    }
}
