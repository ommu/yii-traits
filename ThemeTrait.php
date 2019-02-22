<?php
/**
 * ThemeTrait
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 21 February 2019, 12:04 WIB
 * @link https://github.com/ommu/yii-traits
 *
 * Contains many function that most used :
 *	urlTitle
 *
 */

namespace ommu\traits;

use Yii;
use yii\helpers\Html;
use Symfony\Component\Yaml\Yaml;

trait ThemeTrait
{
	/**
	 * {@inheritdoc}
	 */
	public function themeParseYaml($theme)
	{
		$themePath = Yii::getAlias(Yii::$app->params['themePath']);
		$themeFile = $themePath . DIRECTORY_SEPARATOR . $theme . DIRECTORY_SEPARATOR . $theme.'.yaml';
		if(!file_exists($themeFile))
			return false;
		
		return Yaml::parseFile($themeFile);
	}

	/**
	 * {@inheritdoc}
	 */
	public function themeMenu($theme)
	{
		$yaml = $this->themeParseYaml($theme);

		return is_array($yaml) ? $yaml['theme_menu'] : [];
	}
}
