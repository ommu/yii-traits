<?php
/**
 * ThemeTrait
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 5 July 2018, 15:48 WIB
 * @link https://github.com/ommu/yii-traits
 *
 * Contains many function that most used :
 *	currentTemplate
 *	applyCurrentTheme
 *	applyViewPath
 *
 */

trait ThemeTrait
{
	/**
	 * Return setting template with tipe of theme
	 *
	 * @param string $typePage Type of theme. example: admin, public, maintenance
	*/
	public function currentTemplate($typeTheme) 
	{
		$model = OmmuThemes::model()->find(array(
			'select' => 'folder, layout',
			'condition' => 'group_page = :group AND default_theme = :default',
			'params' => array(
				':group' => $typeTheme,
				':default' => 1,
			)
		));
		if($model != null)
			return array('folder' => $model->folder, 'layout' => $model->layout);
	}

	/**
	 * Refer layout path to current applied theme.
	 *
	 * @param object $module that currently active [optional]
	 * @return void
	 */
	public function applyCurrentTheme($module=null) 
	{
		$theme = Yii::app()->theme->name;
		Yii::app()->theme = $theme;

		if($module !== null) {
			$themePath = Yii::getPathOfAlias('webroot.themes.'.$theme.'.views.layouts');
			$module->setLayoutPath($themePath);
		}
	}

	/**
	 * @return string the root directory of view files. Defaults to 'moduleDir/views' where
	 * moduleDir is the directory containing the module class.
	 */
	public static function applyViewPath($path, $core=true)
	{
		$module = strtolower(Yii::app()->controller->module->id);
		$basePath = Yii::app()->basePath;
		$modulePath = Yii::app()->modulePath;
		$viewPath = Yii::app()->controller->module->viewPath;
		if($module == null)
			$viewPath = Yii::app()->viewPath;
		
		$path = preg_replace('(controllers)', 'views', $path);
		$viewPathSlashes = addcslashes($viewPath, '/');
		if(!preg_match("/$viewPathSlashes/", $path)) {
			if($module == null) {
				if($core == true)
					Yii::app()->viewPath = join('/', [$basePath, 'views']);
				else
					Yii::app()->viewPath = $path;
			} else
				Yii::app()->controller->module->viewPath = join('/', [$modulePath, $module, 'views']);
		}
	}
}
