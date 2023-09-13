<?php

namespace App\Services;

use App\Interfaces\PresencesServiceInterface;
use App\Repositories\PresencesRepository;
use Carbon\Carbon;
use Exception;

class PresencesService implements PresencesServiceInterface
{
    private $presencesRepository;
    private const TYPE_IN = "in";
    private const TYPE_OUT = "out";

    public function __construct(PresencesRepository $presencesRepository)
    {
        $this->presencesRepository = $presencesRepository;
    }

    public function getPresencesByDate(string $date)
    {
        return $this->presencesRepository->getPresencesByDate($date);
    }

    public function save(array $payloads)
    {
        try {
            $type = $payloads['type'];
            $carbonDateTime = Carbon::parse($payloads['date']);

            $result = [];

            switch ($type) {
                case self::TYPE_IN:
                    $result = $this->presencesRepository->in([
                        'date' => $carbonDateTime->toDateString(),
                        'clock_in' => $carbonDateTime->toTimeString(),
                    ]);
                    break;
                case self::TYPE_OUT:
                    $result = $this->presencesRepository->out([
                        'date' => $carbonDateTime->toDateString(),
                        'clock_out' => $carbonDateTime->toTimeString(),
                    ]);
                    break;

                default:
                    break;
            }

            return [
                'success' => true,
                'data' => $result,
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
