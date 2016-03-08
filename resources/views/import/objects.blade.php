@extends( 'layouts.import' )

@section( 'content' )
	<div class="title_left">
    	<h3>Import Objects</h3>

        @if ( session( 'error' ) )
            <div class="alert alert-danger alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                {{ session( 'error' ) }}
            </div>
        @endif

        @if ( session( 'info' ) )
            <div class="alert alert-info alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                {{ session( 'info' ) }}
            </div>
        @endif

    	<div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
             	<div class="x_panel">
                	<div class="x_title">
                  		<h2>Upload form</h2>
                  		
                  		<div class="clearfix"></div>
                	</div>
                
                	<div class="x_content">
                  		<br>
                  		
                  		<form id="objects-upload-form" action="{{ url( '/import/objects' ) }}" method="post" data-parsley-validate="" class="form-horizontal form-label-left" enctype="multipart/form-data">
                    		<div class="form-group">
                      			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="csv_file">CSV File <span class="required">*</span></label>
                      			
                      			<div class="col-md-6 col-sm-6 col-xs-12">
                        			<input type="file" id="objects-file" name="csv_file" required="required" class="form-control col-md-7 col-xs-12">
                      			</div>
                    		</div>

                    		<div class="form-group">
                    			<label class="control-label col-md-3 col-sm-3 col-xs-12">&nbsp;</label>

                    			<div class="col-md-6 col-sm-6 col-xs-12">
                    				<button type="submit" class="btn btn-success">Submit</button>
                    			</div>
                    		</div>
                  		</form>
                	</div>
              	</div>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Imported objects</h2>

                        <div class="clearfix"></div>
                    </div>

                    <div class="x_content">
                        <div id="example_wrapper" class="dataTables_wrapper" role="grid">
                            <div class="DTTT_container">
                                <a href="{{ url( '/export/objects' ) }}" class="DTTT_button DTTT_button_print" id="ToolTables_example_4" title="View print view">
                                    <span>Download</span>
                                </a>
                            </div>
                        </div>

                        <div class="clear"></div>


                        <table id="objects-table" class="table table-striped responsive-utilities jambo_table dataTable" aria-describedby="Imported objects">
                            <thead>
                                <tr class="headings" role="row">
                                    <th>ID</th>
                                    <th>Catalog ID</th>
                                    <th>Category ID</th>
                                    <th>Type ID</th>
                                    <th>Tags</th>
                                    <th>Name</th>
                                    <th>SKU</th>
                                    <th>Retail Price</th>
                                    <th>Sale Price</th>
                                    <th>Competitor Flag</th>
                                    <th>Curated</th>
                                    <th>Author</th>
                                </tr>
                            </thead>

                            <tbody role="alert" aria-live="polite" aria-relevant="all">
                                @if ( ! sizeof( $objects ) > 0 )
                                    <tr>
                                        <td colspan="9">No objects imported yet</td>
                                    </tr>
                                @else
                                    @foreach ( $objects as $object )
                                        <tr class="pointer odd" onclick="new PNotify( {
                                                                            title: '{{ $object->name }}',
                                                                            text: ' \
                                                                                <b>ID:</b> {{ $object->id }} <br />\
                                                                                <b>Catalog ID:</b> {{ $object->catalog_id }} <br />\
                                                                                <b>Category ID:</b> {{ $object->category_id }} <br />\
                                                                                <b>Type ID:</b> {{ $object->type_id }} <br />\
                                                                                <b>Tags:</b> {{ $object->tags }} <br />\
                                                                                <b>Name:</b> {{ $object->name }} <br />\
                                                                                <b>SKU:</b> {{ $object->sku }} <br />\
                                                                                <b>URL:</b> {{ $object->url }} <br />\
                                                                                <b>Image URL:</b> {{ $object->image }} <br />\
                                                                                <b>Weight:</b> {{ $object->weight }} <br />\
                                                                                <b>Description:</b> {{ substr( (string) $object->description, 0, 100 ) . ' ...' }} <br />\
                                                                                <b>Retail Price:</b> {{ $object->retail_price }} <br />\
                                                                                <b>Sale Price:</b> {{ $object->sale_price }} <br />\
                                                                                <b>Offer Value:</b> {{ $object->offer_value }} <br />\
                                                                                <b>Offer URL:</b> {{ $object->offer_url }} <br />\
                                                                                <b>Offer Description:</b> {{ substr( (string) $object->offer_description, 0, 100 ) . ' ...' }} <br />\
                                                                                <b>Offer Start:</b> {{ $object->offer_start }} <br />\
                                                                                <b>Offer Stop:</b> {{ $object->offer_stop }} <br />\
                                                                                <b>Product Details URL:</b> {{ $object->prod_detail_url }} <br />\
                                                                                <b>Competitor Flag:</b> {{ $object->competitor_flag }} <br />\
                                                                                <b>Curated:</b> {{ $object->curated }} <br />\
                                                                                <b>Author:</b> {{ $object->author }} <br />\
                                                                            ',
                                                                            type: 'dark',
                                                                            hide: false
                                                                        } );">
                                            <td>{{ $object->id }}</td>
                                            <td>{{ $object->catalog_id }}</td>
                                            <td>{{ $object->category_id }}</td>
                                            <td>{{ $object->type_id }}</td>
                                            <td>{{ $object->tags }}</td>
                                            <td>{{ $object->name }}</td>
                                            <td>{{ $object->sku }}</td>
                                            <td>{{ $object->retail_price }}</td>
                                            <td>{{ $object->sale_price }}</td>
                                            <td>{{ $object->competitor_flag }}</td>
                                            <td>{{ $object->curated }}</td>
                                            <td>{{ $object->author }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
@endsection

@section( 'scripts' )
    @if ( sizeof( $objects ) > 0 )
        <script type="text/javascript">
            $( document ).ready( function () {
                var oTable = $( '#objects-table' ).dataTable();
            } );
        </script>
    @endif
@endsection