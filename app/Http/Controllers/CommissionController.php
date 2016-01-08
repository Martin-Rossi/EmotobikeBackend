<?php

namespace App\Http\Controllers;

use App\Commission;
use App\User;
use App\Catalog;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Extensions\APIResponse;

class CommissionController extends Controller {

    protected $pp = 10;

    public function __construct( Request $request ) {
        if ( $request->get( 'pp' ) )
            $this->pp = intval( $request->get( 'pp' ) );

        if ( auth()->user()->group_id > 2 )
            abort( 403 );
    }

    public function index( ApiResponse $response ) {
        $commissions = Commission::orderBy( 'created_at', 'DESC' )
                                 ->paginate( $this->pp );

        return $response->result( $commissions->toArray() );
    }

    public function show( $id, ApiResponse $response ) {
        $commission = Commission::where( 'id', '=', $id )
                                ->with( 'user' )
                                ->with( 'catalog' )
                                ->first();

        if ( is_null( $commission ) )
            abort( 404 );

        return $response->result( $commission );
    }

    public function store( Request $request, ApiResponse $response ) {
        $user = User::find( $request->get( 'user_id' ) );

        if ( is_null( $user ) )
            return $response->error( 'User not found' );

        $catalog = Catalog::find( $request->get( 'catalog_id' ) );

        if ( is_null( $catalog ) )
            return $response->error( 'Catalog not found' );

        $uc = doubleval( $user->commission_rate ) * doubleval( $request->get( 'product_sales' ) );
        $ucs = doubleval( $user->commissions ) + $uc;

        $commission = [
            'user_id'               => $user->id,
            'catalog_id'            => $catalog->id,
            'commission'            => $uc,
            'commission_accrued'    => $ucs,
            'commission_rate'       => doubleval( $user->commission_rate ),
            'product_sales'         => doubleval( $request->get( 'product_sales' ) )
        ];

        try {
            Commission::create( $commission );
        } catch ( Exception $e ) {
            return $response->error( $e->getMessage() );
        }

        $user->commissions = $ucs;
        $user->save();

        $catalog->total_transaction = $catalog->total_transaction + doubleval( $request->get( 'product_sales' ) );
        $catalog->total_commission = $catalog->total_commission + $uc;
        $catalog->save();

        return $response->success( 'Commission added successfully' );
    }

    public function filter( Request $request, ApiResponse $response ) {
        $commissions = [];

        $operators = [
            '=',
            '<',
            '>'
        ];

        $operator = $request->get( 'operator' );

        if ( ! in_array( $operator, $operators ) )
            return $response->result( $objects );

        $filters = [
            'user_id',
            'catalog_id',
            'created_at'
        ];

        $filter = $request->get( 'filter' );

        if ( ! in_array( $filter, $filters ) )
            return $response->result( $objects );

        $commissions = Commission::where( $filter, $operator, $request->get( 'value' ) )
                                 ->with( 'user', 'catalog' )
                                 ->paginate( $this->pp );

        return $response->result( $commissions->toArray() );
    }
    
}
