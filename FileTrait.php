<?php
/**
 * FileTrait
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 27 June 2018, 10:55 WIB
 * @link https://github.com/ommu/yii-traits
 *
 * Contains many function that most used :
 *	formatFileType
 *	createUploadDirectory
 *
 */

trait FileTrait 
{
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
			$result = array_map('trim', explode($separator, $data));
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
