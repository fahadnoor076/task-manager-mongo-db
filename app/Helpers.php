<?php
use Illuminate\Support\Facades\DB;
use App\Http\Models\Categories;

function mongoInitialization(){
	if(MONGO_OBJECT == 1):
		//return new \MongoDB\Driver\Manager("mongodb://localhost:31246");
		return new \MongoClient();
	else:
		return new \MongoClient();
	endif;
}

function projectStatus(){
	$status = array(
		'0' => 'active',
		'1' => 'complete',
		'2' => 'halt',
		'3' => 'refund-cbd',
		'4' => 'refund-delay',
		'5' => 'refund-quality',
	);
	return $status;
}


function logoIndustries(){
	$industries = array(
		'1' => 'Agriculture',
		'2' => 'Animal',
		'3' => 'Architecture',
		'4' => 'Automobiles',
		'5' => 'BPO',
		'6' => 'Business Solutions',
		'7' => 'Chemical',
		'8' => 'Children',
		'9' => 'Confetionery',
		'10' => 'Construction',
		'11' => 'Consultancy',
		'12' => 'Dating',
		'13' => 'Education',
		'14' => 'Engineering',
		'15' => 'Event Management',
		'16' => 'Fashion & Beauty',
		'17' => 'Finance',
		'18' => 'Food',
		'19' => 'Health & Fitness',
		'20' => 'Health & Medical',
		'21' => 'Home & Garden',
		'22' => 'Information & Technology',
		'23' => 'Insurance',
		'24' => 'Jewellery',
		'25' => 'Landscaping',
		'26' => 'Liquor & Beverages',
		'27' => 'Logistics',
		'28' => 'Media',
		'29' => 'Mortgage',
		'30' => 'Music',
		'31' => 'NGO',
		'32' => 'Photography',
		'33' => 'Production & Manufacturing',
		'34' => 'Real State',
		'35' => 'Recuirement',
		'36' => 'Spealing',
		'37' => 'Sports',
		'38' => 'Travel & Tourism',
		'39' => 'Wealth',
		'40' => 'Web',
		'41' => '3D',
		'42' => 'Apparels & Clothings',
		'43' => 'Arms &amp; Ammunitions',
		'44' => 'Arts & Crafts',
		'45' => 'Beauty & Cosmetics',
		'46' => 'Beverages',
		'47' => 'Brewery',
		'48' => 'Careers',
		'49' => 'Causes & Charity',
		'50' => 'Child Care',
	);
	return $industries;
}

function websiteIndustries(){
	$industries = array(
		'1' => 'Agriculture',
		'2' => 'Animal',
		'3' => 'Architecture',
		'4' => 'Automobiles',
		'5' => 'BPO',
		'6' => 'Business Solutions',
		'7' => 'Chemical',
		'8' => 'Children',
		'9' => 'Confetionery',
		'10' => 'Construction',
		'11' => 'Consultancy',
		'12' => 'Dating',
		'13' => 'Education',
		'14' => 'Engineering',
		'15' => 'Event Management',
		'16' => 'Fashion & Beauty',
		'17' => 'Finance',
		'18' => 'Food',
		'19' => 'Health & Fitness',
		'20' => 'Health & Medical',
		'21' => 'Home & Garden',
		'22' => 'Information & Technology',
		'23' => 'Insurance',
		'24' => 'Jewellery',
		'25' => 'Landscaping',
		'26' => 'Liquor & Beverages',
		'27' => 'Logistics',
		'28' => 'Media',
		'29' => 'Mortgage',
		'30' => 'Music',
		'31' => 'NGO',
		'32' => 'Photography',
		'33' => 'Production & Manufacturing',
		'34' => 'Real State',
		'35' => 'Recuirement',
		'36' => 'Spealing',
		'37' => 'Sports',
		'38' => 'Travel & Tourism',
		'39' => 'Wealth',
		'40' => 'Web',
		'41' => '3D',
		'42' => 'Apparels & Clothings',
		'43' => 'Arms &amp; Ammunitions',
		'44' => 'Arts & Crafts',
		'45' => 'Beauty & Cosmetics',
		'46' => 'Beverages',
		'47' => 'Brewery',
		'48' => 'Careers',
		'49' => 'Causes & Charity',
		'50' => 'Child Care',
	);
	return $industries;
}

function videoIndustries(){
	$industries = array(
		'1' => 'Agriculture',
		'2' => 'Animal',
		'3' => 'Architecture',
		'4' => 'Automobiles',
		'5' => 'BPO',
		'6' => 'Business Solutions',
		'7' => 'Chemical',
		'8' => 'Children',
		'9' => 'Confetionery',
		'10' => 'Construction',
		'11' => 'Consultancy',
		'12' => 'Dating',
		'13' => 'Education',
		'14' => 'Engineering',
		'15' => 'Event Management',
		'16' => 'Fashion & Beauty',
		'17' => 'Finance',
		'18' => 'Food',
		'19' => 'Health & Fitness',
		'20' => 'Health & Medical',
		'21' => 'Home & Garden',
		'22' => 'Information & Technology',
		'23' => 'Insurance',
		'24' => 'Jewellery',
		'25' => 'Landscaping',
		'26' => 'Liquor & Beverages',
		'27' => 'Logistics',
		'28' => 'Media',
		'29' => 'Mortgage',
		'30' => 'Music',
		'31' => 'NGO',
		'32' => 'Photography',
		'33' => 'Production & Manufacturing',
		'34' => 'Real State',
		'35' => 'Recuirement',
		'36' => 'Spealing',
		'37' => 'Sports',
		'38' => 'Travel & Tourism',
		'39' => 'Wealth',
		'40' => 'Web',
		'41' => '3D',
		'42' => 'Apparels & Clothings',
		'43' => 'Arms &amp; Ammunitions',
		'44' => 'Arts & Crafts',
		'45' => 'Beauty & Cosmetics',
		'46' => 'Beverages',
		'47' => 'Brewery',
		'48' => 'Careers',
		'49' => 'Causes & Charity',
		'50' => 'Child Care',
	);
	return $industries;
}


function videoAnimationStyles(){
	$styles = array(
		'1' => 'Motion Graphics',
		'2' => 'RSA Style',
		'3' => '3D Animation',
		'4' => '2D Animation',
	);
	return $styles;
}


//Task Priority
function taskPriorities(){
	$status = array(
		'1' => 'Prospect',
		'2' => 'High Paid',
		'3' => 'Confirm Upsell',
		'4' => 'Regular',
	);
	return $status;
}


//Website Phases
function websitePhases(){
	$status = array(
		'1' => 'UI Team',
		'2' => 'Front-End Team',
		'3' => 'Back-End Team',
		'4' => 'Social Media',
		'5' => 'Wordpress',
	);
	return $status;
}


//Logo Phases
function logoPhases(){
	$status = array(
		'1' => 'Logo',
		'2' => 'Flyer',
		'3' => 'Broucher',
		'4' => 'Stationary',
	);
	return $status;
}


//Video Phases
function videoPhases(){
	$status = array(
		'1' => 'Script Writing',
		'2' => 'Story Board',
		'3' => 'Animation',
		'4' => 'Editing',
		'5' => 'Final Format',
		'6' => 'Voice Over',
	);
	return $status;
}

//GetDueDate
function getDueDate($endTime = 29){
	$priorityHours = 24;
	if($priorityHours == 24){

		if($endTime == 29)
		{
			if(strtolower(date('D')) == 'sat' && date('H') <= 5)
			{
				//$x = date('H')+$priorityHours;//24
				$y = 29 - date('H');
				$hoursToAdd = $y+48;//24+y
				$dDate  = date("Y-m-d H:i:s", strtotime('+'.$hoursToAdd.' hours'));
			}
			else if(strtolower(date('D')) == 'sun' && date('H') <= 5)
			{
				//$x = date('H')+$priorityHours;//24
				$y = 29 - date('H');
				$hoursToAdd = $y+24;//24+y
				$dDate  = date("Y-m-d H:i:s", strtotime('+'.$hoursToAdd.' hours'));
			}
			elseif(strtolower(date('D')) == 'fri' && date('H') >= 20 && date('H') <= 23)
			{
				//$x = date('H')+$priorityHours;//24
				$y = 29 - date('H');
				$hoursToAdd = $y+48+24;//24+y
				$dDate  = date("Y-m-d H:i:s", strtotime('+'.$hoursToAdd.' hours'));
			}
			else if(date('H') > 5 && date('H') <= 19)
			{
				//$x = date('H')+$priorityHours;//24
				$y = 29 - date('H');
				$hoursToAdd = $y;//24+y
				$dDate  = date("Y-m-d H:i:s", strtotime('+'.$hoursToAdd.' hours'));
			}
			else if(date('H') <= 5)
			{
				//$x = date('H')+$priorityHours;//24
				$y = 29 - date('H');
				$hoursToAdd = $y;//24+y
				$dDate  = date("Y-m-d H:i:s", strtotime('+'.$hoursToAdd.' hours'));
			}
			else {
				$x = 29-date('H');
				$hoursToAdd = 24+$x;//24+x
				//echo "hours to add:".$hoursToAdd;exit;
				$dDate  = date("Y-m-d H:i:s", strtotime('+'.$hoursToAdd.' hours'));
			}
			$dDate = date('Y-m-d H:00:00', strtotime($dDate));
			/*if(strtolower(date('D',strtotime($dDate))) == 'sat' || strtolower(date('D',strtotime($dDate))) == 'sun'){
				//$dDate  = strtotime("+48 hours", strtotime($dDate));
				$date = new DateTime($dDate);
				$date->modify('+2 day');
				$dDate = $date->format('Y-m-d H:00:00');
			}*/

		}
		else if($endTime == 21)
		{
			if(strtolower(date('D')) == 'fri' && date('H') < 5)
			{
				$x = date('H')+$priorityHours;//current hours+24
				$y = $x - 21;
				$z = $priorityHours - $y;//24-y
				$hoursToAdd = $z +$priorityHours;//z+24
				$dDate  = date("Y-m-d H:i:s", strtotime('+'.$hoursToAdd.' hours'));
			}
			else if(date('H') < 21 ){
				$x = 21-date('H');
				$hoursToAdd = $priorityHours+$x;//24+x
				$dDate  = date("Y-m-d H:i:s", strtotime('+'.$hoursToAdd.' hours'));
			}
			else if(date('H') > 21)
			{
				$x = date('H') - 21;
				$y = $priorityHours-$x;//24-x
				$hoursToAdd = $y+$priorityHours;//y+24
				$dDate  = date("Y-m-d H:i:s", strtotime('+'.$hoursToAdd.' hours'));
			}
			else if(date('H') == 21)
			{
				$hoursToAdd = $priorityHours;//24
				$dDate  = date("Y-m-d H:i:s", strtotime('+'.$hoursToAdd.' hours'));
			}
			$dDate = date('Y-m-d H:00:00', strtotime($dDate));

			/*if(strtolower(date('D',strtotime($dDate))) == 'sat' || strtolower(date('D',strtotime($dDate))) == 'sun'){
				$dDate  = strtotime("+48 hours", strtotime($dDate));;
			}*/

			if(strtolower(date('D',strtotime($dDate))) == 'sat'){
				//$dDate  = strtotime("+48 hours", strtotime($dDate));;
				$date = new DateTime($dDate);
				$date->modify('+2 day');
				$dDate = $date->format('Y-m-d H:00:00');
			}
			else if(strtolower(date('D',strtotime($dDate))) == 'sun'){
				//$dDate  = strtotime("+48 hours", strtotime($dDate));;
				$date = new DateTime($dDate);
				$date->modify('+1 day');
				$dDate = $date->format('Y-m-d H:00:00');
			}
			else if(strtolower(date('D',strtotime($dDate))) == 'mon'){
				//$dDate  = strtotime("+48 hours", strtotime($dDate));;
				if(strtolower(date('H',strtotime($dDate))) == '5' || strtolower(date('H',strtotime($dDate))) == '05')
				{
					$date = new DateTime($dDate);
					$date->modify('+1 day');
					$dDate = $date->format('Y-m-d H:00:00');
				}

			}

		}

		$dDate = date('Y-m-d H:00:00', strtotime($dDate));

	}else{

		if($endTime == 29)
		{
			if(strtolower(date('D')) == 'sat' && date('H') <= 5)
			{
				//$x = date('H')+$priorityHours;//24
				$y = 29 - date('H');
				$hoursToAdd = $y+72;//24+y
				$dDate  = date("Y-m-d H:i:s", strtotime('+'.$hoursToAdd.' hours'));
			}
			else if(strtolower(date('D')) == 'sun' && date('H') <= 5)
			{
				//$x = date('H')+$priorityHours;//24
				$y = 29 - date('H');
				$hoursToAdd = $y+48;//24+y
				$dDate  = date("Y-m-d H:i:s", strtotime('+'.$hoursToAdd.' hours'));
			}
			elseif(strtolower(date('D')) == 'fri' && date('H') >= 20 && date('H') <= 23)
			{
				//$x = date('H')+$priorityHours;//24
				$y = 29 - date('H');
				$hoursToAdd = $y+48+48;//24+y
				$dDate  = date("Y-m-d H:i:s", strtotime('+'.$hoursToAdd.' hours'));
			}
			else if(date('H') > 5 && date('H') <= 19)
			{
				//$x = date('H')+$priorityHours;//24
				$y = 29 - date('H');
				$hoursToAdd = $y+24;//24+y
				$dDate  = date("Y-m-d H:i:s", strtotime('+'.$hoursToAdd.' hours'));
			}
			else if(date('H') <= 5)
			{
				//$x = date('H')+$priorityHours;//24
				$y = 29 - date('H');
				$hoursToAdd = $y+24;//24+y
				$dDate  = date("Y-m-d H:i:s", strtotime('+'.$hoursToAdd.' hours'));
			}
			else {
				$x = 29-date('H');
				$hoursToAdd = 48+$x;//24+x
				//echo "hours to add:".$hoursToAdd;exit;
				$dDate  = date("Y-m-d H:i:s", strtotime('+'.$hoursToAdd.' hours'));
			}
			
			/*if(strtolower(date('D')) == 'fri' || strtolower(date('D')) == 'sat')
			{
				$a = 29 - date('H');
				$hoursToAdd = $a + 48;
				$hoursToAdd = $hoursToAdd+ 48;//For weekend holidays hours
				$dDate  = date("Y-m-d H:i:s", strtotime('+'.$hoursToAdd.' hours'));
			}
			else{
				$a = 29 - date('H');
				$hoursToAdd = $a + 48;
				$dDate  = date("Y-m-d H:i:s", strtotime('+'.$hoursToAdd.' hours'));

			}*/
			$dDate = date('Y-m-d H:00:00', strtotime($dDate));
		}
		else if($endTime == 21)
		{
			if(strtolower(date('D')) == 'fri' || strtolower(date('D')) == 'sat')
			{
				$a = 21 - date('H');
				//$b = 24-$a;
				$hoursToAdd = $a + 48;
				$hoursToAdd = $hoursToAdd+ 48;//For weekend holidays hours
				$dDate  = date("Y-m-d H:i:s", strtotime('+'.$hoursToAdd.' hours'));
			}
			else{
				if(date('H') < 21)
				{
					$a = 21 - date('H');
					$b = 24+$a;
					$hoursToAdd = $b + 24;
					$dDate  = date("Y-m-d H:i:s", strtotime('+'.$hoursToAdd.' hours'));



				}
				else if(date('H') > 21)
				{
					$a = date('H') - 21;
					$b = 24-$a;
					$hoursToAdd = $b + 24;
					$dDate  = date("Y-m-d H:i:s", strtotime('+'.$hoursToAdd.' hours'));

				}
				else if(date('H') == 21)
				{
					//$a = 20 - date('H');
					//$b = 24;
					$hoursToAdd = 48;
					$dDate  = date("Y-m-d H:i:s", strtotime('+'.$hoursToAdd.' hours'));

				}
			}

			$dDate = date('Y-m-d H:00:00', strtotime($dDate));

		}


		if(strtolower(date('D',strtotime($dDate))) == 'sat' || strtolower(date('D',strtotime($dDate))) == 'sun'){
			//$dDate  = strtotime("+48 hours", strtotime($dDate));
			//echo $dDate;exit;
			$date = new DateTime($dDate);
			$date->modify('+2 day');
			$dDate = $date->format('Y-m-d H:00:00');

		}
		else if(strtolower(date('D',strtotime($dDate))) == 'mon'){
			//$dDate  = strtotime("+48 hours", strtotime($dDate));
			//echo $dDate;exit;
			$date = new DateTime($dDate);
			$date->modify('+2 day');
			$dDate = $date->format('Y-m-d H:00:00');

		}
	}

	return $dDate;

}


//Buttons Array
function buttonsArray(){
	$buttons = array(
		'1' => 'default',
		'2' => 'primary',
		'3' => 'success',
		'4' => 'info',
		'5' => 'warning',
		'6' => 'danger',
	);
	return $buttons;
}


//Task Statues
function logoTaskStatus(){
	$status = array(
		'1' => 'Order Unassigned',
		'2' => 'Initial Concepts Pending',
		'3' => 'Initial Concepts Submitted',
		'4' => 'Initial Concepts Processed',
		'5' => 'Pending Review',
		'6' => 'Designs Rejected',
		'7' => 'Final Files Pending',
		'8' => 'Final Files Submitted',
		'9' => 'Final Files Processed',
		'10' => 'Redraw Pending',
		'11' => 'Redraw Submitted',
		'12' => 'Redraw Processed',
		'13' => 'Revision Pending',
		'14' => 'Revision Submitted',
		'15' => 'Revision Processed',
	);
	return $status;
}

function websiteTaskStatus(){
	$status = array(
		'16' => 'Order Unassigned',
		'17' => 'Initial Concepts Pending',
		'18' => 'Initial Concepts Submitted',
		'19' => 'Initial Concepts Processed',
		'20' => 'Pending Review',
		'21' => 'Designs Rejected',
		'22' => 'Final Files Pending',
		'23' => 'Final Files Submitted',
		'24' => 'Final Files Processed',
		'25' => 'Redraw Pending',
		'26' => 'Redraw Submitted',
		'27' => 'Redraw Processed',
		'28' => 'Revision Pending',
		'29' => 'Revision Submitted',
		'30' => 'Revision Processed',
	);
	return $status;
}

function videoTaskStatus(){
	$status = array(
		'31' => 'Order Unassigned',
		'32' => 'Initial Concepts Pending',
		'33' => 'Initial Concepts Submitted',
		'34' => 'Initial Concepts Processed',
		'35' => 'Pending Review',
		'36' => 'Designs Rejected',
		'37' => 'Final Files Pending',
		'38' => 'Final Files Submitted',
		'39' => 'Final Files Processed',
		'40' => 'Redraw Pending',
		'41' => 'Redraw Submitted',
		'42' => 'Redraw Processed',
		'43' => 'Revision Pending',
		'44' => 'Revision Submitted',
		'45' => 'Revision Processed',
	);
	return $status;
}

function mobileTaskStatus(){
	$status = array(
		'46' => 'Order Unassigned',
		'47' => 'Initial Concepts Pending',
		'48' => 'Initial Concepts Submitted',
		'49' => 'Initial Concepts Processed',
		'50' => 'Pending Review',
		'51' => 'Designs Rejected',
		'52' => 'Final Files Pending',
		'53' => 'Final Files Submitted',
		'54' => 'Final Files Processed',
		'55' => 'Redraw Pending',
		'56' => 'Redraw Submitted',
		'57' => 'Redraw Processed',
		'58' => 'Revision Pending',
		'59' => 'Revision Submitted',
		'60' => 'Revision Processed',
	);
	return $status;
}

function flyerTaskStatus(){
	$status = array(
		'46' => 'Order Unassigned',
		'47' => 'Initial Concepts Pending',
		'48' => 'Initial Concepts Submitted',
		'49' => 'Initial Concepts Processed',
		'50' => 'Pending Review',
		'51' => 'Designs Rejected',
		'52' => 'Final Files Pending',
		'53' => 'Final Files Submitted',
		'54' => 'Final Files Processed',
		'55' => 'Redraw Pending',
		'56' => 'Redraw Submitted',
		'57' => 'Redraw Processed',
		'58' => 'Revision Pending',
		'59' => 'Revision Submitted',
		'60' => 'Revision Processed',
	);
	return $status;
}

function brochureTaskStatus(){
	$status = array(
		'61' => 'Order Unassigned',
		'62' => 'Initial Concepts Pending',
		'63' => 'Initial Concepts Submitted',
		'64' => 'Initial Concepts Processed',
		'65' => 'Pending Review',
		'66' => 'Designs Rejected',
		'67' => 'Redraw Pending',
		'68' => 'Redraw Submitted',
		'69' => 'Redraw Processed',
		'70' => 'Revision Pending',
		'71' => 'Revision Submitted',
		'72' => 'Revision Processed',
	);
	return $status;
}

function packagingTaskStatus(){
	$status = array(
		'73' => 'Order Unassigned',
		'74' => 'HTML Pending',
		'75' => 'HTML Submitted',
		'76' => 'HTML Processed',
		'77' => 'Pending Review',
		'78' => 'QA Rejected',
		'79' => 'Deployment Pending',
		'80' => 'Deployment Submitted',
		'81' => 'Deployment Processed',
		'82' => 'Revision Pending',
		'83' => 'Revision Submitted',
		'84' => 'Revision Processed',
	);
	return $status;
}

function stationaryTaskStatus(){
	$status = array(
		'85' => 'Order Unassigned',
		'86' => 'Test Link Pending',
		'87' => 'Test Link Submitted',
		'88' => 'Test Link Processed',
		'89' => 'CMS Integration Pending',
		'90' => 'CMS Integration Submitted',
		'91' => 'CMS Integration Processed',
		'92' => 'Pending Review',
		'93' => 'QA Rejected',
		'94' => 'Deployment Pending',
		'95' => 'Deployment Submitted',
		'96' => 'Deployment Processed',
		'97' => 'Revision Pending',
		'98' => 'Revision Submitted',
		'99' => 'Revision Processed',
	);
	return $status;
}


function getTaskStatus(){
	$status = array(
		array("logo","Order Unassigned"),
		array("logo","Initial Concepts Pending"),
		array("logo","Initial Concepts Submitted"),
		array("logo","Initial Concepts Processed"),
		array("logo","Pending Review"),
		array("logo","Designs Rejected"),
		array("logo","Final Files Pending"),
		array("logo","Final Files Submitted"),
		array("logo","Final Files Processed"),
		array("logo","Redraw Pending"),
		array("logo","Redraw Submitted"),
		array("logo","Redraw Processed"),
		array("logo","Revision Pending"),
		array("logo","Revision Submitted"),
		array("logo","Revision Processed"),
	);
	return $status;
}


function getInvoiceTypes(){
	$status = array(
		"1" => "New Lead",
		"2" => "Upsell",
		"3" => "Change Request",
		"4" => "Other"
	);
	return $status;
}


function taskTypes(){
	$status = array(
		"1" => "Clarification",
		"2" => "Design",
		"3" => "Revision",
		"4" => "Redraw",
		"5" => "Approve",
		"6" => "Reject",
		"7" => "Proceed",
		"8" => "Final Files",
	);
	return $status;
}