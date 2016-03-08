@extends( 'layouts.import' )

@section( 'content' )
	<div class="row tile_count">
		<div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count">
        	<div class="left"></div>
            
            <div class="right">
            	<span class="count_top">Users</span>
              	<div class="count">{{ $data['users'] }}</div>
            </div>
        </div>
          
        <div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count">
        	<div class="left"></div>
            
            <div class="right">
            	<span class="count_top">Objects</span>
              	<div class="count">{{ $data['objects'] }}</div>
            </div>
        </div>
        
        <div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count">
           	<div class="left"></div>
            
            <div class="right">
            	<span class="count_top">Catalogs</span>
              	<div class="count">{{ $data['catalogs'] }}</div>
            </div>
        </div>
        
        <div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count">
            <div class="left"></div>
            
            <div class="right">
              	<span class="count_top">Collections</span>
              	<div class="count">{{ $data['collections'] }}</div>
            </div>
        </div>
        
        <div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count">
            <div class="left"></div>

            <div class="right">
              	<span class="count_top">Generic Collections</span>
              	<div class="count">{{ $data['generic_collections'] }}</div>
            </div>
        </div>
        
        <div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count">
            <div class="left"></div>
            
            <div class="right">
              	<span class="count_top">Categories</span>
              	<div class="count">{{ $data['categories'] }}</div>
            </div>
       	</div>
	</div>
@endsection