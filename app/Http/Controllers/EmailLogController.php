<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class EmailLogController extends Controller
{
    public function index()
    {
        $logPath = storage_path('logs/laravel.log');
        $emails = [];
        
        if (File::exists($logPath)) {
            $logContent = File::get($logPath);
            $lines = explode("\n", $logContent);
            
            foreach ($lines as $line) {
                if (strpos($line, 'brunodalcum') !== false && strpos($line, 'From:') !== false) {
                    $emails[] = $line;
                }
            }
        }
        
        return view('emails.log', compact('emails'));
    }
}
