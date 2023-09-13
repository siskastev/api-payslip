<?php

namespace App\Interfaces;

use App\Models\Presences;
use Illuminate\Database\Eloquent\Collection;

interface PresencesRepositoryInterface
{
    public function getPresencesByDate(string $datIn): ?Presences;
    public function getAllPresencesByDate(string $year, string $month): ?Collection;
    public function in(array $dataIn): Presences;
    public function out(array $dataOut): Presences;
}
