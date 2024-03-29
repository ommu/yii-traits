<?php
/**
 * UtilityTrait
 *
 * @author Putra Sudaryanto <putra@ommu.co>
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
 *	parseYML
 *	parseTemplate
 *	parseYesNo
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
	public function dateFormat($datetime, $date='full', $time='full')
	{
		return Yii::app()->dateFormatter->formatDateTime($datetime, $date, $time);
	}
	
	/**
	* Return setting template with typePage: public, admin_sweeto or back_office
	*/
	public static function parseYML($path)
	{
		Yii::import('mustangostang.spyc.Spyc');

		if(!file_exists($path))
			return false;
			
		$arraySpyc = Spyc::YAMLLoad($path);	
		
		if(empty($arraySpyc))
			return false;
			
		return $arraySpyc;
	}

	/**
	 * Method for parsing string
	 * 
	 * @param string $message Source string for parsing
	 * @param array $attribute of example
	 * [
	 *		'{link}' => 'https://github.com/ommu/mod-mailer',
	 *		'{author}' => Yii::$app->user->email
	 *	]
	 * 
	 * @return string
	 */
	public function parseTemplate($message, $attribute=null)
	{
		if($attribute != null) {
			foreach ($attribute as $key => $value) {
				$message = strtr($message, [
					'{'.$key.'}' => $value,
				]);
			}
		}

		return $message;
	}

	/**
	 * Method for parsing yes no condition
	 * 
	 * @return string
	 */
	public function parseYesNo($value, $icon=true)
	{
		$items = array(
			1 => Yii::t('phrase', 'Yes'),
			0 => Yii::t('phrase', 'No'),
		);
		$baseUrl = !empty(Yii::app()->theme->name) ? Yii::app()->theme->baseUrl : Yii::app()->request->baseUrl;
		$image = $value == '0' ? $baseUrl.'/images/icons/unpublish.png' : $baseUrl.'/images/icons/publish.png';

		if($icon == false)
			return $items[$value];
		else
			return CHtml::image($image, $items[$value]);
	}
}
