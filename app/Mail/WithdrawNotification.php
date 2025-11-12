<?php

namespace App\Mail;

use App\Model\AccountWithdraw;
use FriendsOfHyperf\Mail\Mailable;
use FriendsOfHyperf\Mail\Mailable\Content;
use FriendsOfHyperf\Mail\Mailable\Envelope;

class WithdrawNotification extends Mailable
{

    /**
     * Create a new message instance.
     */
    public function __construct(protected readonly AccountWithdraw $withdraw)
    {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Withdraw Notification',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.withdraw_sucess',
            with: [
                'userName' => $this->withdraw->account->name,
                'withdrawId' => $this->withdraw->id,
                'amount' => $this->withdraw->amount,
                'pixKey' => $this->withdraw->pix?->key,
                'scheduled_for' => $this->withdraw->scheduled_for ?? $this->withdraw->updated_at,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \FriendsOfHyperf\Mail\Mailable\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
