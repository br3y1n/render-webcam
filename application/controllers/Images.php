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
    public function getImagesList()
    {
        $filesH = array_diff(scandir(FCPATH  . 'assets/images/horizontal'), array('.', '..'));
        $filesV = array_diff(scandir(FCPATH  . 'assets/images/vertical'), array('.', '..'));
        $data =
            [
                'horizontal' => [],
                'vertical' => []
            ];

        if (count($filesH) > 0 || count($filesV) > 0) {

            foreach ($filesH as $file) {
                $path = FCPATH  . 'assets/images/horizontal/' . $file;
                $fileType = pathinfo($path, PATHINFO_EXTENSION);

                if ($fileType == 'png')
                    array_push($data['horizontal'], $file);
            }

            foreach ($filesV as $file) {
                $path = FCPATH  . 'assets/images/vertical/' . $file;
                $fileType = pathinfo($path, PATHINFO_EXTENSION);

                if ($fileType == 'png')
                    array_push($data['vertical'], $file);
            }

            $result = (count($data['horizontal']) > 0 || count($data['vertical']) > 0) ? getResponse('ok', '', $data) : getResponse('error', 'No hay contenido para mostrar');
        } else
            $result = getResponse('error', 'No hay contenido para mostrar');


        $status_request = $result['status'] == 'ok' ? 200 : 400;
        return $this->output->set_status_header($status_request)->set_output(json_encode($result));
    }

    /**
     * Get base64 image
     * 
     * @return JSON 
     */
    public function getImage()
    {
        $imagePath = $this->input->post('imagePath');

        if (isset($imagePath)) {
            $path = FCPATH  . 'assets/images/' . $imagePath;

            if (file_exists($path)) {
                $imageBase64 = getDataURI($path);
                $result = getResponse('ok', '', $imageBase64);

            } else
                $result = getResponse('error', 'Imagen no econtrada');
                
        } else
            $result = getResponse('error', 'Datos invalidos');

        $status_request = $result['status'] == 'ok' ? 200 : 400;
        return $this->output->set_status_header($status_request)->set_output(json_encode($result));
    }
}
