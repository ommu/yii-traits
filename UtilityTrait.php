<?php
/**
 * UtilityTrait
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 4 July 2018, 23:55 WIB
 * @link https://github.com/ommu/yii-traits
 *
 * Contains many function that most used :
 *	urlTitle
 *	flashMessage
 *	uniqueCode
 *	licenseCode
 *	dateFormat
 *
 */

trait UtilityTrait
{
	/**
	 * Create URL Title
	 *
	 * Takes a "title" string as input and creates a
	 * human-friendly URL string with a "separator" string
	 * as the word separator.
	 *
	 * @todo Remove old 'dash' and 'underscore' usage in 3.1+.
	 * @param string $str Input string
	 * @param string $separator Word separator (usually '-' or '_')
	 * @param bool $lowercase  Wether to transform the output string to lowercase
	 * @return string
	 */
	public function urlTitle($str, $separator = '-', $lowercase = true)
	{
		$str = trim($str);

		if($separator === 'dash')
			$separator = '-';
			
		elseif($separator === 'underscore')
			$separator = '_';

		$qSeparator = preg_quote($separator, '#');
		$trans = [
			'&.+?:;' => '',
			'[^a-z0-9 _-]' => '',
			'\s+' => $separator,
			'('.$qSeparator.')+' => $separator
		];

		$str = strip_tags($str);
		foreach ($trans as $key => $val)
			$str = preg_replace('#'.$key.'#i', $val, $str);

		if($lowercase === true)
			$str = strtolower($str);

		return trim(trim($str, $separator));
	}

	/**
	 * Provide style for error message
	 *
	 * @param mixed $message
	 * @param string $class "success, info, warning, danger"
	 */
	public function flashMessage($message, $class='success')
	{
		if($message != '') {
			$result = '<div class="errorSummary alert '.$class.'">';
			$result .= $message.'</div>';
		}

		return $result;
	}

	/**
	 * uniqueCode
	 *
	 * @return string
	 */
	public function uniqueCode($length=32, $str=2)
	{
		$chars = "abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		srand((double)microtime()*time());
		$i = 1;
		$salt = '' ;

		while ($i <= $length) {
			$num = rand() % 33;
			$tmp = substr($chars, $num, $str);
			$salt = $salt . $tmp; 
			$i++;
		}

		return $salt;
	}

	/**
	 * licenseCode
	 *
	 * @return string
	 */
	public function licenseCode($source='1234567890', $length=16, $char=4)
	{
		$mod = $length%$char;
		if($mod == 0)
			$sep = ($length/$char);
		else
			$sep = (int)($length/$char)+1;
		
		$sourceLength = strlen($source);
		$random = '';
		for ($i = 0; $i < $length; $i++)
			$random .= $source[rand(0, $sourceLength - 1)];
		
		$license = '';
		for ($i = 0; $i < $sep; $i++) {
			if($i != $sep-1)
				$license .= substr($random,($i*$char),$char).'-';
			else
				$license .= substr($random,($i*$char),$char);
		}

		return $license;
	}
	
	/**
	 * Get format date from locale setting
	 * 
	 * @return string
	 */
	public function dateFormat($date, $time=false) 
	{
		if(is_numeric($date) && (int)$date == $date)
			$date = date('Y-m-d H:i:s', $date);
		
		$setting = OmmuSettings::model()->findByPk(1, array(
			'select' => 'site_dateformat, site_timeformat',
		));
		
		if($time == true)
			return date($setting->site_dateformat, strtotime($date)).' '.date($setting->site_timeformat, strtotime($date)).' WIB';
		else
			return date($setting->site_dateformat, strtotime($date));
	}
}
