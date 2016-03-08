@extends( 'layouts.import' )

@section( 'content' )
	<div class="title_left">
    	<h3>Import GenericCollections</h3>

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
                  		
                  		<form id="generic-collections-upload-form" action="{{ url( '/import/generic-collections' ) }}" method="post" data-parsley-validate="" class="form-horizontal form-label-left" enctype="multipart/form-data">
                    		<div class="form-group">
                      			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="csv_file">CSV File <span class="required">*</span></label>
                      			
                      			<div class="col-md-6 col-sm-6 col-xs-12">
                        			<input type="file" id="generic-collections-file" name="csv_file" required="required" class="form-control col-md-7 col-xs-12">
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
                        <h2>Imported generic collections</h2>

                        <div class="clearfix"></div>
                    </div>

                    <div class="x_content">
                        <div id="example_wrapper" class="dataTables_wrapper" role="grid">
                            <div class="DTTT_container">
                                <a href="{{ url( '/export/generic-collections' ) }}" class="DTTT_button DTTT_button_print" id="ToolTables_example_4" title="View print view">
                                    <span>Download</span>
                                </a>
                            </div>
                        </div>

                        <div class="clear"></div>


                        <table id="generic-collections-table" class="table table-striped responsive-utilities jambo_table dataTable" aria-describedby="Imported generic collections">
                            <thead>
                                <tr class="headings" role="row">
                                    <th>ID</th>
                                    <th>Collection ID</th>
                                    <th>Foreign ID</th>
                                    <th>Foreign Type</th>
                                    <th>Name</th>
                                </tr>
                            </thead>

                            <tbody role="alert" aria-live="polite" aria-relevant="all">
                                @if ( ! sizeof( $generic_collections ) > 0 )
                                    <tr>
                                        <td colspan="9">No generic collections imported yet</td>
                                    </tr>
                                @else
                                    @foreach ( $generic_collections as $generic_collection )
                                        <tr class="pointer odd" onclick="new PNotify( {
                                                                            title: '{{ $generic_collection->name }}',
                                                                            text: ' \
                                                                                <b>ID:</b> {{ $generic_collection->id }} <br />\
                                                                                <b>Collection ID:</b> {{ $generic_collection->collection_id }} <br />\
                                                                                <b>Foreign ID:</b> {{ $generic_collection->foreign_id }} <br />\
                                                                                <b>Foreign Type:</b> {{ $generic_collection->foreign_type }} <br />\
                                                                                <b>Name:</b> {{ $generic_collection->name }} <br />\
                                                                                <b>Author:</b> {{ $generic_collection->author }} <br />\
                                                                            ',
                                                                            type: 'dark',
                                                                            hide: false
                                                                        } );">
                                            <td>{{ $generic_collection->id }}</td>
                                            <td>{{ $generic_collection->collection_id }}</td>
                                            <td>{{ $generic_collection->foreign_id }}</td>
                                            <td>{{ $generic_collection->foreign_type }}</td>
                                            <td>{{ $generic_collection->name }}</td>
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
    @if ( sizeof( $generic_collections ) > 0 )
        <script type="text/javascript">
            $( document ).ready( function () {
                var oTable = $( '#generic-collection-table' ).dataTable();
            } );
        </script>
    @endif
@endsection