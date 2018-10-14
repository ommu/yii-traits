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
 *	gridColumnTemp
 *	filterYesNo
 *	filterDatepicker
 *	quickAction
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
	 * gridColumnTemp
	 *
	 * @return array
	 */
	public function gridColumnTemp()
	{
		$gridColumn = Yii::app()->getRequest()->getParam('GridColumn');
		$columnTemp = array();
		if($gridColumn) {
			foreach($gridColumn as $key => $val) {
				if($gridColumn[$key] == 1)
					$columnTemp[] = $key;
			}
		}
		
		return $columnTemp;
	}
	
	/**
	 * filterYesNo
	 *
	 * @return array
	 */
	public function filterYesNo($value=null)
	{
		$items = array(
			1 => Yii::t('phrase', 'Yes'),
			0 => Yii::t('phrase', 'No'),
		);

		if($value != null)
			return $items[$value];
		else
			return $items;
	}

	/**
	 * filterDatepicker
	 *
	 * @return string input
	 */
	public function filterDatepicker($model, $attribute, $filter=true)
	{
		$class = trim(get_class($model));
		$attrValue = Yii::app()->getRequest()->getParam($class)[$attribute];
		if(Yii::app()->params['grid-view']['JuiDatepicker']) {
			$options = array(
				'model'=>$model,
				'attribute'=>$attribute,
				'language' => 'en',
				'i18nScriptFile' => 'jquery-ui-i18n.min.js',
				//'mode'=>'datetime',
				'htmlOptions' => array(
					'value' => $attrValue,
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
			);
			if($filter == true) {
				$options['htmlOptions']['id'] = $attribute.'_filter';
				$options['htmlOptions']['on_datepicker'] = 'on';
				$options['htmlOptions']['placeholder'] = Yii::t('phrase', 'filter');
			} else 
				$options['htmlOptions']['class'] = 'form-control';

			return Yii::app()->controller->widget('zii.widgets.jui.CJuiDatePicker', $options, true);

		} else {
			$options = array(
				'value'=>$attrValue,
			);
			if($filter == true)
				$options['placeholder'] = Yii::t('phrase', 'filter');
			else
				$options['class'] = 'form-control';

			return CHtml::activeDateField($model, $attribute, $options);
		}
	}
	
	/**
	 * quickAction
	 *
	 * @return array
	 */
	public function quickAction($url, $id, $alert=null, $single=false)
	{
		if(is_array($alert))
			return $alert[$id];

		$cs = Yii::app()->getClientScript();
$js=<<<EOP
	var linkBG = location.href;
	function quickAction(url) {
		$.ajax({
			type: 'post',
			url: url,
			dataType: 'json',
			success: function(response) {
				window.location = linkBG;
				$(a[href=url]).parents('#ajax-message').html(response.msg).fadeIn();
			},
			error: function(jqXHR, textStatus, error) {
				location.href = url;
			}
		});	
	}
EOP;
		$cs->registerScript('quick-action', $js, CClientScript::POS_END);

		if($alert == null)
			$alert = 'Publish,Unpublish';
		$alertArray = explode(',', $alert);

		$text = $id == 1 ? $alertArray[0] : $alertArray[1];
		$title = $id == 1 ? $alertArray[1] : $alertArray[0];

		if($single == true && $id == 1)
			return Yii::t('phrase', ucwords(strtolower($alertArray[0])));
			
		else {
			return CHtml::link(Yii::t('phrase', ucwords(strtolower($text))), $url, array(
				'title' => Yii::t('phrase', ucwords(strtolower($title))),
				'confirm' => Yii::t('phrase', 'Are you sure you want to '.strtolower($title).' this item?'),
				'method' => 'post',
				'onclick' => 'quickAction(this);return false;',
			));
		}
	}
}
