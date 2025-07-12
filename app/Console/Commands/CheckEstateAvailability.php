<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Estate;
use Illuminate\Console\Command;
use App\Events\EstateAvailableForNotification;

class CheckEstateAvailability extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'estates:check-availability';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $estates = Estate::where('status', 'rented')->get();

        foreach ($estates as $estate) {
            $reservation = $estate->reservations->last();

            if ($reservation && Carbon::parse($reservation->end_date)->isPast()) {
                $estate->status = 'available';
                $estate->save();

                event(new EstateAvailableForNotification($estate));
            }
        }

        $this->info('Checked all rented estates.');
    }
}
