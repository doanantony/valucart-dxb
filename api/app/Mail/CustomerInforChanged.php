<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Models\Customer;

class CustomerInforChanged extends Mailable
{
    use Queueable, SerializesModels;

    public $customer;

    public $email_verification_code;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Customer $customer, $email_verification_code = null)
    {
        $this->customer = $customer;
        $this->email_verification_code = $email_verification_code;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        
        return $this->from('customer-service@valucart.com', 'Valucart customer service')
                    ->view('email.customer_info_changed')
                    ->text('email.customer_info_changed_text');

    }
}
