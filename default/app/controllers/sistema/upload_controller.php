<?php

class UploadController extends BackendController
{
    public function index()
    {
        View::template(null);

        if (!isset($_FILES['file'])) {
            return $this->respondJson(false, 'No file uploaded');
        }

        $tempPath = Input::post('tempPath', 'string');
        $finalPath = Input::post('finalPath', 'string');

        $upload = new DwUpload('file', $tempPath);
        $upload->setAllowedTypes('png|jpg|gif|jpeg');
        $upload->setEncryptName(true);
        // Set max file size to 3MB and image dimensions to 800x800
        $upload->setSize('3MB', 800, 800, true);

        if (!$data = $upload->save()) {
            return $this->respondJson(false, $upload->getError());
        }

        return $this->respondJson(true, 'File uploaded successfully', $data);
    }


    public function move()
    {
        View::template(null);

        $fileName = Input::post('fileName');
        $tempPath = trim(Input::post('tempPath'), '/');
        $finalPath = trim(Input::post('finalPath'), '/');
        $oldFileName = Input::post('oldFileName');

        $serverRoot = dirname(APP_PATH) . '/public';
        $tempFile = $serverRoot . '/' . $tempPath . '/' . $fileName;
        $finalFile = $serverRoot . '/' . $finalPath . '/' . $fileName;

        // Create final directory if it doesn't exist
        $finalDir = dirname($finalFile);
        if (!is_dir($finalDir)) {
            mkdir($finalDir, 0755, true);
        }

        // Delete old file if exists and is not default.png
        if ($oldFileName && $oldFileName !== 'default.png') {
            $oldFile = $serverRoot . '/' . $finalPath . '/' . $oldFileName;
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }
        }

        if (!file_exists($tempFile)) {
            return $this->respondJson(false, "Temporary file not found");
        }

        if (rename($tempFile, $finalFile)) {
            return $this->respondJson(true, 'File moved successfully');
        }

        return $this->respondJson(false, 'Error moving file');
    }


    private function respondJson($success, $message, $data = null)
    {
        $response = [
            'success' => $success,
            'message' => $message
        ];

        if ($data) {
            $response['data'] = $data;
        }

        $this->data = $response;
        View::json();
    }
}
