<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Images extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('tools_helper');
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        header("Access-Control-Allow-Methods: GET,POST");
        header('content-type: application/json; charset=utf-8');
    }

    /**
     * Get base64 image
     * 
     * @return JSON 
     */
    public function getImages()
    {
        $files = array_diff(scandir(FCPATH  . 'assets/images'), array('.', '..'));

        if (count($files) > 0) {
            $data = [];

            foreach ($files as $file) {
                $path = FCPATH  . 'assets/images/' . $file;
                $fileType = pathinfo($path, PATHINFO_EXTENSION);

                if ($fileType == 'png') {
                    $imageBase64 = getDataURI($path);
                    array_push($data, $imageBase64);
                }
            }

            $result = (count($data) > 0) ? getResponse('ok', '', $data) : getResponse('error', 'No hay contenido para mostrar');
            
        } else
            $result = getResponse('error', 'No hay contenido para mostrar');


        $status_request = $result['status'] == 'ok' ? 200 : 400;
        return $this->output->set_status_header($status_request)->set_output(json_encode($result));
    }
}
