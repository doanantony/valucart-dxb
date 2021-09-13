<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">
<title>Order placed. Reference: {{ $snapshot['reference'] }}</title>
<style>

html {
    font-size: 100%;
    width: 100%;
    height: 100%;
}

html * {
    box-sizing: border-box;
}

body {
    font-size: 100%;
    margin: 0;
    padding: 0;
}

.container {
    width: 100%;
    max-width: 960px;
    margin: 24px auto;
    padding: 12px;
    font-size: 13px;
    line-height: 1.5em;
    font-family: Arial, sans-serif;
    color: #2c3b52;
}

.clear {
    display: table;
    clear: both;
}

.float-left {
    float: left;
}

.float-right {
    float: right;
}

.data-wrapper {
    padding: 3px 0;
    width: 100%;
}

.data-wrapper:after {
    content: " ";
    display: table;
    clear: both;
}

.data-wrapper .label {
    float: left;
    width: 30%;
    font-size: 1.1em;
    font-weight: 600;
}

.data-wrapper .value {
    float: left;
    width: 70%;
}

.data-wrapper a {
    color: #06c;
    text-decoration: none;
}

/* Header */

.page-header {
    width: 100%;
}

.page-header .identity-container {
    margin-bottom: 24px;
}

.page-header .identity-container .logo {
    width: 150px;
    margin-bottom: 16px;
}

.page-header .identity-container .logo .img {
    width:100%;
}

.page-header .identity-container .data-wrapper a {
    color: #06c;
    text-decoration: none;
}

.page-header .invoice-customer-information {
    
}

.page-header .invoice-customer-information .title {
    font-size: 2.1em;
    font-weight: 600;
    padding: 12px 0;
    color: #2c3b52;
    margin-bottom: 6px;
    text-transform: uppercase;
}

.page-header .invoice-customer-information .invoice-information-container .data-wrapper .value {
    font-weight: 600;
    color: #767d88;
}

.page-header .invoice-customer-information .customer-information-container {
    margin: 24px 0;
    background-color: #eaeaea;
    padding: 12px;
    border-radius: 3px;
}

.page-header .invoice-customer-information .customer-information-container .heading {
    font-size: 1.2em;
    font-weight: 600;
    color: #2c3b52;
    margin: 0 0 12px;
    padding: 6px 0;
}

/* Header end */

/* Table */

table, tr, td {
    padding: 0;
    margin: 0;
}

td {
    padding: 8px;
}

tbody.styled tr:nth-child(even) {
    background-color: #f6f6f6;
}

tbody.styled2 tr td {
    border-bottom: 1px solid #e9e9e9;
}

tbody.styled2 tr:last-child td {
    border-bottom: none;
}

table.products {
    width: 100%;
    font-size: 1.1em;
    padding: 0;
    margin: 16px 0;
}

table.products thead {
    font-size: 1em;
    font-weight: 600;
    background-color: #e3e3e3;
}

table.products thead tr td {
    padding: 12px 8px;
}

table.products thead tr td:first-child {
    width: 50%;
}

table.products tbody tr {
    padding: 12px 0;
}

/* Table end */

/* Items */

.bundles-container {
    margin-bottom: 45px;
}

.bundles-container > header {
    font-size: 1.6em;
    font-weight: 600;
    padding: 24px 0 16px;
}

.bundles-container .bundle {
    border: 1px solid #eee;
    padding: 8px;
    margin: 0 0 16px;
}

.bundles-container .bundle .header {

}

.bundles-container .bundle .bundle-header header{
    font-size: 1.2em;
    font-weight: 600;
    padding: 8px 6px;
    color: #1d242d;
}

.bundles-container .bundle .bundle-header .meta-data {
    padding: 0 6px;
}

.bundles-container .bundle .bundled-items {
    width: 100%;
}

.bundles-container .bundle .bundled-items table {
    width: 100%;
    font-size: 1.1em;
    padding: 0;
    margin: 16px 0 0;
}

.bundles-container .bundle .bundled-items table thead{
    font-size: 1em;
    font-weight: 600;
    background-color: #f6f6f6;
}
.bundles-container .bundle .bundled-items table thead tr td:first-child {
    width: 40%;
}

.bundles-container .bundle .bundled-items table thead tr td:last-child {
    /* width: 20%; */
}

/* Items end */


.payment-information {
    
}

.payment-information .left {
    margin: 0 0 12px;
}

.payment-information .right {
    border: 1px solid #eee;
    margin: 0 0 12px;
    border-radius: 3px;
}

.payment-information .left .payment-information {
    border: 1px solid #eee;
    padding: 12px;
    border-radius: 3px;
}

.payment-information .left .payment-information .heading {
    font-size: 1.2em;
    font-weight: 600;
    padding: 0  0 12px;
    border-bottom: 1px solid #eee;
    min-width: 320px;
    margin-bottom: 12px;
}

.payment-information .left .payment-information .info {
    font-size: 1.1em;
    margin-bottom: 8px;
}

.payment-information .left .payment-information .info .label {
    font-weight: 600;
    float: left;
    width: 40%;
}

.payment-information .left .payment-information .info .value {
    float: left;
}

.payment-information-table {
    width: 100%;
    font-size: 1.2em;
    padding: 0;
}

.payment-information-table tbody {
    font-size: .9em
}

.payment-information-table tbody tr td:first-child {
    width: 30%;
    padding-top: 10px;
    padding-bottom: 10px;
    font-weight: 600;
}

.payment-information-table tbody tr td:last-child {
    width: 70%;
    padding-top: 10px;
    padding-bottom: 10px;
}

.payment-information-table tfoot {
    font-size: 1em;
    font-weight: 600;
    background-color: #f6f6f6;
}

.payment-information-table tfoot tr td:first-child {
    width: 30%;
    padding-top: 16px;
    padding-bottom: 16px;
    font-weight: 600;
}

.payment-information-table tfoot tr td:last-child {
    width: 70%;
    padding-top: 16px;
    padding-bottom: 16px;
}

.terms-conditions {
    padding: 8px;
    margin-top: 12px;
    border-top: 1px solid #eee;

    /* -webkit-columns: 2;
    -moz-columns: 2;
    columns: 2;

    -webkit-column-gap: 2em;
    -moz-column-gap: 2em;
    column-gap: 2em; */
}

@media (min-width: 720px) {
  
    .container {
        font-size: 14px;
        line-height: 1.5em;
    }

    /* Header */

    .page-header:after {
        content: " ";
        display: table;
        clear: both;
    }

    .page-header .identity-container {
        width: 50%;
        margin-bottom: 24px;
        float: left;
    }

    .page-header .invoice-customer-information {
        width: 50%;
        margin-bottom: 24px;
        float: left;
    }

    /* Header end */

    .bundles-container .bundle .bundle-header {
        width: 40%;
        float: left;
    }

    .bundles-container .bundle .bundled-items {
        width: 60%;
        float: left;
    }

    .bundles-container .bundle .bundled-items table {
        margin: 0 !important;
    }

    .bundles-container .bundle .bundled-items table thead tr td:first-child {
        width: 80%;
    }

    .payment-information .left {
        width: 40%;
        float: left;
    }

    .payment-information .right {
        width: 50%;
        float: right;
    }

}

@media print {
  
    .container {
        font-size: 10px;
        line-height: 1.2em;
    }

    /* Header */

    .page-header:after {
        content: " ";
        display: table;
        clear: both;
    }

    .page-header .identity-container {
        width: 50%;
        margin-bottom: 24px;
        float: left;
    }

    .page-header .identity-container .logo {
        width: 120px;
    }

    .page-header .invoice-customer-information {
        width: 50%;
        margin-bottom: 0;
        float: left;
    }

    .page-header .invoice-customer-information .title {
        font-size: 1.8em;
    }

    .page-header .invoice-customer-information .invoice-information-container .data-wrapper {
        font-size: 1em;
        line-height: 1;
    }

    .page-header .invoice-customer-information .customer-information-container {
        margin: 6px 0;
        background-color: #fff;
        padding: 6px 0;
    }

    .page-header .invoice-customer-information .customer-information-container .heading {
        font-size: 1em;
        font-weight: 600;
        color: #2c3b52;
        margin: 0;
        padding: 3px 0;
    }

    /* Header end */

    .bundles-container .bundle .bundle-header {
        width: 30%;
        float: left;
    }

    .bundles-container .bundle .bundled-items {
        width: 70%;
        float: left;
    }

    .bundles-container .bundle .bundled-items table {
        margin: 0 !important;
    }

    .bundles-container .bundle .bundled-items table tr td:first-child {
        width: 80%;
    }

    .payment-information .left {
        width: 40%;
        float: left;
    }

    .payment-information .right {
        width: 50%;
        float: right;
    }

    .terms-conditions {
        font-size: .8em
    }

    table.products {
        width: 100%;
        font-size: 1em;
        padding: 0;
        margin: 8px 0;
        border: 1px solid #f6f6f6;
    }

    table.products thead {
        font-size: 1em;
        font-weight: 600;
        background: #eee !important;
    }

    table.products thead tr td {
        padding: 6px 8px;
        border-bottom: 1px solid #f6f6f6;
    }

    table.products thead tr td:first-child {
        width: 50%;
    }

    table.products tbody tr {
        padding: 12px 0;
    }
    table.products tbody tr td {
        padding: 4px 8px;
        border-bottom: 1px solid #f6f6f6;
    }

    table.products tbody tr:last-child td {
        border-bottom: none;
    }

    .payment-information .left {
        margin: 0 0 6px;
    }

    .payment-information .right {
        border: 1px solid #eee;
        margin: 0 0 6px;
        border-radius: 3px;
    }

    .payment-information .left .payment-information {
        border: 1px solid #eee;
        padding: 12px;
        border-radius: 3px;
    }

    .payment-information .left .payment-information .heading {
        font-size: 1em;
        font-weight: 600;
        padding: 0  0 12px;
        border-bottom: 1px solid #eee;
        width: 100%;
        min-width: 100%;
        margin-bottom: 12px;
    }

    .payment-information .left .payment-information .info {
        font-size: 1em;
        margin-bottom: 8px;
    }

    .payment-information .left .payment-information .info .label {
        font-weight: 600;
        float: left;
        width: 40%;
    }

    .payment-information .left .payment-information .info .value {
        float: left;
    }

    .payment-information-table {
        font-size: 1em;
    }

    .payment-information-table tbody {
        font-size: 1em
    }

    .payment-information-table tbody tr td:first-child {
        width: 30%;
        padding: 6px 8px;
        font-weight: 600;
    }

    .payment-information-table tbody tr td:last-child {
        width: 70%;
        padding: 8px 0;
    }

    .payment-information-table tfoot {
        font-size: 1em;
        font-weight: 600;
        background-color: #f6f6f6;
    }

    .payment-information-table tfoot tr td:first-child {
        width: 30%;
        padding: 8px;
        font-weight: 600;
    }

    .payment-information-table tfoot tr td:last-child {
        width: 70%;
        padding: 8px 0;
    }

    .bundles-container > header {
        font-size: 1.4em;
        font-weight: 600;
        padding: 12px 0 8px;
    }

    .bundles-container .bundle .bundle-header header{
        font-size: 1em;
        font-weight: 600;
        padding: 3px 6px;
        color: #1d242d;
    }

    .bundles-container .bundle .bundle-header .meta-data {
        padding: 0 6px;
    }

    .bundles-container .bundle .bundle-header .meta-data .data-wrapper {
        padding: 2px 0;
    }
    .bundles-container .bundle .bundle-header .meta-data .data-wrapper .label {
        font-size: 1em;
    }

    .bundles-container .bundle .bundled-items table {
        width: 100%;
        font-size: 1em;
        padding: 0;
        margin: 8px 0 0;
    }

    .bundles-container .bundle .bundled-items table thead{
        font-size: 1em;
        font-weight: 600;
        background-color: #f6f6f6;
    }

    .bundles-container .bundle .bundled-items table thead tr td {
        padding: 3px 0;
    }

    .bundles-container .bundle .bundled-items table thead tr td:first-child {
        width: 50%;
    }

    .bundles-container .bundle .bundled-items table thead tr td:last-child {
        /* width: 20%; */
    }

    .bundles-container .bundle .bundled-items table tbody tr td {
        padding: 4px 0;
    }

}

</style>
</head>
<body>

    <div class="container">

        <div class="page-header">

            <div class="identity-container">
                
                <div class="logo">
                    <img src="https://valucart.com/img/logo.svg">
                </div>

                <div class="valucart-contact">
                    
                    <div class="data-wrapper">
                        Office 115 Building 27 (Garhoud star building) <br />
                        61A Street Al Garhoud Dubai <br />
                        United Arab Emirates
                    </div>
                    <div class="data-wrapper">0 (971) 4 223 1188</div>
                    <div class="data-wrapper">customercare@valucart.com</div>
                    <div class="data-wrapper">
                        <a href="https://valucart.com">valucart.com</a>
                    </div>

                </div>

            </div>

            <div class="invoice-customer-information">
                
                <div class="title">Invoice</div>

                <div class="invoice-information-container">

                    <div class="data-wrapper">
                        <div class="label">Order date:</div>
                        <div class="value">{{ $order->created_at->format("l jS F Y H:d") }}</div>
                        <div class="clear"></div>
                    </div>

                    <div class="data-wrapper">
                        <div class="label">Order ref:</div>
                        <div class="value">{{ $order->order_reference }}</div>
                        <div class="clear"></div>
                    </div>

                    <div class="data-wrapper">
                        <div class="label">Invoice No:</div>
                        <div class="value"> </div>
                        <div class="clear"></div>
                    </div>

                    <div class="data-wrapper">
                        <div class="label">TRN:</div>
                        <div class="value">100546479500003</div>
                    </div>

                </div>

                <div class="customer-information-container">

                    <div class="heading">Customer information:</div>

                    <div class="data-wrapper">
                        <div class="label">Name:</div>
                        <div class="value">{{ $order->customer->name }}</div>
                    </div>

                    <div class="data-wrapper">
                        <div class="label">Telephone:</div>
                        <div class="value">{{ $order->customer->phone_number }}</div>
                    </div>

                    <div class="data-wrapper">
                        <div class="label">Email:</div>
                        <div class="value">{{ $order->customer->contact_email }}</div>
                    </div>

                    <div class="data-wrapper">
                        <div class="label">Address:</div>
                        <div class="value">{{ $order->address }}</div>
                    </div>

                    <div class="data-wrapper">
                        <div class="label">Delivery date/time:</div>
                        <div class="value">{{ $snapshot['delivery_information']['date'] }}, {{ $snapshot['delivery_information']['time'] }}</div>
                    </div>

                </div>

            </div>

            <div style="clear: both;"></div>

        </div>
        
        @if(count($snapshot['products']) > 0)
        <table class="products" >
            
            <thead>
                <tr>
                    <td>Item</td>
                    <td>Qty</td>
                    <td>Unit Price</td>
                    <td>Total Price</td>
                    <td>Allow alt</td>
                </tr>
            </thead>

            <tbody class="styled">
                
            @foreach ($snapshot['products'] as $product)
                <tr>
                    <td>{{ $product['name'] }}</td>
                    <td>{{ $product['quantity'] }}</td>
                    <td>{{ $product['price'] }}</td>
                    <td>{{ $product['price'] * $product['quantity'] }}</td>
                    <td>{{ $product['allow_alternatives'] }}</td>
                </tr>
            @endforeach

            </tbody>
            
        </table>
        @endif
        
        <div class="bundles-container" >
        @if(count($snapshot['bundles']) || count($snapshot['customer_bundles']) > 0)
            <header>Bundles</header>
        @endif    
            <!-- Bundles -->
        
        @if(count($snapshot['bundles']) > 0)
            @foreach ($snapshot['bundles'] as $bundle)
                <div class="bundle" >
                    
                    <div class="bundle-header" >

                        <header>{{ $bundle['name'] }}</header>

                        <div class="meta-data">
                            
                            <div class="data-wrapper">
                                <div class="label">Qty:</div>
                                <div class="value">{{ $bundle['quantity'] }}</div>
                            </div>

                            <div class="data-wrapper">
                                <div class="label">Unit price:</div>
                                <div class="value">{{ $bundle['price'] }}</div>
                            </div>

                            <div class="data-wrapper">
                                <div class="label">
                                    Total price:
                                </div>
                                <div class="value">{{ $bundle['price'] * $bundle['quantity'] }}</div>
                            </div>

                        </div>
                        
                    </div>

                    <div class="bundled-items" >
                        <table>
                            
                            <thead>
                                <tr>
                                    <td>Bundled items</td>
                                    <td>Qty</td>
                                </tr>
                            </thead>

                            <tbody class="styled2">
                            @foreach ($bundle['products'] as $product)
                                <tr>
                                    <td>{{ $product['name'] }}</td>
                                    <td>{{ $product['quantity'] }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                            
                        </table>
                    </div>

                    <div style="clear: both;" ></div>
                
                </div>
            @endforeach
        @endif

            <!-- Customer bundles -->
        @if(count($snapshot['customer_bundles']) > 0)
            @foreach ($snapshot['customer_bundles'] as $bundle)
                <div class="bundle" >
                    
                    <div class="bundle-header" >

                        <header>{{ $bundle['name'] }}</header>

                        <div class="meta-data">
                            
                            <div class="data-wrapper">
                                <div class="label">Qty:</div>
                                <div class="value">{{ $bundle['quantity'] }}</div>
                            </div>

                            <div class="data-wrapper">
                                <div class="label">Unit price:</div>
                                <div class="value">{{ $bundle['price'] }}</div>
                            </div>

                            <div class="data-wrapper">
                                <div class="label">
                                    Total price:
                                </div>
                                <div class="value">{{ $bundle['price'] * $bundle['quantity'] }}</div>
                            </div>

                        </div>
                        
                    </div>

                    <div class="bundled-items" >
                        <table>
                            
                            <thead>
                                <tr>
                                    <td>Bundled items</td>
                                    <td>Qty</td>
                                </tr>
                            </thead>

                            <tbody class="styled2">
                            @foreach ($bundle['products'] as $product)
                                <tr>
                                    <td>{{ $product['name'] }}</td>
                                    <td>{{ $product['quantity'] }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                            
                        </table>
                    </div>

                    <div style="clear: both;" ></div>
                
                </div>
            @endforeach
        @endif

        </div>

        <div class="payment-information" >

            <div class="right">
                
                <table class="payment-information-table" >

                    <tbody class="styled">
                        <tr>
                            <td>Sub total:</td>
                            <td>{{ $snapshot['sub_total'] }}</td>
                        </tr>

                        <tr>
                            <td>Discount:</td>
                            <td>{{ $snapshot['discount'] }}</td>
                        </tr>


                        <tr>
                            <td>Delivery fee:</td>
                            <td>{{ $snapshot['delivery_charge'] }} @if($snapshot['delivery_charge'] > 0) AED @endif</td>
                        </tr>

                        <tr>
                            <td>Prices inclusive of VAT.</td>
                        </tr>

                        @if(array_key_exists('valucredits', $snapshot))
                        <tr>
                            <td>Valucredits:</td>
                            <td> - {{ $snapshot['valucredits'] }}</td>
                        </tr>    
                           
                        @endif 

                    </tbody>

                     @if(array_key_exists('valucredits', $snapshot))
                    <tfoot>
                        <tr>
                            <td>TOTAL:</td>
                            <td>{{ $snapshot['total']  -  $snapshot['valucredits'] }}</td>
                        </tr>
                    </tfoot>
                    @else
                    <tfoot>
                        <tr>
                            <td>TOTAL:</td>
                            <td>{{ $snapshot['total'] }}</td>
                        </tr>
                    </tfoot>
                    @endif

                </table>

            </div>

            <div class="left">
                
                <div class="payment-information">
                    <div class="heading" >Payment information</div>
                    <div class="info">
                        <div class="label" >Payment type:</div>
                        <div class="value" >{{ $order->payment_type }}</div>
                        <div style="clear: both;" ></div>
                    </div>

                    <!-- <div class="info">
                        <div class="label" >Card number:</div>
                        <div class="value" >xxxx xxxx xxxx 1234</div>
                        <div style="clear: both;" ></div>
                    </div> -->
                </div>

            </div>

            <div class="clear" ></div>
            
        </div>
        
        <br class="clear" />

        <div class="terms-conditions">
            
            <h2>Return policy</h2>

            <div>
                <ol>
                    <li>You should check all Products you receive against your order. If you receive Products that are damaged or incorrectly supplied then you must note the details of any damage or error in supply on the delivery documentation and it will be your responsibility to tell us of the damage or incorrect supply within 24 hours of delivery. You must return the Products to us as soon as possible after notifying us that the Products are damaged or have been incorrectly supplied. Please note that we will not accept the return of any damaged or incorrectly supplied Products where you fail to notify us of this within 24 hours of receiving them.</li>
                    <li>If the Products;
                        <ol>
                            <li>are damaged at the time of delivery</li>
                            <li>have past their stated expiry dates at the time of delivery</li>
                            <li>are proved to be counterfeit or</li>
                            <li>Canceling of products after the delivery status is changed to “shipped”, canceling of products is not applicable</li>
                            <li>Customer can cancel their order within 24 hours; refunds will be made back to the payment solution used initially by the customer. Please allow for up to 45days for the refund transfer to be completed</li>
                        </ol>
                    </li>
                    <li>All the Products listed on our Website are sold either by us directly or by carefully selected vendors and, in relation to the later, we just act as a marketplace to get these Products to you. Each Product sold by us will also come with the benefit of the relevant vendors warranty for that particular Product as detailed on the Website or the Products themselves and vendors are directly responsible for it. We will however provide you with reasonable assistance in relation to making any warranty claims against the relevant vendors.</li>
                    <li>Refund will be done only through the original mode of payment</li>
                </ol>
            </div>

            <!-- <h2>Terms and Conditions</h2>

            <p>These terms and conditions ("Terms") apply whenever you use our website <a href="http://www.valucart.com">www.valucart.com</a> (the "Website") or order
                any product ("Product") or bundle of Products (“Bundle”) through the Website or otherwise from us.
            </p>
            <p>Please read these Terms carefully and print a copy for your future reference. By
                using the Website or ordering a Product from us, you agree that you have read, understood and agree
                to these Terms (as amended from time to time). If you do not agree to these Terms, you must not use
                the Website or order any Product from us.</p>
            <ol>
                <li>
                    <h3>About us</h3>
                    <p>Valucart General Trading LLC (referred to in these Terms as “ValuCart”, “we”, or “us”) is a
                        limited liability company registered in Dubai, United Arab Emirates (UAE), whose commercial
                        license number is 809832 and whose registered address is at PO Box [ 555521 ] Dubai with a
                        paid up share capital of AED 300,000. </p>
                    <p>United Arab of Emirates is our country of domicile” and stipulate that the governing law is
                        the local law.</p>
                    <p>We will not trade with or provide any services to OFAC and sanctioned countries</p>
                    <p>Should you have any questions about these Terms or wish to contact us for any reason
                        whatsoever, please e-mail us on <a href="mailto:support@valucart.com">support@valucart.com.</a></p>
                </li>
                <li>
                    <h3>About you</h3>
                    <p>Customer using the website who are Minor /under the age of 18 shall not register as a User of
                        the website and shall not transact on or use the website</p>
                    <p>By ordering any Products from us, you confirm that you are</p>
                    <ul>
                        <li>over 18 years old</li>
                        <li>resident in one of the Gulf Cooperation Council (“GCC”) countries and</li>
                        <li>ordering Products for delivery in the GCC . If any of the above is incorrect, you must
                            notify us and we reserve the right, in our sole discretion, to refuse or accept your
                            order.</li>
                    </ul>
                    <p>User is responsible for maintaining the confidentiality of his account</p>
                </li>
                <li>
                    <h3>Use of the Website</h3>
                    <p>ValuCart General Trading L.L.C maintains the website https://www.valucart.com</p>
                    <p>You agree to use the Website only for lawful purposes and in a way which does not infringe
                        the rights of any anyone else or restrict or inhibit anyone else's use and enjoyment of the
                        Website.</p>
                    <p>You are permitted to download and print content from the Website solely for your own internal
                        business purposes and/or personal use. Website content must not be copied or reproduced,
                        used or otherwise dealt with for any other reason. You are not entitled to modify or
                        redistribute the content of this Website or reproduce, link, frame or deep-link it on any
                        other website without our express written permission. You are not entitled to use the
                        content of the Website for commercial exploitation in any circumstances.</p>
                    <p>Without limiting any other rights and remedies we may have available, we may, without giving
                        any notice, limit your activities on the Website, deny you access to the Website or refuse
                        to sell Products to you if you breach these Terms or if we consider your use of the Website
                        to be inappropriate.</p>
                </li>
                <li>
                    <h3>Uploads</h3>
                    <p>From time to time, the Website may include a blog ("Blog"), which is a specialised part of
                        the Website that allows an individual or a group of individuals to share a running log of
                        events and personal insights with other users of the Website. You agree that you will not
                        post or share any material that is:</p>
                    <ul>
                        <li>contrary to Islamic principles, or the social norms or moral or cultural considerations
                            of the UAE or other GCC countries; </li>
                        <li>that is threatening, defamatory, obscene, indecent, offensive, pornographic, abusive,
                            discriminatory, scandalous, inflammatory, or which may cause annoyance or inconvenience;
                        </li>
                        <li>for which you have not obtained all necessary licences and approvals; or</li>
                        <li>which is technically harmful (including, but not limited to, computer viruses, logic
                            bombs, Trojan horses, worms, harmful components, corrupted data or other malicious
                            software or harmful data.</li>
                    </ul>
                    <p>We will be free to copy, disclose, distribute, incorporate and otherwise use any posted or
                        shared material for any and all commercial and non-commercial purposes.</p>
                    <p>Without prejudice to any rights or remedies we have under the Terms and the law, we reserve
                        the right to remove any materials that breach these Terms or that we deem inappropriate.</p>
                </li>
                <li>
                    <h3>Product descriptions</h3>
                    <p>All Product descriptions and illustrations shown on the Website are provided in good faith
                        but are intended as guidance only and actual Products may vary accordingly. </p>
                    <p>All prices shown on the Website are in shown in UAE Dirhams. Unless otherwise stated on our
                        Website, prices shown on the Website, App do not include delivery charges, packaging, taxes,
                        custom duties or other charges, which will be added to any order you place. Our current
                        policy for deliveries within [Dubai][Sharjah] is (i) free delivery on all orders over AED
                        250 and (ii) a nominal charge of AED10 on orders between AED 50 and AED250. Please contact
                        us for delivery charges outside of [Dubai][Sharjah].</p>
                    <p>Whilst we use reasonable endeavours to keep prices up-to-date on the Website, we reserve the
                        right to alter prices and delivery charges at any time. </p>
                </li>
                <li>
                    <h3>Ordering Products</h3>
                    <p>Before placing an order to purchase a Product or a Bundle, you will be given the option to
                        purchase to register with us. By creating an account with us, you will be able to move
                        through the checkout process faster, store multiple shipping addresses, store your preferred
                        Bundles, view and track your order status and history and more. If you choose to register
                        with us you will be asked for a user name and password as your login information to create
                        your account. You are responsible for keeping your account login information secret and
                        secure and you agree:</p>
                    <ul>
                        <li>Not to permit any other person to use your user name and password; and</li>
                        <li>Not to disclose or provide any other person your user name, your password or any other
                            information that may allow other person to gain access to your user name or password.
                        </li>
                    </ul>
                    <p>We will use this user name and password information to create your personal account. Our
                        collection of your personal data submitted via the Website is subject to our Privacy Policy.
                    </p>
                    <p>When you place an order to purchase a Product from us, your order represents an offer to us
                        to purchase that Product, which we may choose to accept or refuse – it does not represent a
                        legally binding contract.</p>
                    <p>Whilst we will do our best to fulfil your order (once it is accepted), we cannot guarantee to
                        do so (for example, where our vendors are out of stock, where your card issuer refuses to
                        authorise your payment or where Products have been incorrectly priced on our Website). Your
                        offer is accepted by us and becomes binding only when we expressly confirm your order in
                        writing (either by invoice submitted with Products delivered, e-mail or otherwise).</p>
                </li>
                <li>
                    <h3>Payment for the Products</h3>
                    <p>Visa and Master Card and AED will be accepted for payment</p>
                    <p>Cardholder must retain a copy of transaction records and Merchant policies and rules</p>
                    <p>All Products will remain our property (or those of our vendors where we are selling on a
                        consignment basis on their behalf) until we have received payment in full for those
                        Products.</p>
                    <p>During the checkout process, you will be asked to complete your payment option. All
                        highlighted fields must be completed. We accept and can make arrangements for payment
                        methods such as cash on delivery/ card on delivery. In the future, other payment methods
                        such as pay via debit or credit card on our website will be made available.</p>
                    <p>All card payments are subject to authorisation by your card issuer. If your payment is not
                        authorised, we will cancel your order and notify you in writing (which may include e-mail)
                        that we have done so. If you have opted for cash on delivery, payment must be made in full
                        upon delivery. Please note our delivery team will carry sufficient change for [AED 500]
                        notes. </p>
                    <p>If we cannot supply you with the Products that you have ordered, we will cancel your order
                        and inform you of this as soon as reasonably possible. We will give you a full refund where
                        you have already paid for the Products.</p>
                </li>
                <li>
                    <h3>Delivery of the Products</h3>
                    <p>Delivery will be to the address specified in your order. We endeavor to make delivery the
                        very next day if the order is done within a cut-off time of 4:00PM depending on the
                        Products, the vendors and availability and your exact location but we cannot guarantee the
                        delivery date or time. If no one is available at the address at the time of delivery, the
                        Products will be retained by us for a reasonable period of time and then, if we cannot
                        contact you to arrange redelivery, the Products will be returned to us and/or the vendors
                        and the customer can reschedule the delivery. </p>
                    <p>All risk in the Products you order (including risk of loss and/or damage to the Products)
                        shall pass to you when they are delivered to the delivery address specified in your order.
                    </p>
                    <p>We shall be under no liability for any delay or failure to deliver Products if the delay or
                        failure is wholly or partly caused by circumstances beyond our control.</p>
                </li>
                <li>
                    <h3>Liability</h3>
                    <p>We will use reasonable skill and care in fulfilling any order placed by you which is accepted
                        by us. We exclude however, subject to Clause 9.6, all other representations, warranties,
                        conditions and terms express or implied by law or otherwise to the fullest extent permitted
                        by law.</p>
                    <p>We will not be liable for any special, indirect, incidental, consequential or economic loss
                        or for loss of profits or revenues howsoever caused arising in connection with any order
                        placed by you.</p>
                    <p>Notwithstanding the above, our total liability (whether in contract, tort, negligence or on
                        any other basis) to you, for any loss or damage shall, subject to Clause 9.6, be limited to
                        the sums paid by you for the Products.</p>
                    <p>We will not be liable to you where we breach these Terms due to any cause that is beyond our
                        reasonable control, including acts of God, explosions, floods, tempests, fires or accidents;
                        wars or threats of war, sabotage, insurrection, civil disturbance or requisition; acts,
                        restrictions, regulations, by-laws, decrees or laws, prohibitions or measures of any kind on
                        the part of any governmental, parliamentary or local authority; import or export regulations
                        or embargoes; strikes, lock-outs or other industrial actions or trade disputes; difficulties
                        in obtaining Products from vendors, materials, labour, fuel, parts or machinery; power
                        failure or breakdown in machinery.</p>
                    <p>This Website may contain links to other websites. We accept no responsibility or liability
                        for any material supplied by or contained on any third party website which is linked from or
                        to this Website, or any use of personal data by such third party.</p>
                    <p>Nothing in these Terms shall limit our liability for personal injury or death caused by our
                        negligence, fraud or any other liability which cannot be excluded or limited by law.</p>
                </li>
                <li>
                    <h3>Returns</h3>
                    <p>You should check all Products you receive against your order. If you receive Products that
                        are damaged or incorrectly supplied then you must note the details of any damage or error in
                        supply on the delivery documentation and it will be your responsibility to tell us of the
                        damage or incorrect supply within 24 hours of delivery. You must return the Products to us
                        as soon as possible after notifying us that the Products are damaged or have been
                        incorrectly supplied. Please note that we will not accept the return of any damaged or
                        incorrectly supplied Products where you fail to notify us of this within seventy 24 hours of
                        receiving them.</p>
                    <p>Subject to Clause 10.1, if the Products</p>
                    <ul>
                        <li>are damaged at the time of delivery</li>
                        <li>have past their stated expiry dates at the time of delivery</li>
                        <li>are proved to be counterfeit or</li>
                        <li>have been incorrectly supplied then we will, at our option, provide a replacement or
                            provide you with a credit equal to the value of such Products, which you can use against
                            future purchases of Products from us. If you have any queries in relation to our
                            exchange policy please email to <a href="mailto:customercare@valucart.com">customercare@valucart.com</a> for further
                            assistance.</li>
                    </ul>
                    <p>All the Products listed on our Website are sold either by us directly or by carefully
                        selected vendors and, in relation to the later, we just act as a marketplace to get these
                        Products to you. Each Product sold by us will also come with the benefit of the relevant
                        vendors warranty for that particular Product as detailed on the Website or the Products
                        themselves and vendors are directly responsible for it. We will however provide you with
                        reasonable assistance in relation to making any warranty claims against the relevant
                        vendors.</p>
                </li>
                <li>
                    <h3>Intellectual Property Rights</h3>
                    <p>As between you and us, we own all present and future copyright, registered and unregistered
                        trademarks, design rights, unregistered designs, database rights and all other present and
                        future intellectual property rights and rights in the nature of intellectual property rights
                        existing in or in relation to the Website. We have submitted an application to register
                        ValuCart as a trademark in the United Arab Emirates.</p>
                </li>
                <li>
                    <h3>Indemnity</h3>
                    <p>You agree to indemnify and keep us indemnified from and against all costs, claims, demands,
                        liabilities, expenses, damages or losses (including, but not limited to, direct losses,
                        consequential losses, loss of profit and loss of reputation and all interest, penalties, and
                        legal and other professional costs and expenses) arising out of and in connection with any
                        breach of these Terms by you.</p>
                </li>
                <li>
                    <h3>Other important terms</h3>
                    <p>We may update or amend these Terms from time to time to comply with law or to meet our
                        changing business requirements without notice to you. Any updates or amendments will be
                        posted on the Website and your continued use of the Website confirms your acceptance of the
                        updated or amended Terms.</p>
                    <p>These Terms supersede any other terms and conditions previously published by us and any other
                        representations or statements made by us to you, whether oral, written or otherwise.</p>
                    <p>You may not assign or sub-contract any of your rights or obligations under these Terms to any
                        third party unless we agree in writing.</p>
                    <p>We may assign, transfer or sub-contract any of our rights or obligations under these Terms to
                        any affiliate or third party at our discretion.</p>
                    <p>No relaxation or delay by us in exercising any right or remedy under these Terms shall
                        operate as waiver of that right or remedy or shall affect our ability to subsequently
                        exercise that right or remedy. Any waiver must be agreed by us in writing. </p>
                    <p>If any of these Terms are found to be illegal, invalid or unenforceable by any court of
                        competent jurisdiction, the rest of these Terms shall remain in full force and effect.</p>
                    <p>Only you and we shall be entitled to enforce these Terms. No third party shall be entitled to
                        enforce any of these Terms. </p>
                    <p>These Terms are governed by and shall be construed in accordance with UAE law as applicable
                        in the Emirate of Dubai. In the event of any matter or dispute arising out of or in
                        connection with these Terms, you and we shall submit to the exclusive jurisdiction of the
                        Dubai International Financial Centre (DIFC) Courts, including the DIFC small claims
                        tribunal, where such tribunal has specific jurisdiction for such claim.</p>
                </li>
            </ol> -->

        </div>

    </div>

</body>
</html>
