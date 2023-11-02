
<style>
.ors_fise .text-colr-lite{
	padding-left: 0;
}
.modal .text-colr-lite {
    padding-left: 0px;
}

.trk {
	background: #f7f7f7;
	padding: 15px 15px 25px;
}

._3BVwT {
	padding: 0px 10px;
	width: 100%;
}

._1i5nEe {
    display: inline-block;
    width: 0;
}
._1i5nEe {
    display: inline-block;
    width: 0;
    float: left;
}

._3HKlvX._31uEzM.active, ._1tBjl7.active {
    background: #0099e2;
}
._3HKlvX._31uEzM.active, ._1tBjl7.active {
    background: #0099e2;
}
._3HKlvX {
    border-radius: 50%;
    width: 20px;
    height: 20px;
    position: relative;
    cursor: pointer;
    background: #c9c9c9;
    top: 15px;
    z-index: 10;
    left: 50px;
}
._31uEzM, .aLqPfJ {
    background: #ccc;
    margin-bottom: 10px;
}

._2QynGw {
	width: calc(100% - 10px);
	margin-left: -70px;
	margin-top: -8px;
	height: 8px;
	background: #ccc;
}

._1tBjl7 {
	background: #c9c9c9;
	width: 93px;
	height: 8px;
	-webkit-transform: scaleX(0);
	transform: scaleX(0);
	-webkit-transform-origin: center left;
	transform-origin: center left;
	transition: -webkit-transform 1s ease-in;
	transition: transform 1s ease-in;
	transition: transform 1s ease-in, -webkit-transform 1s ease-in;
	transition-delay: 0s, 0s;
}

.detail_section {
	margin: 25px 0px 25px;
}

.fl-25 {
	float: left;
	width: 50%;
}

.fo-18 {
	font-size: 14px;
	font-weight: bold !important;
	color: #6a6a6a;
	margin-top: 5px;
	line-height: 25px;
}

.modal .text-colr-lite {
    padding-left: 0px;
}
.modal .text-colr-lite {
    padding-left: 0px;
}

.text-colr-lite {
    color: #333;
    text-transform: capitalize;
    padding-left: 15px;
}

.text-colr-lite {
    color: #333;
    text-transform: capitalize;
}

#view_track table th {
	text-align: center;
	background: #f7f7f7;
	color: #696969;
	border: 0;
}

#view_track table td {
    text-align: center;
    border: 0;
}

#view_track table td {
    text-align: center;
    border: 0;
}

._31uEzM:first-child{
    left:10px;
} 

.text-colr-lite{
    font-family: helvectica;
}

._3Qv1YL{
    line-height:20px;   
}

.modal-footer{
    display:none;
}

</style>

<?php
if(isset($message))
{
   $error=json_encode($errors);
    
?>
 
                           <div class="track_table clearfix">
                              <h2 class="fo-18 mtb-15">Awb Status</h2>
                              <table class="table" cellpadding="0" cellspacing="0">
                                 <tr>
                                    <th>Status</th>
                                    <th>Error</th>

                                 </tr>
                                
                                 <tr>
                                    <td><?php echo $message; ?></td>
                                    <td><?php echo $error; ?></td>
                                    
                                 </tr>
                               
                              </table>
                           </div>
                           <?php } 
else if($awb_message){ ?>
   <div class="track_table clearfix">
                              <h2 class="fo-18 mtb-15">Awb Status</h2>
                              <table class="table" cellpadding="0" cellspacing="0">
                                 <tr>
                                    <th>Order ID</th>
                                    <th>Shipment ID</th>
                                    <th>AWB Status</th>

                                 </tr>
                                
                                 <tr>
                                    <td><?php echo $ship_orderid; ?></td>
                                    <td><?php echo $shipment_id; ?></td>
                                    <td><?php echo $awb_message; ?></td>
                                    
                                 </tr>
                               
                              </table>
                           </div>                        
<?php } else { ?>
     <div class="track_table clearfix">
                              <h2 class="fo-18 mtb-15">Assign Details</h2>
                              <table class="table" cellpadding="0" cellspacing="0">
                                 <tr>
                                    <th>Order ID</th>
                                    <th>Shipment ID</th>
                                    <th>AWB Code</th>
                                    <th>Courier Name</th>

                                 </tr>
                                
                                 <tr>
                                    <td><?php echo $ship_orderid; ?></td>
                                    <td><?php echo $shipment_id; ?></td>
                                    <td><?php echo $awb_code; ?></td>
                                    <td><?php echo $courier_name; ?></td>
                                    
                                 </tr>
                               
                              </table>
                           </div>
                        <?php } ?>
