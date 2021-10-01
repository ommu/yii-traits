<?php
/**
 * ThemeTrait
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 21 February 2019, 12:04 WIB
 * @link https://github.com/ommu/yii-traits
 *
 * Contains many function that most used :
 *	themeInfo
 *	themeMenu
 *
 */

namespace ommu\traits;

use Yii;
use yii\helpers\Html;
use Symfony\Component\Yaml\Yaml;

trait ThemeTrait
{
    use \ommu\traits\UtilityTrait;

	/**
	 * {@inheritdoc}
	 */
	public static function themeInfo($theme)
	{
		$themePath = Yii::getAlias(Yii::$app->params['themePath']);
		$themeFile = $themePath . DIRECTORY_SEPARATOR . $theme . DIRECTORY_SEPARATOR . $theme.'.yaml';
		if (!file_exists($themeFile)) {
            return false;
        }
		
		return Yaml::parseFile($themeFile);
	}

	/**
	 * {@inheritdoc}
	 */
	public function themeMenu($theme)
	{
		$yaml = self::themeInfo($theme);

		return is_array($yaml) ? $yaml['theme_menu'] : [];
	}
}
