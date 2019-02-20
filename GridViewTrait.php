<?php
/**
 * GridViewTrait
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.co)
 * @created date 17 April 2018, 08:36 WIB
 * @link https://github.com/ommu/yii-traits
 * 
 * GridViewTrait berisi kumpulan fungsi yang berhubungan dengan grid-view system pada yii2-framework, 
 * seperti fungsi filter yes/no, quick-action dll.
 *
 * Contains many function that most used :
 *	activeDefaultColumns
 *	getKeyIndex
 *	filterDatepicker
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
	 * filterDatepicker
	 *
	 * @return string input
	 */
	public function filterDatepicker($model, $attribute)
	{
		if(Yii::$app->params['gridView']['datepicker'] == true) {
			return \yii\jui\DatePicker::widget([
				'model' => $model,
				'attribute' => $attribute,
				'dateFormat' => 'yyyy-MM-dd',
			]);
		}

		return Html::input('date', $attribute, Yii::$app->request->get($attribute), ['class'=>'form-control']);
	}
}
