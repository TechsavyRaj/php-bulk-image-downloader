<?php

namespace App;

class ImageDownloader
{
    /**
     * Downloads images in bulk concurrently using cURL.
     *
     * @param array $image_urls Array with keys as file paths and values as URLs.
     * @param int $batch_size Number of images to download in each batch.
     * @return int Total number of images successfully downloaded.
     */
    private function download_images_concurrently($image_urls, $batch_size = 100)
    {
        $multi_curl = curl_multi_init();
        $curl_handles = [];
        $completed_requests = 0;
        $failed = [];

        // Process URLs in batches
        foreach (array_chunk($image_urls, $batch_size, true) as $batch) {
            foreach ($batch as $save_path => $image_url) {
                // Ensure the directory for the save path exists
                $directory = dirname($save_path);
                if (!is_dir($directory)) {
                    mkdir($directory, 0777, true);
                }

                $ch = curl_init($image_url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 90000);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_FAILONERROR, true);
                curl_multi_add_handle($multi_curl, $ch);

                $curl_handles[(string)$ch] = [
                    'handle' => $ch,
                    'save_path' => $save_path,
                    'image_url' => $image_url,
                ];
            }

            $active = null;
            do {
                $status = curl_multi_exec($multi_curl, $active);
                if ($active) {
                    curl_multi_select($multi_curl);
                }
            } while ($active && $status == CURLM_OK);

            // Process each completed request
            foreach ($curl_handles as $data) {
                $ch = $data['handle'];
                $save_path = $data['save_path'];
                $image_url = $data['image_url'];

                // Check if there was an error
                if (curl_errno($ch)) {
                    echo 'Error: ' . curl_error($ch) . PHP_EOL;
                    $failed[] = $image_url; // Add failed URL to the array
                } else {
                    $content = curl_multi_getcontent($ch);

                    // Validate the content before saving
                    if (!empty($content) && strlen($content) > 0) {
                        file_put_contents($save_path, $content);
                    } else {
                        $failed[] = $image_url; // Add failed URL if content is invalid
                    }
                }

                curl_multi_remove_handle($multi_curl, $ch);
                curl_close($ch);

                // Free up memory
                unset($curl_handles[(string)$ch]);
            }

            // Increment completed request count
            $completed_requests += count($batch);
        }

        curl_multi_close($multi_curl);
        // Generates a file to log failed URLs
        file_put_contents('failed_logs.json', json_encode($failed));
        return $completed_requests;
    }
}