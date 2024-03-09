<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\HolidayPlanResource;
use App\Models\HolidayPlan;
use App\Traits\HttpResponses;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HolidayPlanController extends Controller
{
    use HttpResponses;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return HolidayPlanResource::collection( HolidayPlan::all() );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make( $request->all(),
            [
                'title'         => 'required|max:100',
                'description'   => 'required|max:300',
                'date'          => 'required|date_format:Y-m-d',
                'location'      => 'required|max:100',
                'participants'  => 'required|nullable|max:100'
            ] );

        if ( $validator->fails() ){
            return $this->error('Data inválid!', 422, $validator->errors());
        }

        $created = HolidayPlan::create($validator->validated());

        if (!$created){
            return $this->error( 'Something is wrong!', 400 );
        }

        return $this->response('Holiday Plan created!', 200, new HolidayPlanResource( $created ));

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return new HolidayPlanResource( HolidayPlan::where( 'id', $id)->first() );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make( $request->all(), [
            'title'         => 'required|max:100',
            'description'   => 'required|max:300',
            'date'          => 'required|date_format:Y-m-d',
            'location'      => 'required|max:100',
            'participants'  => 'required|nullable|max:100'
        ]);

        if ( $validator->fails() ){
            return $this->error('Data inválid!', 422, $validator->errors());
        }

        $validated = $validator->validated();

        $createdArray = [

            'title'         => $validated['title'],
            'description'   => $validated['description'],
            'date'          => $validated['date'],
            'location'      => $validated['location'],
            'participants'  => $validated['participants']

        ];

        $updated = HolidayPlan::find($id)->update($createdArray);

        if (!$updated){
            return $this->error( 'Something is wrong!', 400 );
        }

        return $this->response('Holiday Plan updated!', 200, $validated);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $deleted = HolidayPlan::destroy( $id );

        if (!$deleted){
            return $this->error('Holiday Plan not deleted!', 400);
        }

        return $this->response('Holiday Plan deleted!', 200);
    }

    /**
     * Download the specified holiday plan PDF.
     */
    public function download(Request $request, string $id)
    {
        $holidayPlan = new HolidayPlanResource( HolidayPlan::where( 'id', $id)->first() );

        $pdf = PDF::loadView('PDF.pdf-generate', ['holidayPlan' => $holidayPlan])->setPaper('a4', 'portrait');

        return $pdf->download($holidayPlan['title']);
    }
}
