@extends( 'layouts.import' )

@section( 'content' )
	<div class="title_left">
    	<h3>Import Collections</h3>

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
                  		
                  		<form id="collections-upload-form" action="{{ url( '/import/collections' ) }}" method="post" data-parsley-validate="" class="form-horizontal form-label-left" enctype="multipart/form-data">
                    		<div class="form-group">
                      			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="csv_file">CSV File <span class="required">*</span></label>
                      			
                      			<div class="col-md-6 col-sm-6 col-xs-12">
                        			<input type="file" id="collections-file" name="csv_file" required="required" class="form-control col-md-7 col-xs-12">
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
                        <h2>Imported collections</h2>

                        <div class="clearfix"></div>
                    </div>

                    <div class="x_content">
                        <div id="example_wrapper" class="dataTables_wrapper" role="grid">
                            <div class="DTTT_container">
                                <a href="{{ url( '/export/collections' ) }}" class="DTTT_button DTTT_button_print" id="ToolTables_example_4" title="View print view">
                                    <span>Download</span>
                                </a>
                            </div>
                        </div>

                        <div class="clear"></div>


                        <table id="collections-table" class="table table-striped responsive-utilities jambo_table dataTable" aria-describedby="Imported collections">
                            <thead>
                                <tr class="headings" role="row">
                                    <th>ID</th>
                                    <th>Collection ID</th>
                                    <th>Foreign ID</th>
                                    <th>Foreign Type</th>
                                    <th>Name</th>
                                    <th>Author</th>
                                </tr>
                            </thead>

                            <tbody role="alert" aria-live="polite" aria-relevant="all">
                                @if ( ! sizeof( $collections ) > 0 )
                                    <tr>
                                        <td colspan="9">No collections imported yet</td>
                                    </tr>
                                @else
                                    @foreach ( $collections as $collection )
                                        <tr class="pointer odd" onclick="new PNotify( {
                                                                            title: '{{ $collection->name }}',
                                                                            text: ' \
                                                                                <b>ID:</b> {{ $collection->id }} <br />\
                                                                                <b>Collection ID:</b> {{ $collection->collection_id }} <br />\
                                                                                <b>Foreign ID:</b> {{ $collection->foreign_id }} <br />\
                                                                                <b>Foreign Type:</b> {{ $collection->foreign_type }} <br />\
                                                                                <b>Name:</b> {{ $collection->name }} <br />\
                                                                                <b>Author:</b> {{ $collection->author }} <br />\
                                                                            ',
                                                                            type: 'dark',
                                                                            hide: false
                                                                        } );">
                                            <td>{{ $collection->id }}</td>
                                            <td>{{ $collection->collection_id }}</td>
                                            <td>{{ $collection->foreign_id }}</td>
                                            <td>{{ $collection->foreign_type }}</td>
                                            <td>{{ $collection->name }}</td>
                                            <td>{{ $collection->author }}</td>
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
    @if ( sizeof( $collections ) > 0 )
        <script type="text/javascript">
            $( document ).ready( function () {
                var oTable = $( '#collections-table' ).dataTable();
            } );
        </script>
    @endif
@endsection