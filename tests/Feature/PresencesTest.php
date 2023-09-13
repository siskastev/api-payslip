<?php

namespace Tests\Feature;

use App\Models\PaySlip;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Carbon\Carbon;

class PresencesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test storing a month full of presences in January.
     *
     * @return void
     */

    public function testPresensiEndpoint()
    {
        // Skenario 1: 3 presensi dengan keterlambatan minimal 15 menit di bulan Januari pada hari kerja
        $workingDay1 = $this->getRandomWorkingDayInJanuary();
        $this->createPresenceIn($workingDay1, '08:15:00');
        $this->createPresenceOut($workingDay1, '16:00:00');

        $workingDay2 = $this->getRandomWorkingDayInJanuary();
        $this->createPresenceIn($workingDay2, '08:25:00'); // 16 menit terlambat
        $this->createPresenceOut($workingDay2, '16:00:00');

        $workingDay3 = $this->getRandomWorkingDayInJanuary();
        $this->createPresenceIn($workingDay3, '08:20:00'); // 20 menit terlambat
        $this->createPresenceOut($workingDay3, '16:00:00');

        // Skenario 2: 3 presensi dengan keterlambatan minimal 30 menit di bulan Januari pada hari kerja
        $workingDay4 = $this->getRandomWorkingDayInJanuary();
        $this->createPresenceIn($workingDay4, '08:30:00');
        $this->createPresenceOut($workingDay4, '16:00:00');

        $workingDay5 = $this->getRandomWorkingDayInJanuary();
        $this->createPresenceIn($workingDay5, '08:31:00'); // 31 menit terlambat
        $this->createPresenceOut($workingDay5, '16:00:00');

        $workingDay6 = $this->getRandomWorkingDayInJanuary();
        $this->createPresenceIn($workingDay6, '08:45:00'); // 45 menit terlambat
        $this->createPresenceOut($workingDay6, '16:00:00');

        // Skenario 3: 3 presensi dengan keterlambatan minimal 1 jam di bulan Januari pada hari kerja
        $workingDay7 = $this->getRandomWorkingDayInJanuary();
        $this->createPresenceIn($workingDay7, '09:00:00');
        $this->createPresenceOut($workingDay7, '16:00:00');

        $workingDay8 = $this->getRandomWorkingDayInJanuary();
        $this->createPresenceIn($workingDay8, '09:30:00'); // 1:30 menit terlambat
        $this->createPresenceOut($workingDay8, '16:00:00');

        $workingDay9 = $this->getRandomWorkingDayInJanuary();
        $this->createPresenceIn($workingDay9, '09:0:00'); // 1jam terlambat
        $this->createPresenceOut($workingDay9, '16:00:00');

        // Skenario 4: 3 presensi tanpa melakukan clock-out di bulan Januari pada hari kerja
        $workingDay10 = $this->getRandomWorkingDayInJanuary();
        $this->createPresenceIn($workingDay10, '08:00:00'); // 1jam terlambat

        $workingDay11 = $this->getRandomWorkingDayInJanuary();
        $this->createPresenceIn($workingDay11, '08:00:00'); // 1jam terlambat

        $workingDay12 = $this->getRandomWorkingDayInJanuary();
        $this->createPresenceIn($workingDay12, '08:00:00'); // 1jam terlambat
    }

    private function createPresenceIn(Carbon $date, $clockInTime)
    {
        $response = $this->post('/api/presences', [
            'type' => 'in',
            'date' => $date->format('Y-m-d').' '.$clockInTime,
        ]);

        $response->assertStatus(200);
    }

    private function createPresenceOut(Carbon $date, $clockInTime)
    {
        $response = $this->postJson('/api/presences', [
            'type' => 'in',
            'date' => $date->format('Y-m-d').' '.$clockInTime,
        ]);

        $response->assertStatus(200);
    }

    private function getRandomWorkingDayInJanuary()
    {
        $startDate = Carbon::create(2023, 7, 1);
        $endDate = Carbon::create(2023, 7, 31);

        // Loop to find a random working day (Monday to Friday) in January
        while ($startDate <= $endDate) {
            if ($startDate->isWeekday()) {
                return $startDate;
            }
            $startDate->addDay();
        }

        // In case no working day is found (which is unlikely in January)
        return null;
    }
}
