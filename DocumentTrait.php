<?php
/**
 * DocumentTrait
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 9 July 2019, 15:58 WIB
 * @link https://github.com/ommu/yii-traits
 *
 * Contains many function that most used :
 *	getPdf
 *
 */

namespace ommu\traits;

use Yii;
use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;

trait DocumentTrait
{
	public function getPdf($attribute, $template, $outputPath, $outputName, $preview=false, $returnIsPath=false, $orientation='P', $format='A4')
	{
		try {
			extract($attribute);

			ob_start();
			include $template;
			$content = ob_get_clean();

			$html2pdf = new Html2Pdf($orientation, $format, 'en', true, 'UTF-8', array(0, 0, 0, 0));
			$html2pdf->pdf->SetDisplayMode('fullpage');
		
			$html2pdf->writeHTML($content);

			$fileName = $outputName.'.pdf';
			$filePath = join('/', [$outputPath, $fileName]);

            if ($preview == false) {
                $html2pdf->output($filePath, 'F');
            } else {
                $html2pdf->output($filePath);
            }
		
            if ($returnIsPath == true) {
                return $filePath;
            } else {
                return $fileName;
            }

		} catch(Html2PdfException $ex) {
			Yii::error(print_r($ex->getMessage(), true));
			$html2pdf->clean();

			$formatter = new ExceptionFormatter($ex);
			echo $formatter->getHtmlMessage();
		}
	}
}
