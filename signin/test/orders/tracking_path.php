<?php 
	
	/* ### MESSAGES ### */

	$on_old 			= "Order no hold";
	$refused 			= "Order refused";
	$cancelled 			= "Order cancelled";

	$step1_done 		= "You place the order online. Order is approved.";
	$step2_done 		= "Pune Fullfillment Team forwards the order to Hong Kong.";
	$step3_done 		= "Hong Kong Team process the order.";
	$step4_done 		= "Export process in progress - with Hong Kong Customs.";
	$step5_done 		= "Hong Kong to New Delhi - International transportation.";
	$step6_done 		= "Custom Clearance in New Delhi.";
	$step7_done 		= "Order Dispatch from Pune Fullfillment Center";

	$step1_before_done 	= "Step 1, before done!";
	$step2_before_done 	= "Step 2, before done!";
	$step3_before_done 	= "Step 3, before done!";
	$step4_before_done 	= "Step 4, before done!";
	$step5_before_done 	= "Step 5, before done!";
	$step6_before_done 	= "Step 6, before done!";
	$step7_before_done 	= "Step 7, before done!";

?>

<link rel="stylesheet" href="timeline/css/style.css">
<style>
	.waiting .cd-timeline-img,
	.waiting .cd-timeline-content{
		background: #D8D8D8;
		color: gray;	
	}
	@media only screen and (max-width: 1170px) {
		.waiting .cd-timeline-content::before {
	  		border-right-color: #D8D8D8;
		}
	}
	@media only screen and (min-width: 1170px) {
		.waiting .cd-timeline-content::before {
		    border-left-color: #D8D8D8;
		}
		.waiting.cd-timeline-block:nth-child(even) .cd-timeline-content::before {
		 	border-right-color: #D8D8D8;
		}
	}
	 
	.done .cd-timeline-img{
		background: #75ce66;
	}
	.error .cd-timeline-img{
		background: #c03b44;
	}

	.cd-timeline-between-step{
		margin: 2em 0;
		width: 100%;
		overflow: hidden;
	}
	.cd-timeline-between-step>div{
		width: 50%;
		float: left;
		padding-left: 10px;
		font-size: 11px;
		opacity: .7;
	}
	@media only screen and (max-width: 1170px) {
		.cd-timeline-between-step>div:first-child{
			display: none;
		}
		.cd-timeline-between-step>div{
			padding-left: 30px;
		}
	}
</style>
<!--<script src="timeline/js/modernizr.js"></script>-->

<?php

	function make_tracking_path($id_order){
		global $on_old,$refused,$cancelled,$step1_done,$step2_done,$step3_done,$step4_done,$step5_done,$step6_done,$step7_done,$step1_before_done,$step2_before_done,$step3_before_done,$step4_before_done,$step5_before_done,$step6_before_done,$step7_before_done;

		$order = new order();
		$order->map($id_order);

		$staticDate     = $order->get_date_done();
		$status_admin   = $order->get_status_admin();

		$max 			= 7;

		// SET TIME ZONE DATE (USED FOR NOW)
		date_default_timezone_set('Mexico/General');
		$output	 	= array();

		$date1 				= date('Y-m-d H:i:s', strtotime($staticDate));
		$output['date'][]   = $date1;
		$output['date'][]   = date('Y-m-d H:i:s', strtotime($date1 . ' + 1 weekdays'));
		$output['date'][]   = date('Y-m-d H:i:s', strtotime($date1 . ' + 6 weekdays'));
		$output['date'][]   = date('Y-m-d H:i:s', strtotime($date1 . ' + 7 weekdays'));
		$output['date'][]   = date('Y-m-d H:i:s', strtotime($date1 . ' + 8 weekdays'));
		$output['date'][]   = date('Y-m-d H:i:s', strtotime($date1 . ' + 12 weekdays'));
		$output['date'][]   = date('Y-m-d H:i:s', strtotime($date1 . ' + 13 weekdays'));

		$output['step_msg'][] = $step1_done;
		$output['step_msg'][] = $step2_done;
		$output['step_msg'][] = $step3_done;
		$output['step_msg'][] = $step4_done;
		$output['step_msg'][] = $step5_done;
		$output['step_msg'][] = $step6_done;
		$output['step_msg'][] = $step7_done;

		$output['step_msg_waiting'][] = $step1_before_done;
		$output['step_msg_waiting'][] = $step2_before_done;
		$output['step_msg_waiting'][] = $step3_before_done;
		$output['step_msg_waiting'][] = $step4_before_done;
		$output['step_msg_waiting'][] = $step5_before_done;
		$output['step_msg_waiting'][] = $step6_before_done;
		$output['step_msg_waiting'][] = $step7_before_done;
		
		$now       				   = date('Y-m-d H:i:s');
		$output['step_status'][]   = ($now >= $output['date'][0]) ? "done" : "waiting";
		$output['step_status'][]   = ($now >= $output['date'][1]) ? "done" : "waiting";
		$output['step_status'][]   = ($now >= $output['date'][2]) ? "done" : "waiting";
		$output['step_status'][]   = ($now >= $output['date'][3]) ? "done" : "waiting";
		$output['step_status'][]   = ($now >= $output['date'][4]) ? "done" : "waiting";
		$output['step_status'][]   = ($now >= $output['date'][5]) ? "done" : "waiting";
		$output['step_status'][]   = ($now >= $output['date'][6]) ? "done" : "waiting";

		$output['days_gap'][] 	 	= '';
		$output['days_gap'][] 	 	= '1 working days';
		$output['days_gap'][] 	 	= '1 to 5 working days';
		$output['days_gap'][] 	 	= '1 working days';
		$output['days_gap'][] 	 	= '1 working days';
		$output['days_gap'][] 	 	= '1 to 4 working days';
		$output['days_gap'][] 	 	= '1 working days';

		// ########## EXCEPTIONS ##########

		$bad_order      	= "";
	    $msg_shipped        = "";
	    $msg_processing 	= "";
	    $delay 				= "";

	    /* ORDERS ON PROCESSING */

	    if( $status_admin == 'PROCESSING ORDER' ){
			$msg_processing = "<div class='alert alert-info text-center'>Your order is under preparation</div>";

			$last_date_calculated = $now;
			for ($i=0; $i < 6; $i++) {
				$step_status = $output['step_status'][$i];
	
				if($step_status == 'done'){
					$last_date_calculated = $output['date'][$i];
				}else if($step_status == 'waiting'){
					$output['days_gap'][$i] 	= '';
					$output['date'][$i] 		= $last_date_calculated;
					$output['step_status'][$i] 	= "done";	
				}
			}

			$output['days_gap'][$i] 	= '';
			$output['date'][$i] 		= $now;
			$output['step_status'][$i] 	= "waiting";
	    }

	    /* ORDERS ON HOLD, REFUSED OR CANCELLED */

		if( $status_admin == 'ORDER ON HOLD' ){
		  $bad_order = $on_old;
		}else if($status_admin == 'ORDER REFUSED'){
		  $bad_order = $refused;
		}else if($status_admin == 'ORDER CANCELLED'){
		  $bad_order = $cancelled;
		}

		/* ORDERS SHIPPED OR PARTIALLY SHIPPED */

		if($status_admin == 'ORDER SHIPPED' || $status_admin == 'ORDER PARTIALLY SHIPPED'){
			$msg_shipped = "<div class='alert alert-success text-center'>ORDER SHIPPED</div>";
			if($status_admin == 'ORDER PARTIALLY SHIPPED'){
				$msg_shipped = "<div class='alert alert-success text-center'>One or more of the products in your order has been shipped</div>";
			}

			$last_date_calculated = $now;
			for ($i=0; $i < 7; $i++) {
				$step_status = $output['step_status'][$i];
	
				if($step_status == 'done'){
					$last_date_calculated = $output['date'][$i];
				}else if($step_status == 'waiting'){
					$output['days_gap'][$i] 	= '';
					$output['date'][$i] 		= $last_date_calculated;
					$output['step_status'][$i] 	= "done";	
				}
			}
		}

		/* ORDERS DELAY, THE DATE HAS PASSED AND THE ORDER HASN'T BEEN MARKED AS SHIPPED OR PARTIALLY SHIPPED */

		if($output['step_status'][6]=='done' && !($status_admin == 'ORDER SHIPPED' || $status_admin == 'ORDER PARTIALLY SHIPPED') && $bad_order == ""){
			$output['step_status'][5] 	= "error";
			$delay 						= "yes";
			$max 						= 6;
		}

		// ########## END EXCEPTIONS ##########

		$rs  = "";
		if($bad_order == "" && $msg_shipped == "" && $delay == ""){
			$rs .= "<br><br><div class='alert alert-info text-center'>Your estimated dispatch date is ".date('M d,y', strtotime($output['date'][6]))."</div>";
		}
		if($bad_order != ""){
			$rs .= "<br><br><div class='alert alert-danger text-center'>The estimated dispatch date is not available</div>";
		}
		if($delay != ""){
			$rs .= "<br><br><div class='alert alert-danger text-center'>Delay due to uncontrollable circumstances. Please contact the customer care</div>";
		}
		$rs .= "<section id='cd-timeline' class='cd-container'>";
		
		for ($i=0; $i < $max; $i++) {
			$step_status= $output['step_status'][$i];
			$number 	= $i+1;
			$step_msg 	= $output['step_msg'][$i];
			$date 		= date('M d,y', strtotime($output['date'][$i]));
			$days_gap 	= $output['days_gap'][$i];

			if($step_status == 'waiting'){
				$step_msg = $output['step_msg_waiting'][$i];
			}

			if($bad_order != "" && $i==1){
				$step_status 	= 'error';
				$step_msg 		= $bad_order;
				$date 			= "";
				$days_gap 		= "";
			}

			$rs .= "<div class='cd-timeline-block $step_status'>";
				if($days_gap!=""){$rs .= "<div class='cd-timeline-between-step'><div>&nbsp;</div><div>$days_gap</div></div>";}
				$rs .= "<div class='cd-timeline-img'>$number</div>";
				$rs .= "<div class='cd-timeline-content'>";
					$rs .= "<p>$step_msg</p>";
					$rs .= "<span class='cd-date'>$date</span>";
				$rs .= "</div>";
			$rs .= "</div>";

			//$rs .= "<div>no idea</div>";

			if($bad_order != "" && $i==1){
				break;
			}
		}

		$rs .= '</section>';

		$rs .= $msg_shipped;
		$rs .= $msg_processing;

		return $rs;
	}

?>

<!--<script src="timeline/js/main.js"></script>-->
