<?php
/**
 * GridViewTrait
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 6 July 2018, 10:50 WIB
 * @link https://github.com/ommu/yii-traits
 *
 * Contains many function that most used :
 *	keyIndex
 *	activeDefaultColumns
 *
 */

trait GridViewTrait
{
	/**
	 * Generates key index defaultColumns in models
	 * @return array
	 */
	public function keyIndex($data)
	{
		if(!is_array($data))
			return $data;

		else {
			if(array_key_exists('name', $data))
				return $data['name'];
		}

		return false;
	}

	/**
	 * Generates key index defaultColumns in models
	 * @return array
	 */
	public function activeDefaultColumns($columns)
	{
		$column = array();

		foreach($columns as $val) {
			$keyIndex = $this->keyIndex($val);
			if($keyIndex)
				$column[] = $keyIndex;
		}

		return $column;
	}
	
	/**
	 * filterYesNo
	 *
	 * @return array
	 */
	public function filterYesNo() 
	{
		return [
			1 => Yii::t('phrase', 'Yes'),
			0 => Yii::t('phrase', 'No'),
		];
	}
}
