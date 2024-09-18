<?php
namespace Startupful\ContentsGenerate\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Routing\Controller as BaseController;

class NotificationController extends BaseController
{
    public function sendNotification($type, $message)
    {
        Log::info("Notification sent", ['type' => $type, 'message' => $message]);
        // Implementation for sending notifications
    }
}