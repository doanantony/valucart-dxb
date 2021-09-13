<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Models\Customer;

class CustomerPasswordChanged extends Mailable
{
    use Queueable, SerializesModels;

    public $customer = null;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        
        return $this->from('customer-service@valucart.com', 'Valucart customer service')
                    ->view('email.customer_password_change')
                    ->text('email.customer_password_change_text');

    }
}
