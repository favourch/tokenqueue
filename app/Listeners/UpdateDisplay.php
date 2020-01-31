<?php

namespace App\Listeners;

use App\Events\TokenCalled;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Call;
use Carbon\Carbon;

class UpdateDisplay
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    // ToDo: ASER - Display - To get display data for a particular department only, modify this Call::with and add a where condition E.G ->where('department_id', '1')
    public function handle(TokenCalled $event)
    {
        $calls = Call::with('department', 'counter')
                    ->where('called_date', Carbon::now()->format('Y-m-d'))
                    ->orderBy('calls.id', 'desc')
                    ->take(4)
                    ->get();

        $data = [];
        // ToDo: ASER - Display -  here add field for expired or so 
        for ($i=0;$i<4;$i++) {
            $data[$i]['call_id'] = (isset($calls[$i]))?$calls[$i]->id:'NIL';
            $data[$i]['number'] = (isset($calls[$i]))?(($calls[$i]->department->letter!='')?$calls[$i]->department->letter.'-'.$calls[$i]->number:$calls[$i]->number):'NIL';
            $data[$i]['call_number'] = (isset($calls[$i]))?(($calls[$i]->department->letter!='')?$calls[$i]->department->letter.' '.$calls[$i]->number:$calls[$i]->number):'NIL';
            $data[$i]['counter'] = (isset($calls[$i]))?$calls[$i]->counter->name:'NIL';
        }

        file_put_contents(base_path('assets/files/display'), json_encode($data));
    }
}
