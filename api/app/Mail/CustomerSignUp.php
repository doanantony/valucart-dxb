<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Models\Customer;

class CustomerSignUp extends Mailable
{
    use Queueable, SerializesModels;

    public $customer = null;

    public $verification_code = null;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Customer $customer, $verification_code)
    {
        $this->customer = $customer;
        $this->verification_code = $verification_code;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        
        return $this->from('customer-service@valucart.com', 'Valucart customer service')
                    ->view('email.customer_signup')
                    ->text('email.customer_signup_text');

    }
}
