<?php

class ImageUpload
{
    private const MAX_FILE_SIZE = 5242880;

    public static function save($file, $directory, $relativeDirectory)
    {
        if ($file === null) {
            return null;
        }

        $uploadError = $file['error'] ?? null;
        if ($uploadError === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if ($uploadError !== UPLOAD_ERR_OK || !isset($file['tmp_name'], $file['size'])) {
            throw new RuntimeException("Tải ảnh lên không thành công. Vui lòng thử lại.");
        }

        if ($file['size'] > self::MAX_FILE_SIZE) {
            throw new RuntimeException("Ảnh tải lên không được vượt quá 5 MB.");
        }

        $imageInfo = @getimagesize($file['tmp_name']);
        if ($imageInfo === false) {
            throw new RuntimeException("Tệp tải lên không phải ảnh hợp lệ.");
        }

        $mimeExtensions = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
            'image/bmp' => 'bmp',
            'image/x-ms-bmp' => 'bmp',
            'image/avif' => 'avif'
        ];
        $mime = $imageInfo['mime'] ?? '';

        if (!isset($mimeExtensions[$mime])) {
            throw new RuntimeException("Chỉ hỗ trợ ảnh JPG, PNG, GIF, WEBP, BMP hoặc AVIF.");
        }

        if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
            throw new RuntimeException("Không thể tạo thư mục lưu ảnh.");
        }

        $filename = bin2hex(random_bytes(16)) . '.' . $mimeExtensions[$mime];
        $destination = rtrim($directory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            throw new RuntimeException("Không thể lưu ảnh tải lên.");
        }

        return trim($relativeDirectory, '/\\') . '/' . $filename;
    }
}
