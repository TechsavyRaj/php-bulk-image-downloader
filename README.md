# PHP Bulk Image Downloader  

A lightweight PHP tool for downloading multiple images concurrently in bulk using cURL. This utility is designed to handle large-scale downloads efficiently, making it an ideal solution for developers managing large datasets or automating image collection workflows.  

## Features  
- **Batch Processing**: Downloads images in customizable batches to optimize memory and network usage.  
- **Error Handling**: Automatically logs failed downloads to a `failed_logs.json` file for easy debugging.  
- **Retry Support**: Robust handling of timeouts and failed requests.  
- **Highly Configurable**: Specify batch sizes and customize the download paths.  

## Installation  
1. Clone the repository:  
   ```bash
   git clone https://github.com/username/php-bulk-image-downloader.git
   ```  
2. Include the `ImageDownloader` class in your project to start downloading images.  

## Usage  
Please refer to the example in `example/example.php` for guidance on using the tool effectively.  
