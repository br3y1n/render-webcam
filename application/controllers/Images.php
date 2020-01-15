<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Images extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['tools_helper', 'directory']);
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
        $allowedDirectories = ['vertical\\', 'horizontal\\'];
        $directoryImages = FCPATH  . 'assets/images/';
        $allowedExt = 'png';
        $directories = array_intersect_key(directory_map($directoryImages), array_flip($allowedDirectories));
        $data = [
            'images' => [
                'horizontal' => [],
                'vertical' => []
            ]
        ];

        foreach ($directories as $directoryName => $directory) {
            $norDirName = str_replace('\\', '', $directoryName);
            foreach ($directory as $file) {
                if (is_string($file)) {
                    $path = $directoryImages . $directoryName . $file;
                    if (pathinfo($path, PATHINFO_EXTENSION) == $allowedExt) {;
                        $imageSize = getimagesize($path);
                        $width = $imageSize[0];
                        $height = $imageSize[1];

                        if (isset($data['images'][$norDirName]['sizes'])) {
                            $sizes = $data['images'][$norDirName]['sizes'];
                            if (is_array($sizes) && ($sizes['width'] != $width || $sizes['height'] != $height))

                                $data['images'][$norDirName]['sizes'] = 'noStandardized';
                        } else
                            $data['images'][$norDirName]['sizes'] = [
                                'width' => $width,
                                'height' => $height
                            ];

                        if (isset($data['images'][$norDirName]['list']))
                            array_push($data['images'][$norDirName]['list'], $file);

                        else
                            $data['images'][$norDirName]['list'] = [$file];
                    }
                }
            }
        }

        $result = (count($data['images']['horizontal']) > 0 || count($data['images']['vertical']) > 0) ? getResponse('ok', '', $data) : getResponse('error', 'No hay imagenes para mostrar');

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
