@extends( 'layouts.import' )

@section( 'content' )
	<div class="title_left">
    	<h3>Import Catalogs</h3>

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
                  		
                  		<form id="catalogs-upload-form" action="{{ url( '/import/catalogs' ) }}" method="post" data-parsley-validate="" class="form-horizontal form-label-left" enctype="multipart/form-data">
                    		<div class="form-group">
                      			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="csv_file">CSV File <span class="required">*</span></label>
                      			
                      			<div class="col-md-6 col-sm-6 col-xs-12">
                        			<input type="file" id="catalogs-file" name="csv_file" required="required" class="form-control col-md-7 col-xs-12">
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
                        <h2>Imported catalogs</h2>

                        <div class="clearfix"></div>
                    </div>

                    <div class="x_content">
                        <div id="example_wrapper" class="dataTables_wrapper" role="grid">
                            <div class="DTTT_container">
                                <a href="{{ url( '/export/catalogs' ) }}" class="DTTT_button DTTT_button_print" id="ToolTables_example_4" title="View print view">
                                    <span>Download</span>
                                </a>
                            </div>
                        </div>

                        <div class="clear"></div>


                        <table id="catalogs-table" class="table table-striped responsive-utilities jambo_table dataTable" aria-describedby="Imported catalogs">
                            <thead>
                                <tr class="headings" role="row">
                                    <th>ID</th>
                                    <th>Category ID</th>
                                    <th>Type ID</th>
                                    <th>Tags</th>
                                    <th>Name</th>
                                    <th>Title</th>
                                    <th>Publish</th>
                                    <th>Trending</th>
                                    <th>Popular</th>
                                    <th>Chat</th>
                                    <th>Author</th>
                                </tr>
                            </thead>

                            <tbody role="alert" aria-live="polite" aria-relevant="all">
                                @if ( ! sizeof( $catalogs ) > 0 )
                                    <tr>
                                        <td colspan="9">No catalogs imported yet</td>
                                    </tr>
                                @else
                                    @foreach ( $catalogs as $catalog )
                                        <tr class="pointer odd" onclick="new PNotify( {
                                                                            title: '{{ $catalog->name }}',
                                                                            text: ' \
                                                                                <b>ID:</b> {{ $catalog->id }} <br />\
                                                                                <b>Category ID:</b> {{ $catalog->category_id }} <br />\
                                                                                <b>Type ID:</b> {{ $catalog->type_id }} <br />\
                                                                                <b>Tags:</b> {{ $catalog->tags }} <br />\
                                                                                <b>Name:</b> {{ $catalog->name }} <br />\
                                                                                <b>Title:</b> {{ $catalog->title }} <br />\
                                                                                <b>Description:</b> {{ substr( (string) $catalog->description, 0, 100 ) . ' ...' }} <br />\
                                                                                <b>Image:</b> {{ $catalog->image }} <br />\
                                                                                <b>Publish:</b> {{ $catalog->publish }} <br />\
                                                                                <b>Trending:</b> {{ $catalog->trending }} <br />\
                                                                                <b>Popular:</b> {{ $catalog->popular }} <br />\
                                                                                <b>Chat:</b> {{ $catalog->chat }} <br />\
                                                                                <b>Author:</b> {{ $catalog->author }} <br />\
                                                                            ',
                                                                            type: 'dark',
                                                                            hide: false
                                                                        } );">
                                            <td>{{ $catalog->id }}</td>
                                            <td>{{ $catalog->category_id }}</td>
                                            <td>{{ $catalog->type_id }}</td>
                                            <td>{{ $catalog->tags }}</td>
                                            <td>{{ $catalog->name }}</td>
                                            <td>{{ $catalog->title }}</td>
                                            <td>{{ $catalog->publish }}</td>
                                            <td>{{ $catalog->trending }}</td>
                                            <td>{{ $catalog->popular }}</td>
                                            <td>{{ $catalog->chat }}</td>
                                            <td>{{ $catalog->author }}</td>
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
    @if ( sizeof( $catalogs ) > 0 )
        <script type="text/javascript">
            $( document ).ready( function () {
                var oTable = $( '#catalogs-table' ).dataTable();
            } );
        </script>
    @endif
@endsection