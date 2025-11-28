<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ConfirmEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $id;

    public function __construct($user, $id)
    {
        $this->user = $user;
        $this->id = $id;
    }

    public function build()
    {
        return $this->subject('Konfirmasi Email Anda')
                ->view('auth.emails')
                ->with([
                    'name' => $this->user->name,
                    'confirmationUrl' => url('/confirm-email/' . $this->id),
                ]);
    }
}