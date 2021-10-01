<?php
/**
 * UtilityTrait
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 12 May 2018, 22:47 WIB
 * @modified date 18 April 2019, 10:06 WIB
 * @link https://github.com/ommu/yii-traits
 *
 * Contains many function that most used :
 *	urlTitle
 *	licenseCode
 *	quickAction
 *	filterYesNo
 *	convertSmartQuotes
 *	htmlSoftDecode
 *	htmlHardDecode
 *  parseAddress
 *  joinString
 *  parseContact
 *  asDatetime
 *  strToArray
 *  arrayToStr
 *  getUserIP
 *  shortText
 *  shortTitle
 *  loadYaml
 *
 */

namespace ommu\traits;

use Yii;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use Symfony\Component\Yaml\Yaml;

trait UtilityTrait
{
	/**
	 * Create URL Title
	 *
	 * Takes a "title" string as input and creates a
	 * human-friendly URL string with a "separator" string
	 * as the word separator.
	 *
	 * @todo Remove old 'dash' and 'underscore' usage in 3.1+.
	 * @param string $str Input string
	 * @param string $separator Word separator (usually '-' or '_')
	 * @param bool $lowercase  Wether to transform the output string to lowercase
	 * @return string
	 */
	public function urlTitle($str, $separator = '-', $lowercase = true)
	{
		$str = trim($str);

		if ($separator === 'dash') {
            $separator = '-';
        } elseif ($separator === 'underscore') {
            $separator = '_';
        }

		$qSeparator = preg_quote($separator, '#');
		$trans = [
			'&.+?:;' => '',
			'[^a-z0-9 _-]' => '',
			'\s+' => $separator,
			'('.$qSeparator.')+' => $separator
		];

		$str = strip_tags($str);
		foreach ($trans as $key => $val)
            $str = preg_replace('#'.$key.'#i', $val, $str);

		if ($lowercase === true) {
            $str = strtolower($str);
        }

		return trim(trim($str, $separator));
	}

	/**
	 * get License
	 */
	public function licenseCode($source='1234567890', $length=16, $char=4)
	{
		$mod = $length%$char;
		if ($mod == 0) {
            $sep = ($length/$char);
        } else {
            $sep = (int)($length/$char)+1;
        }
		
		$sourceLength = strlen($source);
		$random = '';
		for ($i = 0; $i < $length; $i++)
			$random .= $source[rand(0, $sourceLength - 1)];
		
		$license = '';
		for ($i = 0; $i < $sep; $i++) {
			if ($i != $sep-1) {
                $license .= substr($random,($i*$char),$char).'-';
            } else {
                $license .= substr($random,($i*$char),$char);
            }
		}

		return $license;
	}
	
	/**
	 * quickAction
	 *
	 * @return array
	 */
	public function quickAction($url, $id, $alert=null, $single=false)
	{
		if ($id == 2) {
            return Yii::t('app', 'Deleted');
        }

		if ($alert == null) {
            $alert = 'Publish,Unpublish';
        }
		$alertArray = explode('#', $alert);

		$textArray = $titleArray = explode(',', $alert);
		if (count($alertArray) != 1) {
			$textArray = explode(',', $alertArray[0]);
			$titleArray = explode(',', $alertArray[1]);
		}


		$text = $id == 1 ? Yii::t('app', $textArray[0]) : Yii::t('app', $textArray[1]);
		$title = $id == 1 ? Yii::t('app', $titleArray[1]) : Yii::t('app', $titleArray[0]);
		$message = Yii::t('app', 'Are you sure you want to {text} this item?', array(
			'text' => strtolower($title),
		));

		if ($single == true && $id == 1) {
            return Yii::t('app', ucwords(strtolower($textArray[0])));

        } else {
			return Html::a(ucwords(strtolower($text)), $url, [
				'title' => Yii::t('app', 'Click to {title}', array('title' => strtolower($title))),
				'data-confirm' => $message,
				'data-method' => 'post',
			]);
		}
	}
	
	/**
	 * filterYesNo
	 * 
	 * @return array
	 */
	public function filterYesNo($value=null) 
	{
		$items = [
			0 => Yii::t('app', 'No'),
			1 => Yii::t('app', 'Yes'),
		];

		if ($value !== null) {
            return $items[$value];
        }
		
		return $items;
	}

	/**
	 * replace smart quotes or franky ugly char by micrsooft word 'copas'
	 * 
	 * @param string $string
	 * @return string
	 */
	public static function convertSmartQuotes($string)
	{
		$search = array(chr(145), chr(146), chr(147), chr(148), chr(151), chr(150), chr(133), chr(149));
		$replace = array("'", "'", '"', '"', '--', '-', '...', "&bull;");
		return str_replace($search, $replace, $string);
	}

	/**
	 * Cleaning html entities for detail view, so it still 
	 * html tag<p> or <strong>,etc
	 * 
	 * @param string $string
	 * @return string
	 */
	public static function htmlSoftDecode($string)
	{
		/*
		$data = htmlspecialchars_decode($string);
		$data= html_entity_decode($string);
		$data = ereg_replace("&quot;", chr(34),$data);
		$data = ereg_replace("&lt;", chr(60),$data);
		$data = ereg_replace("&gt;", chr(62),$data);
		$data = ereg_replace("&amp;", chr(38),$data);
		$data = ereg_replace("&nbsp;", chr(32),$data);
		$data = ereg_replace("&amp;nbsp;", "",$data);
		$data= html_entity_decode($data);
		*/

		$data = get_html_translation_table(HTML_SPECIALCHARS, ENT_QUOTES);
		$data = array_flip($data);
		$original = strtr($string, $data);

		return $original;
	}

	/**
	 * Super Cleaning for decode and strip all html tag
	 * 
	 * @param string $string
	 * @return string
	 */
	public static function htmlHardDecode($string)
	{
		$data = htmlspecialchars_decode($string);
		$data = html_entity_decode($data);
		$data = strip_tags($data);
		$data = chop(self::convertSmartQuotes($data));
		$data = str_replace(array("\r", "\n", "	"), "", $data);

		return ($data);
	}

	/**
	 * parse Address
	 * 
	 * @param array $address
	 * @return string
	 */
	public static function parseAddress($address)
	{
        // place
        if ($address['place'] != '') {
            $data = self::joinString($address['place']);
        }
        // village
        if ($address['village'] != '') {
            $data = self::joinString($address['village'], $data);
        }
        // district
        if ($address['district'] != '') {
            $data = self::joinString($address['district'], $data);
        }
        // city
        if ($address['city'] != '') {
            $city = \ommu\core\models\CoreZoneCity::getInfo($address['city'], 'city_name');
            $data = self::joinString($city, $data);
        }
        // province
        if ($address['province'] != '') {
            $province = \ommu\core\models\CoreZoneProvince::getInfo($address['province'], 'province_name');
            $data = self::joinString($province, $data);
        }
        // zipcode
        if ($address['zipcode'] != '') {
            $data = self::joinString($address['zipcode'], $data);
        }
        // country
        if ($address['country'] != '') {
            $country = \ommu\core\models\CoreZoneCountry::getInfo($address['country'], 'country_name');
            $data = self::joinString($country, $data);
        }

        if ($data != '') {
            return $data;
        }

		return '-';
    }

	/**
	 * {@inheritdoc}
	 */
	public static function joinString($data, $oldData='')
	{
        if ($oldData == '') {
            $data = $data;
        } else {
            $data = join(', ', [$oldData, $data]);
        }

        return $data;
	}

	/**
	 * parse Address
	 * 
	 * @param array $address
	 * @return string
	 */
	public static function parseContact($contact)
	{
        $data = [];
        // phone
        if ($contact['phone'] != '') {
            $data = ArrayHelper::merge($data, [
                Yii::t('app', 'Phone: {phone}', [
                    'phone' => $contact['phone'],
                ]),
            ]);
        }
        // fax
        if ($contact['fax'] != '') {
            $data = ArrayHelper::merge($data, [
                Yii::t('app', 'FAX: {fax}', [
                    'fax' => $contact['fax'],
                ]),
            ]);
        }
        // hotline
        if ($contact['hotline'] != '') {
            $hotline = preg_split('/\n|\r\n?\s*/', $contact['hotline']);
            $data = ArrayHelper::merge($data, [
                Yii::t('app', 'Hotline: {hotline}', [
                    'hotline' => implode($hotline, ', '),
                ]),
            ]);
        }
        // email
        if ($contact['email'] != '') {
            $data = ArrayHelper::merge($data, [
                Yii::t('app', 'Email: {email}', [
                    'email' => Yii::$app->formatter->asEmail($contact['email']),
                ]),
            ]);
        }
        // website
        if ($contact['website'] != '') {
            $data = ArrayHelper::merge($data, [
                Yii::t('app', 'Website: {website}', [
                    'website' => Yii::$app->formatter->asUrl($contact['website']),
                ]),
            ]);
        }

        if (!empty($data)) {
            return Html::ul($data, ['encode' => false, 'class' => 'list-boxed']);
        }

		return '-';
	}

	/**
	 * {@inheritdoc}
	 */
	public function asDatetime($datetime, $dateFormat='medium', $timeFormat='long')
	{
        return Yii::$app->formatter->asDatetime($datetime, 'medium');
	}

    /**
	 * {@inheritdoc}
     *
     * @param string $data
     * @param string $separator Word separator (usually '-' or '_')
     */
    public function strToArray($data, $separator=',') 
    {
        return array_map('trim', explode($separator, $data));
    }

    /**
	 * {@inheritdoc}
     *
     * @param array $data
     * @param string $separator Word separator (usually '-' or '_')
     */
    public function arrayToStr($data, $separator=', ') 
    {
        return implode($separator, $data);
    }

    /**
     * The function to get the visitor's IP.
     */
    public function getUserIP()
    {
        //check ip from share internet
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        }

        //to check ip is pass from proxy
        else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else if (!empty($_SERVER['HTTP_X_FORWARDED'])) {
            return $_SERVER['HTTP_X_FORWARDED'];
        } else if (!empty($_SERVER['HTTP_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_FORWARDED_FOR'];
        } else if (!empty($_SERVER['HTTP_FORWARDED'])) {
            return $_SERVER['HTTP_FORWARDED'];
        } else {
            return $_SERVER['REMOTE_ADDR'];
        }
    }

	/**
	 * {@inheritdoc}
	 */
    function shortText($text, $len=60, $dotted = "...")
    {
        $text = trim($text);
        if (strlen($text) > $len) {
            $rpos = strrpos(substr($text, 0, $len), " ");
            if ($rpos!==false) {
                // if there's whitespace, cut off at last whitespace
                return substr($text, 0, $rpos) . $dotted;
            } else {
                // otherwise, just cut after $len chars
                return substr($text, 0, $len) . $dotted;
            }

        } else {
            return $text;
        }
    }

	/**
	 * {@inheritdoc}
	 */
    function shortTitle($text, $len=20, $dotted = "...")
    {
        return $this->shortText($text, $len, $dotted);
    }

	/**
	 * {@inheritdoc}
	 */
	public static function loadYaml($yamlFile)
	{
		if (!file_exists($yamlFile)) {
            return false;
        }
		
		return Yaml::parseFile($yamlFile);
	}
}
