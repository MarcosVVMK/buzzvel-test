<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\HolidayPlanResource;
use App\Models\HolidayPlan;
use App\Traits\HttpResponses;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations as OA;

class HolidayPlanController extends Controller
{
    use HttpResponses;


    /**
     * @OA\Get(
     *     path="/holiday-plans",
     *     tags={"Holiday Plans"},
     *     summary="Get all holiday plans",
     *     operationId="getAllHolidayPlans",
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             type="object",
     *              example={"data":{
     *                  "id": 1, "title": "Example Holiday Plan", "description": "Description for holiday plan", "date": "yyyy-mm-dd", "location": "Location of holiday plan", "participants": "name of participants",
     *              }}
     *         )
     *     ),
     *      @OA\Parameter(
     *          name="Authorization",
     *          in="header",
     *          required=true,
     *          description="Bearer token",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     * )
     */
    public function index()
    {
        return HolidayPlanResource::collection( HolidayPlan::all() );
    }

    /**
     * @OA\Post(
     *     path="/holiday-plans",
     *     tags={"Holiday Plans"},
     *     summary="Create a new holiday plan",
     *     operationId="storeHolidayPlan",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                     maxLength=100
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="string",
     *                     maxLength=300
     *                 ),
     *                 @OA\Property(
     *                     property="date",
     *                     type="string",
     *                     format="date",
     *                     example="YYYY-MM-DD"
     *                 ),
     *                 @OA\Property(
     *                     property="location",
     *                     type="string",
     *                     maxLength=100
     *                 ),
     *                 @OA\Property(
     *                     property="participants",
     *                     type="string",
     *                     maxLength=100,
     *                     nullable=true
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Holiday Plan created!",
     *         @OA\JsonContent(
     *             type="object",
     *             example={"message": "Holiday Plan created!", "data": {"id": 1, "title": "Example Holiday Plan"}}
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Something is wrong!"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Data invalid",
     *         @OA\JsonContent(
     *             type="object",
     *             example={"message": "Data invalid", "errors": {"title": {"The title field is required."}}}
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="Authorization",
     *         in="header",
     *         required=true,
     *         description="Bearer token",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     * ),
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
     * @OA\Get(
     *     path="/holiday-plans/{id}",
     *     tags={"Holiday Plans"},
     *     summary="Get a specific holiday plan",
     *     operationId="showHolidayPlan",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the holiday plan",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="Authorization",
     *         in="header",
     *         required=true,
     *         description="Bearer token",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             type="object",
     *             example={"id": 1, "title": "Example Holiday Plan", "description": "Description for holiday plan", "date": "yyyy-mm-dd", "location": "Location of holiday plan", "participants": "name of participants"}
     *         )
     *     )
     * )
     */

    public function show(string $id)
    {
        return new HolidayPlanResource( HolidayPlan::where( 'id', $id)->first() );
    }

    /**
     * @OA\Put(
     *     path="/holiday-plans/{id}",
     *     tags={"Holiday Plans"},
     *     summary="Update a holiday plan",
     *     operationId="updateHolidayPlan",
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID of the holiday plan",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="Authorization",
     *          in="header",
     *          required=true,
     *          description="Bearer token",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                     maxLength=100
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="string",
     *                     maxLength=300
     *                 ),
     *                 @OA\Property(
     *                     property="date",
     *                     type="string",
     *                     format="date",
     *                     example="YYYY-MM-DD"
     *                 ),
     *                 @OA\Property(
     *                     property="location",
     *                     type="string",
     *                     maxLength=100
     *                 ),
     *                 @OA\Property(
     *                     property="participants",
     *                     type="string",
     *                     maxLength=100,
     *                     nullable=true
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Holiday Plan updated!",
     *         @OA\JsonContent(
     *             type="object",
     *             example={"id": 1, "title": "Example Holiday Plan", "description": "Description for holiday plan", "date": "yyyy-mm-dd", "location": "Location of holiday plan", "participants": "name of participants"}
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Something is wrong!"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Data invalid",
     *         @OA\JsonContent(
     *             type="object",
     *             example={"message": "Data invalid", "errors": {"title": {"The title field is required."}}}
     *         )
     *     )
     * )
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
     * @OA\Delete(
     *     path="/holiday-plans/{id}",
     *     tags={"Holiday Plans"},
     *     summary="Delete a holiday plan",
     *     operationId="deleteHolidayPlan",
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID of the holiday plan",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="Authorization",
     *          in="header",
     *          required=true,
     *          description="Bearer token",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Holiday Plan deleted!",
     *         @OA\JsonContent(
     *             type="object",
     *             example={"message": "Holiday Plan deleted!"}
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Holiday Plan not deleted!",
     *     @OA\JsonContent(
     *              type="object",
     *              example={"message": "Holiday Plan not deleted!"}
     *          )
     *      ),
     *     )
     * )
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
     * @OA\Get(
     *     path="/holiday-plans/{id}/download",
     *     tags={"Holiday Plans"},
     *     summary="Download a holiday plan PDF",
     *     operationId="downloadHolidayPlanPdf",
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID of the holiday plan",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="Authorization",
     *          in="header",
     *          required=true,
     *          description="Bearer token",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Success"
     *     )
     * )
     */
    public function download(Request $request, string $id)
    {
        $holidayPlan = new HolidayPlanResource( HolidayPlan::where( 'id', $id)->first() );

        $pdf = PDF::loadView('PDF.pdf-generate', ['holidayPlan' => $holidayPlan])->setPaper('a4', 'portrait');

        return $pdf->download($holidayPlan['title']);
    }
}
