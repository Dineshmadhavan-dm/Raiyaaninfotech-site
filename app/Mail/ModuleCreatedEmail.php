<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Modulo;

class ModuleCreatedEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $modulo;
    public $senderName;
    public $recipientName;
    public $isEdit;

    public function __construct(Modulo $modulo, $senderName, $recipientName, $isEdit = false)
    {
        $this->modulo = $modulo;
        $this->senderName = $senderName;
        $this->recipientName = $recipientName;
        $this->isEdit = $isEdit;
    }

    public function build()
    {
        $subject = $this->isEdit
            ? 'Updated Module: ' . $this->modulo->mod_name
            : 'New Module Assigned: ' . $this->modulo->mod_name;

        return $this->subject($subject)
                    ->view('dashboard.hr.email.moduletemp')
                    ->with([
                        'modulo' => $this->modulo,
                        'senderName' => $this->senderName,
                        'recipientName' => $this->recipientName,
                        'isEdit' => $this->isEdit,
                    ]);
    }
}
