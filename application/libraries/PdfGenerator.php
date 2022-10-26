<?php
use Dompdf\Dompdf as Dompdf;
class PdfGenerator
{
	public function generate($html,$id)
	{
//		define('DOMPDF_ENABLE_AUTOLOAD', false);
		define("DOMPDF_AUTOLOAD_PREPEND", true);
		require 'vendor/autoload.php';
		$options = new \Dompdf\Options();
		$options->set('isRemoteEnabled', true);
		$dompdf = new DOMPDF($options);
		$dompdf->load_html($html);
		$dompdf->render();
		$paper_size = array(400,400,500,500);
		$dompdf->set_paper($paper_size);
		$dompdf->set_paper('letter', 'portrait');
		$dompdf->set_option('isHtml5ParserEnabled', true);
		$dompdf->set_option('isRemoteEnabled', true);
		$destination_folder = $_SERVER['DOCUMENT_ROOT'].'/wiggin/uploads/invoices';
		$output = $dompdf->output();
		$file ="invoice".'_'.$id;
		$file_location = $_SERVER['DOCUMENT_ROOT']."/wiggin/uploads/invoices/".$file.".pdf";
		file_put_contents($file_location,$output);
		return $file;
	}
	public function InOutRevenue($html,$invoice_id)
	{
//		define('DOMPDF_ENABLE_AUTOLOAD', false);
		define("DOMPDF_AUTOLOAD_PREPEND", true);
		require 'vendor/autoload.php';
		$options = new \Dompdf\Options();
		$options->set('isRemoteEnabled', true);
		$dompdf = new DOMPDF($options);
		$dompdf->load_html($html);
		$dompdf->render();
		$paper_size = array(600,600,600,600);
		$dompdf->set_paper($paper_size);
		$dompdf->set_option('isHtml5ParserEnabled', true);
		$dompdf->set_option('isRemoteEnabled', true);
		$destination_folder = $_SERVER['DOCUMENT_ROOT'].'/uploads/invoices';
		$output = $dompdf->output();
		$file ="invoice".'_'.$invoice_id;
		$file_location = $_SERVER['DOCUMENT_ROOT']."/uploads/invoices/".$file.".pdf";
		file_put_contents($file_location,$output);
		return $file;
	}
	public function thermalPdf($html,$invoice_id)
	{
//		define('DOMPDF_ENABLE_AUTOLOAD', false);
		define("DOMPDF_AUTOLOAD_PREPEND", true);
		require 'vendor/autoload.php';
		$options = new \Dompdf\Options();
		$options->set('isRemoteEnabled', true);
		$dompdf = new DOMPDF($options);
		$dompdf->load_html($html);
		$dompdf->render();
		$paper_size = array(100,100,100,100);
		$dompdf->set_paper($paper_size);
		$dompdf->set_option('isHtml5ParserEnabled', true);
		$dompdf->set_option('isRemoteEnabled', true);
		$destination_folder = $_SERVER['DOCUMENT_ROOT'].'/uploads/invoices';
		$output = $dompdf->output();
		$file ="invoice".'_'.$invoice_id;
		$file_location = $_SERVER['DOCUMENT_ROOT']."/uploads/invoices/".$file.".pdf";
		file_put_contents($file_location,$output);
		return $file;
	}
}
