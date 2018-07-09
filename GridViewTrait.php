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
		return array(
			1 => Yii::t('phrase', 'Yes'),
			0 => Yii::t('phrase', 'No'),
		);
	}
	
	/**
	 * quickAction
	 *
	 * @return array
	 */
	public function quickAction($url, $id, $type=null, $single=false)
	{
		return $url;
		if($type == null)
			$type = 'Publish,Unpublish';
		$typeArray = explode(',', $type);

		$text = $id == 1 ? $typeArray[0] : $typeArray[1];
		$title = $id == 1 ? $typeArray[1] : $typeArray[0];

		if($single == true && $id == 1)
			return Yii::t('phrase', ucwords(strtolower($typeArray[0])));
			
		else {
			return CHtml::link(Yii::t('phrase', ucwords(strtolower($text))), $url, array(
				'title' => Yii::t('phrase', ucwords(strtolower($title))),
			));
		}
	}

	/**
	 * filterDatepicker
	 *
	 * @return string input
	 */
	public function filterDatepicker($model, $attribute)
	{
		$class = trim(get_class($model));
		$attrValue = Yii::app()->getRequest()->getParam($class)[$attribute];
		if(Yii::app()->params['grid-view']['JuiDatepicker'])
		{
			return Yii::app()->controller->widget('application.libraries.core.components.system.CJuiDatePicker', array(
				'model'=>$model,
				'attribute'=>$attribute,
				'language' => 'en',
				'i18nScriptFile' => 'jquery-ui-i18n.min.js',
				//'mode'=>'datetime',
				'htmlOptions' => array(
					'value' => $attrValue,
					'id' => $attribute.'_filter',
					'on_datepicker' => 'on',
					'placeholder' => Yii::t('phrase', 'filter'),
				),
				'options'=>array(
					'showOn' => 'focus',
					'dateFormat' => 'yy-mm-dd',
					'showOtherMonths' => true,
					'selectOtherMonths' => true,
					'changeMonth' => true,
					'changeYear' => true,
					'showButtonPanel' => true,
				),
			), true);

		} else 
			return CHtml::activeDateField($model, $attribute, array('value'=>$attrValue, 'placeholder'=>'filter'));
	}
}
