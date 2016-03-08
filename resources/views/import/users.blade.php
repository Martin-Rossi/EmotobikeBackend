@extends( 'layouts.import' )

@section( 'content' )
	<div class="title_left">
    	<h3>Import Users</h3>

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
                  		
                  		<form id="users-upload-form" action="{{ url( '/import/users' ) }}" method="post" data-parsley-validate="" class="form-horizontal form-label-left" enctype="multipart/form-data">
                    		<div class="form-group">
                      			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="csv_file">CSV File <span class="required">*</span></label>
                      			
                      			<div class="col-md-6 col-sm-6 col-xs-12">
                        			<input type="file" id="users-file" name="csv_file" required="required" class="form-control col-md-7 col-xs-12">
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
                        <h2>Imported users</h2>

                        <div class="clearfix"></div>
                    </div>

                    <div class="x_content">
                        <div id="example_wrapper" class="dataTables_wrapper" role="grid">
                            <div class="DTTT_container">
                                <a href="{{ url( '/export/users' ) }}" class="DTTT_button DTTT_button_print" id="ToolTables_example_4" title="View print view">
                                    <span>Download</span>
                                </a>
                            </div>
                        </div>

                        <div class="clear"></div>


                        <table id="users-table" class="table table-striped responsive-utilities jambo_table dataTable" aria-describedby="Imported users">
                            <thead>
                                <tr class="headings" role="row">
                                    <th>ID</th>
                                    <th>Parent ID</th>
                                    <th>Group ID</th>
                                    <th>Tags</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Profile Name</th>
                                    <th>Chat</th>
                                    <th>Noteworthy</th>
                                </tr>
                            </thead>

                            <tbody role="alert" aria-live="polite" aria-relevant="all">
                                @if ( ! sizeof( $users ) > 0 )
                                    <tr>
                                        <td colspan="9">No users imported yet</td>
                                    </tr>
                                @else
                                    @foreach ( $users as $user )
                                        <tr class="pointer odd" onclick="new PNotify( {
                                                                            title: '{{ $user->name }}',
                                                                            text: ' \
                                                                                <b>ID:</b> {{ $user->id }} <br />\
                                                                                <b>Parent ID:</b> {{ $user->parent_id }} <br />\
                                                                                <b>Group ID:</b> {{ $user->group_id }} <br />\
                                                                                <b>Tags:</b> {{ $user->tags }} <br />\
                                                                                <b>Name:</b> {{ $user->name }} <br />\
                                                                                <b>Email:</b> {{ $user->email }} <br />\
                                                                                <b>Password:</b> ****** <br />\
                                                                                <b>Image:</b> {{ $user->image }} <br />\
                                                                                <b>Profile Name:</b> {{ $user->profile_name }} <br />\
                                                                                <b>Profile Description:</b> {{ substr( (string) $user->profile_description, 0, 100 ) . ' ...' }} <br />\
                                                                                <b>Chat:</b> {{ $user->chat }} <br />\
                                                                                <b>Noteworthy:</b> {{ $user->noteworthy }} <br />\
                                                                            ',
                                                                            type: 'dark',
                                                                            hide: false
                                                                        } );">
                                            <td>{{ $user->id }}</td>
                                            <td>{{ $user->parent_id }}</td>
                                            <td>{{ $user->group_id }}</td>
                                            <td>{{ $user->tags }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->profile_name }}</td>
                                            <td>{{ $user->chat }}</td>
                                            <td>{{ $user->noteworthy }}</td>
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
    @if ( sizeof( $users ) > 0 )
        <script type="text/javascript">
            $( document ).ready( function () {
                var oTable = $( '#users-table' ).dataTable();
            } );
        </script>
    @endif
@endsection