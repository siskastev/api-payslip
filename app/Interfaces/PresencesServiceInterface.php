<?php

namespace App\Interfaces;

interface PresencesServiceInterface
{
    public function getPresencesByDate(string $datIn);
    public function save(array $payloads);
}
