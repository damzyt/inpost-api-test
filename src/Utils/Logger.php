<?php

declare(strict_types=1);

namespace App\Utils;

/**
 * Class Logger
 * Simple logger for handling console output and file logging.
 * 
 * Provides methods for different types of messages with automatic formatting
 * and optional file logging functionality.
 * 
 * @package App\Utils
 */
final class Logger
{
    private string $logFile;

    /**
     * Constructor for Logger.
     * 
     * @param string $logFile The file path for logging messages (default: 'log.txt')
     */
    public function __construct(string $logFile = 'log.txt')
    {
        $this->logFile = $logFile;
    }

    /**
     * Log a process message (displayed and logged).
     * 
     * @param string $message The message to log
     * @param array|null $data Optional data to log in JSON format (file only)
     */
    public function process(string $message, ?array $data = null): void
    {
        $this->output("[PROCESS] {$message}");
        $this->writeToFile($message);
        
        if ($data !== null) {
            $this->logJsonData("Process data", $data);
        }
    }

    /**
     * Log a success message (displayed and logged).
     * 
     * @param string $message The message to log
     * @param array|null $data Optional data to log in JSON format (file only)
     */
    public function success(string $message, ?array $data = null): void
    {
        $this->output("[SUCCESS] {$message}");
        $this->writeToFile("[SUCCESS] {$message}");
        
        if ($data !== null) {
            $this->logJsonData("Success data", $data);
        }
    }

    /**
     * Log an info message (displayed and logged).
     * 
     * @param string $message The message to log
     * @param array|null $data Optional data to log in JSON format (file only)
     */
    public function info(string $message, ?array $data = null): void
    {
        $this->output("[INFO] {$message}");
        $this->writeToFile("[INFO] {$message}");
        
        if ($data !== null) {
            $this->logJsonData("Info data", $data);
        }
    }

    /**
     * Log an error message (displayed and logged).
     * 
     * @param string $message The message to log
     * @param array|null $data Optional data to log in JSON format (file only)
     */
    public function error(string $message, ?array $data = null): void
    {
        $this->output("[ERROR] {$message}");
        $this->writeToFile("[ERROR] {$message}");
        
        if ($data !== null) {
            $this->logJsonData("Error data", $data);
        }
    }

    /**
     * Log detailed data (JSON format, file only).
     * 
     * @param string $label Label for the data
     * @param array $data Data to log in JSON format
     */
    public function data(string $label, array $data): void
    {
        $this->logJsonData($label, $data);
    }

    /**
     * Log a separator message (for starting new operations).
     * 
     * @param string $message The separator message
     */
    public function separator(string $message): void
    {
        $separator = "--- {$message} ---";
        $this->writeToFile($separator);
    }

    /**
     * Output message to console with automatic line ending.
     * 
     * @param string $message The message to output
     */
    private function output(string $message): void
    {
        echo $message . PHP_EOL;
    }

    /**
     * Write message to log file with timestamp.
     * 
     * @param string $message The message to write to file
     */
    private function writeToFile(string $message): void
    {
        $timestamp = date('Y-m-d H:i:s');
        file_put_contents($this->logFile, "[{$timestamp}] {$message}" . PHP_EOL, FILE_APPEND);
    }

    /**
     * Log JSON data to file with label.
     * 
     * @param string $label Label for the JSON data
     * @param array $data Data to log in JSON format
     */
    private function logJsonData(string $label, array $data): void
    {
        $jsonData = json_encode($data, JSON_PRETTY_PRINT);
        $this->writeToFile("[JSON] {$label}:" . PHP_EOL . $jsonData);
    }
}
