<?php
namespace App\Services;

class QrService
{
    public function getQrUrl(string $roomCode): string
    {
        $joinUrl = url('/join?room=' . $roomCode);
        return "https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=" . urlencode($joinUrl);
    }
}
