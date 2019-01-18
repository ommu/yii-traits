<?php
/**
 * UtilityTrait
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.co)
 * @created date 12 May 2018, 22:47 WIB
 * @link https://github.com/ommu/yii-traits
 *
 * Contains many function that most used :
 *	urlTitle
 *	flashMessage
 *	licenseCode
 *	quickAction
 *	filterYesNo
 *
 */

namespace ommu\traits;

use Yii;
use yii\helpers\Html;

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

		if ($lowercase === true)
			$str = strtolower($str);

		return trim(trim($str, $separator));
	}

	/**
	 * Provide style for error message
	 *
	 * @param mixed $msg
	 * @param string $type "success, info, warning, danger"
	 */
	public function flashMessage($message, $class='success')
	{
		if($message != '') {
			$result = '<div class="alert alert-'.$class.' alert-dismissible fade in">';
			$result .= $message.'</div>';
		}

		return $result;
	}

	/**
	 * get License
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
	 * quickAction
	 *
	 * @return array
	 */
	public function quickAction($url, $id, $alert=null, $single=false)
	{
		if($alert == null)
			$alert = 'Publish,Unpublish';
		$alertArray = explode('#', $alert);

		$textArray = $titleArray = explode(',', $alert);
		if(count($alertArray) != 1) {
			$textArray = explode(',', $alertArray[0]);
			$titleArray = explode(',', $alertArray[1]);
		}


		$text = $id == 1 ? Yii::t('app', $textArray[0]) : Yii::t('app', $textArray[1]);
		$title = $id == 1 ? Yii::t('app', $titleArray[1]) : Yii::t('app', $titleArray[0]);
		$message = Yii::t('app', 'Are you sure you want to {text} this item?', array(
			'text'=>strtolower($title),
		));

		if($single == true && $id == 1)
			return Yii::t('app', ucwords(strtolower($textArray[0])));
			
		else {
			return Html::a(ucwords(strtolower($text)), $url, [
				'title' => Yii::t('app', 'Click to {title}', array('title'=>strtolower($title))),
				'data-confirm' => $message,
				'data-method' => 'post',
			]);
		}
	}
	
	/**
	 * filterYesNo
	 * 
	 * @return array
	 */
	public function filterYesNo($value=null) 
	{
		$items = [
			0 => Yii::t('app', 'No'),
			1 => Yii::t('app', 'Yes'),
		];

		if($value !== null)
			return $items[$value];
		
		return $items;
	}
}
