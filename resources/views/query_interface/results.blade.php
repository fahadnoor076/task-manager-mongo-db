@if($success == 1)
	@if(isset($records[0]))
    {{--*/ $columns = array_keys((array)$records[0]); /*--}}
    <div class="b_scroller">
    <table id="mydatatable" class="js-table-sections table table-hover js-dataTable-full" cellspacing="0" width="100%">
      <thead>
        <tr>
        @foreach($columns as $key=>$value)
          <th>{!! $value !!}</th>
        @endforeach
        </tr>
      </thead>
      <tbody>
      @foreach($records as $record)
        <tr>
        @for($i=0; $i<count($columns); $i++)
          <td>{!! $record->{$columns[$i]} !!}</td>
        @endfor
        </tr>
       @endforeach
      </tbody>
      <tfoot>
        <tr>
        @foreach($columns as $key=>$value)
          <th>{!! $value !!}</th>
        @endforeach
        </tr>
      </tfoot>
    </table>
    </table>
    <script type="text/javascript">
        $(function() {	
            // data grid generation
            $('#mydatatable').DataTable().destroy();
            var dg = $("#mydatatable").DataTable({
                //dom: 'Bfrtip',
				dom: 'lpiBfrtip',
				//bScrollCollapse: true,
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                processing: true,
                serverSide: false,
                searching: true,
                //bStateSave: true, // save datatable state(pagination, sort, etc) in cookie.
                pageLength: 10, // default record count per page
				drawCallback: function (settings) {
					// other functionality
					$("div.b_scroller").doubleScroll();
					$(".suwala-doubleScroll-scroll-wrapper").css("width","100%");
					$(".suwala-doubleScroll-scroll").html($(settings.nTable).clone());
					// make scrollable
					//$("div.row:nth-child(2)",$(settings.nTableWrapper)).css("overflowX","auto");
				},
            });
			// add double scrolls
			//console.log("dg",dg);
			//$($(dg.context[0]["nTableWrapper"])).wrap("<div class='b_scroller'></div>");
			//$("div.b_scroller").doubleScroll();
			//$($(dg.context[0]["nTableWrapper"])).doubleScroll();
			//$("div.row:nth-child(2)",$(dg.context[0]["nTableWrapper"])).doubleScroll();
        });
        </script> 
    @else
    <p class="text-danger text-center">no records found</p>
    @endif
@else
<p class="text-danger">{!! $message !!}</p>
@endif