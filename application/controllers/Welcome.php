<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
class Welcome extends CI_Controller {

	public function invoice_pdf(){
		error_reporting(E_ALL ^ E_DEPRECATED);
		$this->load->library('PdfGenerator');
		$data='';
		$html = $this->load->view('frontend/pdf/invoice',$data, TRUE);
		$file= $this->pdfgenerator->generate($html,'invoice', array("Attachment" => false));
		echo $file; die;
//		$this->dompdf->load_html($html);
//		$this->dompdf->render();
//		$this->dompdf->stream("invoice".now().".pdf");

	}

	// Repair Invoice
	public function therMal() {
		header('Content-type: application/json');
		error_reporting(E_ALL ^ E_DEPRECATED);
		$this->load->library('PdfGenerator');
		$data="";

		$html = $this->load->view('frontend/pdf/thermal',$data, TRUE);
		$file= $this->pdfgenerator->thermalPdf($html,rand(), array("Attachment" => false));
		$data = array('file_path'=>base_url('uploads/invoices/').$file.'.pdf');
		$response = array();
		$response['status'] = 200;
		$response['message'] ="invoice generated successfully.";
		$response['data'] = $data;
		echo json_encode($response);
		exit();
		}

	public function repairInvoice() {
		header('Content-type: application/json');
		error_reporting(E_ALL ^ E_DEPRECATED);
		$this->load->library('PdfGenerator');
		$data=$_POST;
		if($data['var_Item']){
			$itemsArray=array();
			$var_Item=explode(',',$data['var_Item']);
			foreach ($var_Item as $key => $items){
				if(isset($var_Item[$key])){
					$item['item']=$var_Item[$key];
				}
				array_push($itemsArray,$item);
			}
		}
		if($itemsArray){
			$data['items']=$itemsArray;
		}
		$html = $this->load->view('frontend/pdf/repair',$data, TRUE);
		$file= $this->pdfgenerator->generate($html,$data['invoice_id'], array("Attachment" => false));
		$data = array('file_path'=>base_url('uploads/invoices/').$file.'.pdf');
		$response = array();
		$response['status'] = 200;
		$response['message'] ="invoice generated successfully.";
		$response['data'] = $data;
		echo json_encode($response);
		exit();
	}

	// Unlock Invoice
	public function unlockInvoice() {
		header('Content-type: application/json');
		error_reporting(E_ALL ^ E_DEPRECATED);
		$this->load->library('PdfGenerator');
		$data=$_POST;
		$data['total_amount']=0;
		if($data['var_Item']){
			$itemsArray=array();
			$var_Item=explode(',',$data['var_Item']);
			$var_unit=explode(',',$data['var_unit']);
			foreach ($var_Item as $key => $items){
				if(isset($var_Item[$key])){
					$item['item']=$var_Item[$key];
				}
				if(isset($var_unit[$key])){
					$item['unit']=$var_unit[$key];
					$item['total']=($var_unit[$key]);
				}
				array_push($itemsArray,$item);
			}
		}
		if($itemsArray){
			$data['items']=$itemsArray;
		}
		$html = $this->load->view('frontend/pdf/unlock',$data, TRUE);
		$file= $this->pdfgenerator->generate($html,$data['invoice_id'], array("Attachment" => false));
		$data = array('file_path'=>base_url('uploads/invoices/').$file.'.pdf');
		$response = array();
		$response['status'] = 200;
		$response['message'] ="unlock generated successfully.";
		$response['data'] = $data;
		echo json_encode($response);
		exit();
		}

	//Invoice Work
	public function invoice() {

		header('Content-type: application/json');
		error_reporting(E_ALL ^ E_DEPRECATED);
		$this->load->library('PdfGenerator');

		$data=$_POST;
		$id=$_POST['id'];
        $user_id = $this->apis->validateApiKey();
        $invoiceDetails = $this->invoice->getInvoicePdf($id);
        $invoice = $this->invoice->getInvoicePdfItems($id);
        $total_amount = $this->invoice->productTotalAmount($id);
        $total_invoice=$this->invoice->getDeliveryInvoiced($id);
        $data = array();
        $data['items'] = $invoiceDetails;
        $data['invoice_item'] = $invoice;
        $data['total_amount']=$total_amount;
        $data['total_invoice']=$total_invoice;
		$html = $this->load->view('frontend/pdf/invoice',$data, TRUE);
		$file= $this->pdfgenerator->generate($html,$id, array("Attachment" => false));
        $data = array('file_path'=>base_url('uploads/invoices/').$file.'.pdf');
		$response = array();
		$response['status'] = 200;
		$response['message'] ="invoice generated successfully.";
		$response['data'] = $data;
		echo json_encode($response);
		//exit();
		}
	public function index()
	{
		$data=[];
		$data['videos']=$this->videos();
		$data['liveStatus']=$this->liveStatus();
		$data['heramieantes']=$this->heramieantes();
		$this->load->view('frontend/homepage',$data);
	}
	public function liveStream(){
		$live=$this->liveStatus();
		if(isset($live['code']) && isset($_POST['CodeStream'])){
			if($live['code']==$_POST['CodeStream']){
				echo json_encode(array('responce'=>'success','msg' => 'success','url'=>$live['url']));
			}else{
				echo json_encode(array('responce'=>'failure','msg' => 'success'));
			}
		}else{
			echo json_encode(array('responce'=>'failure','msg' => 'success'));
		}
	}

	public function heramieantes(){
		$curl = curl_init();
		curl_setopt_array($curl, array(
			// CURLOPT_URL => 'https://e2f486ca4e838b81e0f86038167931fc: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@tech-spot-pr.myshopify.com/admin/products.json',
			CURLOPT_URL => 'https://126a7e58356df807428cad0eea3364f9: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@repair-spots-inc.myshopify.com/admin/products.json',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
		));
		$response = curl_exec($curl);
		curl_close($curl);
		$data=json_decode($response,true);
		$videos=array();
		$Taller=array();
		$Negocios=array();
		$Home=array();
		if($data){
			foreach ($data as $record){
				foreach ($record as $rec){
					$rcrd=explode(',',$rec['tags']);
					if(in_array('hometag',$rcrd)){
						array_push($videos,$rec);
						//array_push($Home,$rec);
					}if($rec['product_type']=='Taller'){
						array_push($Taller,$rec);
					}if($rec['product_type']=='Negocios'){
						array_push($Negocios,$rec);
					}
					if($rec['product_type']=='Tools' || $rec['product_type']=='Parts'){
						array_push($Home,$rec);
					}
				}
			}
		}
		return array("videos"=>$videos,"Taller"=>$Taller,"Negocios"=>$Negocios,'Tools'=>$Home);
	}
	public function liveStatus(){
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://repairspots.org/api/v3/account/version/5c57680e1e94e249a92d2d01',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
		));
		$response = curl_exec($curl);
		curl_close($curl);
		$data=json_decode($response,true);
		return $data;
	}
	public function videos(){
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://repairspots.org/api/v3//utility/tutorials',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
		));
		$response = curl_exec($curl);
		curl_close($curl);
		$data=json_decode($response,true);
		return $data;
	}
}
