<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VoteVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $vote;
    public $token;

    public function __construct($vote, $token)
    {
        $this->vote = $vote;
        $this->token = $token;
    }

    public function build()
    {
        $verificationUrl = route('vote.verify', ['token' => $this->token]);

        return $this->subject('Verify Your Vote')
            ->view('emails.vote-verification', [
                'candidate' => $this->vote->candidate->user->full_name,
                'election' => $this->vote->election->name,
                'verificationUrl' => $verificationUrl
            ]);
    }
}