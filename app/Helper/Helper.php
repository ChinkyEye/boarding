<?php 

namespace App\Helper;
use Nepali_Calendar;

class Helper{
	function slug_converter($string){
        $str = strtolower($string);
        $strs = preg_replace(array('#[\\s-]+#', '#[^A-Za-z0-9. -]+#'), ' ', $str);
        $split = explode(' ',$strs); 
        $slug_rep = implode('-',$split);
        $slug = str_replace(array('----','---','--'), '-', $slug_rep);
        return $slug;
    }
	public static function imageResize($file_path, $thumb_path, $filename, $extension, $width, $height){
			// Generate filename
			// Read RAW data
			$extension = strtolower($extension);
			$imageDetail = getimagesize($file_path);
			$thumb = imagecreatetruecolor($width, $height);
			$source = null;
			switch ($extension) {
				case 'png':
				imagealphablending($thumb, FALSE);
				imagesavealpha($thumb, TRUE);
				$source = imagecreatefrompng($file_path);
					break;
				case 'jpeg':
				$source = imagecreatefromjpeg($file_path);
					break;
				case 'jpg':
				$source = imagecreatefromjpeg($file_path);
					break;
				
				default:
					# code...
					break;
			}
			imagecopyresized($thumb, $source, 0, 0, 0, 0, $width, $height, $imageDetail[0], $imageDetail[1]);
			
			switch ($extension) {
				case 'png':
				imagepng($thumb, $thumb_path);
					break;
				case 'jpeg':
				imagejpeg($thumb, $thumb_path);
					break;
				case 'jpg':
				imagejpeg($thumb, $thumb_path);
					break;
				
				default:
					# code...
					break;
			}
	echo $filename;
	}

	function date_np_con(){
		$created_at_1 = date("Y-m-d");
		$date = explode('-', $created_at_1);
		$current_y = $date[0];
		$current_m = $date[1];
		$current_d = $date[2];
			include_once(app_path("/Includes/nepali_calendare.php"));
        	$calendar = new Nepali_Calendar();
        	$cal = $calendar->eng_to_nep($current_y, $current_m, $current_d);
            if(strlen($cal['month']) == '1'){
              $cal['month'] = '0'.$cal['month']; 
            }
            if(strlen($cal['date']) == '1'){
              $cal['date'] = '0'.$cal['date']; 
            }
        	$nepali_conversion = $cal['year'] . '-' . $cal['month'] . '-' . $cal['date'];
			return $nepali_conversion;
	}

	function date_eng_con(){
		$created_at_1 = $this->date_np_con();
		$date = explode('-', $created_at_1);
		$current_y = $date[0];
		$current_m = $date[1];
		$current_d = $date[2];
			include_once(app_path("/Includes/nepali_calendare.php"));
        	$calendar = new Nepali_Calendar();
        	$cal = $calendar->nep_to_eng($current_y, $current_m, $current_d);
            if(strlen($cal['month']) == '1'){
              $cal['month'] = '0'.$cal['month']; 
            }
            if(strlen($cal['date']) == '1'){
              $cal['date'] = '0'.$cal['date']; 
            }
        	$english_conversion = $cal['year'] . '-' . $cal['month'] . '-' . $cal['date'];
			return $english_conversion;
	}

	function date_eng_con_parm($date){
		$created_at_1 = $date;
		$date = explode('-', $created_at_1);
		$current_y = $date[0];
		$current_m = $date[1];
		$current_d = $date[2];
			include_once(app_path("/Includes/nepali_calendare.php"));
        	$calendar = new Nepali_Calendar();
        	$cal = $calendar->nep_to_eng($current_y, $current_m, $current_d);
            if(strlen($cal['month']) == '1'){
              $cal['month'] = '0'.$cal['month']; 
            }
            if(strlen($cal['date']) == '1'){
              $cal['date'] = '0'.$cal['date']; 
            }
        	$english_conversion = $cal['year'] . '-' . $cal['month'] . '-' . $cal['date'];
			return $english_conversion;
	}

	function date_np_con_parm($date){
		$created_at_1 = $date;
		$date = explode('-', $created_at_1);
		$current_y = $date[0];
		$current_m = $date[1];
		$current_d = $date[2];
		include_once(app_path("/Includes/nepali_calendare.php"));
		$calendar = new Nepali_Calendar();
		$cal = $calendar->eng_to_nep($current_y, $current_m, $current_d);
		if(strlen($cal['month']) == '1'){
			$cal['month'] = '0'.$cal['month']; 
		}
		if(strlen($cal['date']) == '1'){
			$cal['date'] = '0'.$cal['date']; 
		}
		$nepali_conversion = $cal['year'] . '-' . $cal['month'] . '-' . $cal['date'];
		return $nepali_conversion;
	}
	//for month date nepali month converter
	function date_np_con_parm_month($date){
		$created_at_1 = $date;
		$date = explode('-', $created_at_1);
		$current_y = $date[0];
		$current_m = $date[1];
		$current_d = $date[2];
		include_once(app_path("/Includes/nepali_calendare.php"));
		$calendar = new Nepali_Calendar();
		$cal = $calendar->eng_to_nep($current_y, $current_m, $current_d);
		if(strlen($cal['month']) == '1'){
			$cal['month'] = '0'.$cal['month']; 
		}
		if(strlen($cal['date']) == '1'){
			$cal['date'] = '0'.$cal['date']; 
		}
		$nepali_conversion = $cal['month'];
		return $nepali_conversion;
	}

	function date_np_con_parm_year($date){
		// dd($date);
		$created_at_1 = $date;
		$date = explode('-', $created_at_1);
		$current_y = $date[0];
		$current_m = $date[1];
		$current_d = $date[2];
		include_once(app_path("/Includes/nepali_calendare.php"));
		$calendar = new Nepali_Calendar();
		$cal = $calendar->eng_to_nep($current_y, $current_m, $current_d);
		if(strlen($cal['month']) == '1'){
			$cal['month'] = '0'.$cal['month']; 
		}
		if(strlen($cal['date']) == '1'){
			$cal['date'] = '0'.$cal['date']; 
		}
		$nepali_conversion = $cal['year'];
		// dd($nepali_conversion);
		return $nepali_conversion;
	}

	

	function date_np_con_parm_days($date){
		$created_at_1 = $date;
		$date = explode('-', $created_at_1);
		$current_y = $date[0];
		$current_m = $date[1];
		$current_d = $date[2];
		// dd($current_y,$current_m);
		include_once(app_path("/Includes/nepali_calendare.php"));
		$calendar = new Nepali_Calendar();
		$cal = $calendar->eng_to_nep($current_y, $current_m, $current_d);
		$i = 0;
		// dd($cal);
		// $nepali_conversion = $this->_get_day_of_week($current_y, $current_m, $current_d);
		dd($this,$cal,$this->_bs[$cal[$i]]);
		if(strlen($cal['month']) == '1'){
			$cal['month'] = '0'.$cal['month']; 
		}
		if(strlen($cal['date']) == '1'){
			$cal['date'] = '0'.$cal['date']; 
		}
		$nepali_conversion = $cal['year'];
		return $nepali_conversion;
	}

	function current_days_in_month($y, $m){
		include_once(app_path("/Includes/nepali_calendare.php"));
		$calendar = new Nepali_Calendar();
		$cal = $calendar->days_in_month($y, $m);
		return $cal;
	}

	function get_date_nepali_parm_month_days($y, $m){
		include_once(app_path("/Includes/nepali_calendare.php"));
		$calendar = new Nepali_Calendar();
		$cal = $calendar->days_in_month($y, $m);
		return $cal;
	}

	function word_digit($word) {
	    $warr = explode(';',$word);
	    $result = '';
	    foreach($warr as $value){
	        switch(trim($value)){
	            case 'zero':
	                $result .= '0';
	                break;
	            case 'one':
	                $result .= '1';
	                break;
	            case 'two':
	                $result .= '2';
	                break;
	            case 'three':
	                $result .= '3';
	                break;
	            case 'four':
	                $result .= '4';
	                break;
	            case 'five':
	                $result .= '5';
	                break;
	            case 'six':
	                $result .= '6';
	                break;
	            case 'seven':
	                $result .= '7';
	                break;
	            case 'eight':
	                $result .= '8';
	                break;
	            case 'nine':
	                $result .= '9';
	                break;   
	            case 'ten':
	                $result .= '10';
	                break;   
	            case 'eleven':
	                $result .= '11';
	                break;   

	            case 'twelve':
	                 $result .= '12';
	                 break;               
	        }
	    }
	    return $result;
	}

	

	


}