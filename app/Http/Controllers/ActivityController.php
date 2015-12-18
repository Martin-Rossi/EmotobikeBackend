<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Type;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Extensions\APIResponse;

class ActivityController extends Controller {

    public function show( $id, ApiResponse $response ) {
        $activity = Activity::where( 'id', '=', $id )
                            ->with( 'catalog' )
                            ->first();

        if ( is_null( $activity ) )
            abort( 404 );

        return $response->result( $activity );
    }

    public function store( Request $request, ApiResponse $response ) {
        $inputs = $request->all();

        $inputs = $this->assignType( $inputs );

        try {
            Activity::create( $inputs );
        } catch ( Exception $e ) {
            return $response->error( $e->getMessage() );
        }

        return $response->success( 'Activity created successfully' );
    }

    public function update( $id, Request $request, ApiResponse $response ) {
        $activity = Activity::find( $id );

        if ( is_null( $activity ) )
            abort( 404 );

        $inputs = $request->all();

        $inputs = $this->assignType( $inputs );

        try {
            $activity->update( $inputs );
        } catch ( Exception $e ) {
            return $response->error( $e->getMessage() );
        }

        return $response->success( 'Activity updated successfully' );
    }

    public function search( Request $request, ApiResponse $response ) {
        $activities = Activity::where( 'name', 'LIKE', '%' . $request->get( 'term' ) . '%' )
                              ->orWhere( 'description', 'LIKE', '%' . $request->get( 'term' ) . '%' )
                              ->with( 'catalog' )
                              ->get();

        return $response->result( $activities );
    }

    public function filter( Request $request, ApiResponse $response ) {
        $activities = [];

        $operators = [
            '=',
            '<',
            '>'
        ];

        $operator = $request->get( 'operator' );

        if ( ! in_array( $operator, $operators ) )
            return $response->result( $activities );

        $filters = [
            'type_id',
            'created_at',
            'updated_at'
        ];

        $filter = $request->get( 'filter' );

        if ( ! in_array( $filter, $filters ) )
            return $response->result( $activities );

        $activities = Activity::where( $filter, $operator, $request->get( 'value' ) )
                              ->with( 'catalog' )
                              ->get();

        return $response->result( $activities );
    }

    private function assignType( $inputs ) {
        if ( isset( $inputs['type'] ) && $inputs['type'] ) {
            if ( is_numeric( $inputs['type'] ) )
                $inputs['type_id'] = $inputs['type'];
            else {
                $type = Type::where( 'name', '=', $inputs['type'] )->first();

                if ( $type )
                    $inputs['type_id'] = $type->id;
                else {
                    $type = new Type();
                    $type->name = $inputs['type'];

                    $type->save();

                    $inputs['type_id'] = $type->id;
                }
            }
        }

        return $inputs;
    }
    
}
