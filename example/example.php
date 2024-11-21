<?php

require __DIR__ . '/../src/ImageDownloader.php';

use App\ImageDownloader;

$image_downloader = new ImageDownloader();

$image_urls = [
    __DIR__ . '/downloads/image1.jpg' => 'https://example.com/image1.jpg',
    __DIR__ . '/downloads/image2.jpg' => 'https://example.com/image2.jpg',
    __DIR__ . '/downloads/image3.jpg' => 'https://example.com/image3.jpg',
];

$batch_size = 50;

$total_downloaded = $image_downloader->download_images_concurrently($image_urls, $batch_size);

echo "Total images downloaded: $total_downloaded" . PHP_EOL;
