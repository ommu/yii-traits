<?php
/**
 * FileTrait
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (opensource.ommu.co)
 * @created date 17 April 2018, 08:36 WIB
 * @link https://github.com/ommu/yii2-traits
 *
 * FileTrait berisi kumpulan fungsi yang berhubungan sistem file, seperti buat folder, hapus folder,
 * normalisasi nama file sehingga url friendly dll.
 *
 * Contains many function that most used :
 *	urlTitle
 *	formatFileType
 *	createUploadDirectory
 *
 */

namespace ommu\traits;

trait FileTrait {
	
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
		if($separator === 'dash')
			$separator = '-';
			
		elseif($separator === 'underscore')
			$separator = '_';

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

		if ($lowercase === true)
			$str = strtolower($str);

		return trim(trim($str, $separator));
	}

	/**
	 * Explode or Implode Function
	 *
	 * @param array/string string if param $type=true and array if param $type=false
	 * @param bool $type true (explode), false (implode)
	 * @param string $separator Word separator (usually '-' or '_')
	 */
	public function formatFileType($data, $type=true, $separator=',') 
	{
		if($type == true)
			$result = array_map("trim", explode($separator, $data));
		else
			$result = implode($separator.' ', $data);
			
		return $result; 
	}

	/**
	 * Generate upload directory if directory not found in application
	 */
	public function createUploadDirectory($path, $key=null) 
	{
		$uploadPath = $path;
		if($key != null)
			$uploadPath = join('/', [$path, $key]);
		$verwijderenPath = join('/', [$path, 'verwijderen']);
		
		// Add directory
		if(!file_exists($uploadPath) || !file_exists($verwijderenPath)) {
			if($key != null)
				@mkdir($path, 0755, true);
			@mkdir($uploadPath, 0755, true);
			@mkdir($verwijderenPath, 0755, true);

			// Add file in directory (index.php)
			$indexFile = join('/', [$uploadPath, 'index.php']);
			if(!file_exists($indexFile))
				file_put_contents($indexFile, "<?php\n");
				
			$verwijderenFile = join('/', [$verwijderenPath, 'index.php']);
			if(!file_exists($verwijderenFile))
				file_put_contents($verwijderenFile, "<?php\n");
		} else {
			@chmod($uploadPath, 0755, true);
			@chmod($verwijderenPath, 0755, true);
		}
		
		return true;
	}
}
