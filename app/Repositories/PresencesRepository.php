<?php

namespace App\Repositories;

use App\Interfaces\PresencesRepositoryInterface;
use App\Models\Presences;
use Illuminate\Database\Eloquent\Collection;

class PresencesRepository implements PresencesRepositoryInterface
{
    public function getPresencesByDate(string $dateIn): ?Presences
    {
        return Presences::select([
            'date',
            'clock_in',
            'clock_out'
        ])->whereDate('date', $dateIn)->first();
    }

    public function getAllPresencesByDate(string $year, string $month): ?Collection
    {
        return Presences::select([
            'date',
            'clock_in',
            'clock_out'
        ])
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->orderBy('created_at')
            ->get();
    }

    public function in(array $payloads): Presences
    {
        return Presences::create($payloads);
    }

    public function out(array $payloads): Presences
    {
        $presence = Presences::where('date', $payloads['date'])->firstOrFail();
        $presence->update(['clock_out' =>  $payloads['clock_out']]);
        return $presence;
    }
}
