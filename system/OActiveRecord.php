<?php
/**
 * OActiveRecord class file.
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co) 
 * @created date 17 January 2018, 23:02 WIB
 * @link https://github.com/ommu/mod-core
 *
 */

class OActiveRecord extends CActiveRecord
{
	/**
	 * @var array daftar kolom awal yang akan ditampilkan pada grid view jika pada view tidak disebutkan.
	 */
	public $defaultColumns = array(); 
	/**
	 * @var array Daftar kolom yang akan ditampilkan pada grid dan dijadikan acuan untuk memuncul dan menyembunyikan kolom
	 */
	public $templateColumns = array(); 
	/**
	 * @var array Daftar grid-kolom yg tidak dimuncul di grid-option dan tidak akan muncul pada default gridview.
	 */
	public $gridForbiddenColumn = array(); 

	/**
	 * Get kolom untuk Grid View
	 *
	 * @param array $columns kolom dari view
	 * @return array dari grid yang aktif
	 */
	public function getGridColumn($columns=null) 
	{
		// Jika $columns kosong maka isi defaultColumns dg templateColumns
		if(empty($columns) || $columns == null) {
			array_splice($this->defaultColumns, 0);
			foreach($this->templateColumns as $key => $val) {
				if(!in_array($key, $this->gridForbiddenColumn) && !in_array($key, $this->defaultColumns))
					$this->defaultColumns[] = $val;
			}
			return $this->defaultColumns;
		}
		
		foreach($columns as $val) {
			if(array_key_exists($val, $this->templateColumns) && !in_array($val, $this->defaultColumns)) {
				$col = $this->getTemplateColumn($val);
				if($col != null)
					$this->defaultColumns[] = $col;
			}
		}

		array_unshift($this->defaultColumns, array(
			'header' => Yii::t('app', 'No'),
			'value' => '$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + $row+1',
			'htmlOptions' => array(
				'class' => 'center',
			),
		));

		array_unshift($this->defaultColumns, array(
			'class' => 'CCheckBoxColumn',
			'name' => 'id',
			'selectableRows' => 2,
			'checkBoxHtmlOptions' => array('name' => 'trash_id[]')
		));

		return $this->defaultColumns;
	}

	/**
	 * Get kolom template berdasarkan id pengenal
	 *
	 * @param string $name nama pengenal
	 * @return mixed
	 */
	public function getTemplateColumn($name) 
	{
		$data = null;
		if(trim($name) == '') return $data;

		foreach($this->templateColumns as $key => $item) {
			if($name == $key) {
				$data = $item;
				break;
			}
		}
		return $data;
	}
}
