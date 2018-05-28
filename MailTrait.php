<?php
/**
 * MailTrait
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (opensource.ommu.co)
 * @created date 17 April 2018, 08:36 WIB
 * @link https://github.com/ommu/yii2-traits
 *
 * Contains many function that most used :
 *	mailMessage
 *
 */

namespace ommu\traits;

use Yii;
use yii\helpers\Html;
use app\models\CoreMailTemplate;

trait MailTrait
{
	/**
	 * mailMessage
	 */
	public function mailMessage($template, $attributes=null) 
	{
		$templatePath = join('/', [Yii::getAlias('@public'), 'email', 'template']);
		
		$templateCode = CoreMailTemplate::find()
			->select(['template_code'])
			->where(['template' => $template])
			->one();
		
		$messageBody = file_get_contents(join('/', [$templatePath, $templateCode->template_code.'.php']));
		$messageSearch = $messageReplace = [];
		if($attributes && is_array($attributes) && !empty($attributes)) {
			foreach ($attributes as $key => $val) {
				$messageSearch[] = '{'.$key.'}';
				$messageReplace[] = $val;
			}
			return str_ireplace($messageSearch, $messageReplace, $messageBody);

		} else 
			return $messageBody;
	}
}
