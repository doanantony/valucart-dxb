<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">
<title>Order confirmation - Ref:  <?php echo $email_data['reference'];?></title>
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
                    <img src="https://www.valucart.com/img/logo.svg">
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
                        <a href="http://www.valucart.com">www.valucart.com</a>
                    </div>

                </div>

            </div>

            <div class="invoice-customer-information">
                
                <div class="title">Invoice</div>

                <div class="invoice-information-container">

                    <div class="data-wrapper">
                        <div class="label">Order date:</div>
                        <div class="value"><?php echo $email_data['created_at'];?></div>
                        <div class="clear"></div>
                    </div>

                    <div class="data-wrapper">
                        <div class="label">Order ref:</div>
                        <div class="value"><?php echo $email_data['reference'];?></div>
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

                    <div class="heading">
                        Customers information:
                    </div>

                    <div class="data-wrapper">
                        <div class="label">Name:</div>
                        <div class="value"><?php echo $email_data['customer']['name'];?></div>
                    </div>

                    <div class="data-wrapper">
                        <div class="label">Telephone:</div>
                        <div class="value"><?php echo $email_data['customer']['telephone'];?></div>
                    </div>

                    <div class="data-wrapper">
                        <div class="label">Email:</div>
                        <div class="value"><?php echo $email_data['customer']['email'];?></div>
                    </div>

                    <div class="data-wrapper">
                        <div class="label">Address:<?php echo $email_data['delivery_adress'];?></div>
                        <div class="value"></div>
                    </div>

                    <div class="data-wrapper">
                        <div class="label">Delivery date/time:<?php echo $email_data['delivery_date'];?><?php echo $email_data['delivery_time'];?></div>
                        <div class="value"></div>
                    </div>

                   
                </div>

            </div>

            <div style="clear: both;"></div>

        </div>
        
                 <?php
               if(count($email_data['products']) > 0){
               ?>
        <table class="products" >
            
            <thead>
                <tr>
                    <td>Item</td>
                    <td>Qty</td>
                    <td>Unit Price</td>
                    <td>Total Price</td>
                </tr>
            </thead>

            <tbody class="styled">
                
            <?php foreach($email_data['products'] as $products){?>	
                <tr>
                    <td><?php echo $products['name'];?></td>
                     <td><?php echo $products['qty'];?></td>
                     <td><?php echo $products['price'];?></td>
                      <td><?php echo $products['subtotal'];?></td>
                    <!-- <td>{{ $product['price'] * $product['quantity'] }}</td> -->
                </tr>
           <?php
             }
            ?>

            </tbody>
            
        </table>
        <?php
             }
            ?>
        
         <div class="bundles-container" >
        <?php
               if(count($email_data['bundles']) > 0){

            ?>
            <header>Bundles</header>
       <?php
               }
             ?>
            <!-- Bundles -->
            <?php
               if(count($email_data['bundles']) > 0){

            ?>
             <?php foreach($email_data['bundles'] as $bundle){?>	
                <div class="bundle" >
                    
                    <div class="bundle-header" >

                        <header><?php echo $bundle['name'];?></header>

                        <div class="meta-data">
                            
                            <div class="data-wrapper">
                                <div class="label">Qty:</div>
                                <div class="value"><?php echo $bundle['qty'];?></div>
                            </div>

                            <div class="data-wrapper">
                                <div class="label">Unit price:</div>
                                <div class="value"><?php echo $bundle['price'];?></div>
                            </div>

                            <div class="data-wrapper">
                                <div class="label">
                                    Total price:
                                </div>
                                <div class="value"><?php echo $bundle['subtotal'];?></div>
                            </div>

                        </div>
                        
                    </div>



                    <div style="clear: both;" ></div>
                
                </div>
           <?php
             }
            ?>
        <?php
             }
            ?>

        

        </div>
    		

        <div class="payment-information" >

            <div class="right">
                
                <table class="payment-information-table" >

                    <tbody class="styled">
                        <tr>
                            <td>Sub total:</td>
                            <td><?php echo $email_data['sub_total'];?></td>
                        </tr>

                        <tr>
                            <td>Discount:</td>
                            <td><?php echo $email_data['discount'];?></td>
                        </tr>

                        <tr>
                            <td>Delivery fee:</td>
                            <td><?php echo $email_data['delivery_charge'];?></td>
                        </tr>

                        <tr>
                            <td>VAT:</td>
                            <td><?php echo $email_data['vat'];?></td>
                        </tr>

                    </tbody>

                    <tfoot>
                        <tr>
                            <td>TOTAL:</td>
                            <td><?php echo $email_data['total'];?></td>
                        </tr>
                    </tfoot>
                    
                </table>

            </div>

            <div class="left">
                
                <div class="payment-information">
                    <div class="heading" >Payment information</div>
                    <div class="info">
                        <div class="label" >Payment type:</div>
                        <div class="value" >COD</div>
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



    </div>

</body>
</html>
