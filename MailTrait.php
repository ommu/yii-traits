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
 *	parseTemplate
 *
 *	getMailFrom
 *	getMailTemplatePath
 *	getMailMessage
 *	parseMailSubject
 *	parseMailBody
 *
 */

namespace ommu\traits;

use Yii;
use yii\helpers\Html;
use app\models\CoreMailSetting;
use app\models\CoreMailTemplate;

trait MailTrait
{
	/**
	 * getMailFrom
	 *
	 * @return array
	 */
	public function getMailFrom()
	{
		$model = CoreMailSetting::find()
			->select(['mail_name','mail_from'])
			->where(['id' => 1])
			->one();

		if($model == null)
			return false;
		
		$mail_name = $model->mail_name ? $model->mail_name : $model->mail_from;
		return [$model->mail_from => $mail_name];
	}

	/**
	 * getMailTemplatePath
	 * 
	 * @return string
	 */
	public function getMailTemplatePath()
	{
		$templatePath = join('/', [Yii::getAlias('@public'), 'email', 'template']);

		return $templatePath;
	}

	/**
	 * getMailMessage
	 * 
	 * @param string $template
	 * @return string
	 */
	public function getMailMessage($template)
	{
		$template = $template.'.php';
		$templateFile = join('/', [$this->getMailTemplatePath(), $template]);

		$message = '';
		if(file_exists($templateFile))
			$message = file_get_contents($templateFile);

		return $message;
	}

	/**
	 * Method for parsing string
	 * 
	 * @param string $message Source string for parsing
	 * @param array $attribute of example
	 * [
	 *		'{link}' => 'https://github.com/black-lamp/yii2-email-templates',
	 *		'{author}' => Yii::$app->user->email
	 *	]
	 * 
	 * @return string
	 */
	public function parseTemplate($message, $attribute)
	{
		foreach ($attribute as $key => $value) {
			$message = strtr($message, [
				'{'.$key.'}' => $value,
			]);
		}

		return $message;
	}

	/**
	 * Parsing of email subject
	 * 
	 * @return string
	 */
	public function parseMailSubject($template) 
	{
		$model = CoreMailTemplate::find()
			->select(['subject'])
			->where(['template' => $template])
			->one();
		
		$message = $this->parseTemplate($model->subject, ['sitename' => Yii::$app->name]);
		
		return $message;
	}

	/**
	 * Parsing of email body
	 * 
	 * @return string
	 */
	public function parseMailBody($template, $attributes=null) 
	{
		$model = CoreMailTemplate::find()
			->select(['template_file'])
			->where(['template' => $template])
			->one();
		
		$message = $this->parseTemplate($this->getMailMessage($model->template_file), $attributes);
		
		return $message;
	}
}
