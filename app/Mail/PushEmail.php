<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PushEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if(isset($this->data['file'])){
            return $this->from('support@flexhealth.me')
            ->subject($this->data['subject'])
            ->view('mail.pushemail')
            ->attach($this->data['file']->getRealPath(),
            [
                'as' => $this->data['file']->getClientOriginalName(),
                'mime' => $this->data['file']->getClientMimeType(),
            ])
            ->with('data', $this->data);
        }
        else{
            return $this->from('support@flexhealth.me')
            ->subject($this->data['subject'])
            ->view('mail.pushemail')
            ->with('data', $this->data);
        }
        
    }
}
