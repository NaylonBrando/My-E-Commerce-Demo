<?php

namespace admin\helper;

class FileHelper
{

    public static function upload(string $formInputName, string $dirPath, int $maxFileSize, int $maxFile): array
    {
        define('UPLOAD_DIR', $dirPath);
        define('UPLOAD_MAX_FILE_SIZE', $maxFileSize * 1048576);
        define('UPLOAD_ALLOWED_MIME_TYPES', 'jpeg,png,jpg');

        if (!is_dir(UPLOAD_DIR)) {
            mkdir(UPLOAD_DIR, 0777, true);
        }

        $filenamesToSave = [];

        $allowedMimeTypes = explode(',', UPLOAD_ALLOWED_MIME_TYPES);

        if (!empty($_FILES)) {
            if (count($_FILES[$formInputName]['name']) > $maxFile) {
                $lessValue = $maxFile;
                $errors[] = 'You can add only ' . $lessValue . ' more image';
                return ['errors' => $errors];
            }
            if (isset($_FILES[$formInputName]['error'])) {
                foreach ($_FILES[$formInputName]['error'] as $uploadedFileKey => $uploadedFileError) {
                    if ($uploadedFileError === UPLOAD_ERR_NO_FILE) {
                        $errors[] = 'You did not provide any files.';
                    } elseif ($uploadedFileError === UPLOAD_ERR_OK) {
                        if ($_FILES[$formInputName]['size'][$uploadedFileKey] <= UPLOAD_MAX_FILE_SIZE) {
                            $uploadedFileType = pathinfo($_FILES[$formInputName]['name'][$uploadedFileKey], PATHINFO_EXTENSION);
                            $uploadedFileTempName = $_FILES[$formInputName]['tmp_name'][$uploadedFileKey];

                            $uploadedFileName = md5(basename($_FILES[$formInputName]['name'][$uploadedFileKey])) . '.' . $uploadedFileType;
                            $uploadedFilePath = rtrim(UPLOAD_DIR, '/') . '/' . $uploadedFileName;

                            if (in_array($uploadedFileType, $allowedMimeTypes)) {
                                if (!move_uploaded_file($uploadedFileTempName, $uploadedFilePath)) {
                                    $errors[] = 'The file "' . $uploadedFileName . '" could not be uploaded.';
                                } else {
                                    $filenamesToSave[] = $uploadedFileName;
                                }
                            } else {
                                $errors[] = 'The extension of the file "' . $_FILES[$formInputName]['name'][$uploadedFileKey] . '" is not valid. Allowed extensions: JPG, JPEG, PNG.';
                            }
                        } else {
                            $errors[] = 'The size of the file "' . $_FILES[$formInputName]['name'][$uploadedFileKey] . '" must be of max. ' . (UPLOAD_MAX_FILE_SIZE / 1048576) . ' MB';
                        }
                    }
                }
            }
        }
        if (isset($filenamesToSave) && !isset($errors)) {
            return ['fileNames' => $filenamesToSave];
        } elseif (!isset($filenamesToSave) && isset($errors)) {
            return ['errors' => $errors];
        } else {
            return ['fileNames' => $filenamesToSave, 'errors' => $errors];
        }
    }

    public static function delete(string $path, string $fileName): bool
    {
        $filePath = $path . $fileName;
        if (unlink($filePath)) {
            return true;
        } else {
            return false;
        }

    }

}