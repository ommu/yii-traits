<?php
/**
 * GridViewTrait
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (opensource.ommu.co)
 * @created date 17 April 2018, 08:36 WIB
 * @link https://github.com/ommu/yii2-traits
 * 
 * GridViewTrait berisi kumpulan fungsi yang berhubungan dengan grid-view system pada yii2-framework, 
 * seperti fungsi filter yes/no, quick-action dll.
 *
 * Contains many function that most used :
 *	filterYesNo
 *	quickAction
 *
 */

namespace ommu\traits;

use Yii;
use yii\helpers\Html;

trait GridViewTrait 
{
	/**
	 * activeDefaultColumns
	 * @return array
	 */
	public function activeDefaultColumns($columns)
	{
		$column = [];

		foreach($columns as $val) {
			$keyIndex = $this->getKeyIndex($val);
			if($keyIndex)
				$column[] = $keyIndex;
		}

		return $column;
	}

	/**
	 * getKeyIndex
	 * @return array
	 */
	public function getKeyIndex($data)
	{
		if(!is_array($data))
			return $data;

		else {
			if(array_key_exists('attribute', $data))
				return $data['attribute'];
		}

		return false;
	}
	
	/**
	 * filterYesNo
	 * @return array
	 */
	public function filterYesNo() 
	{
		return [
			1 => Yii::t('app', 'Yes'),
			0 => Yii::t('app', 'No'),
		];
	}
	
	/**
	 * quickAction
	 * @return array
	 */
	public function quickAction($url, $id, $type=null, $single=false)
	{
		if($type == null)
			$type = 'Publish,Unpublish';
		$typeArray = explode(',', $type);

		$text = $id == 1 ? Yii::t('app', $typeArray[0]) : Yii::t('app', $typeArray[1]);
		$title = $id == 1 ? Yii::t('app', $typeArray[1]) : Yii::t('app', $typeArray[0]);
		$message = Yii::t('app', 'Are you sure you want to {$text} this item?', array(
			'{$text}'=>strtolower($title),
		));

		if($single == true && $id == 1)
			return Yii::t('app', ucwords(strtolower($typeArray[0])));
			
		else {
			return Html::a(ucwords(strtolower($text)), $url, [
				'title' => ucwords(strtolower($title)),
				'data-confirm' => $message,
				'data-method' => 'post',
			]);
		}
	}
}
