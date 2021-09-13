<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Models\Customer;

class CustomerAccountRecovery extends Mailable
{
    use Queueable, SerializesModels;

    public $customer = null;

    public $code = null;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Customer $customer, $code)
    {
        $this->customer = $customer;
        $this->code = $code;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        
        return $this->from('customer-service@valucart.com', 'Valucart customer service')
                    ->view('email.customer_account_recovery')
                    ->text('email.customer_account_recovery_text');

    }
}
