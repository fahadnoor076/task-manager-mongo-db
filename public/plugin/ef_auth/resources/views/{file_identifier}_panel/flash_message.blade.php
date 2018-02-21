@if(Session::has(config('pl_{wildcard_identifier}.SESS_KEY').'success_msg'))
<div class="alert alert-success alert-dismissable">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
  <p><strong>Success : </strong>{!!  Session::get(config('pl_{wildcard_identifier}.SESS_KEY').'success_msg') !!}</p>
</div>
{{--*/ Session::forget(config('pl_{wildcard_identifier}.SESS_KEY').'success_msg') /*--}} 
@endif
@if(Session::has(config('pl_{wildcard_identifier}.SESS_KEY').'error_msg'))
<div class="alert alert-danger alert-dismissable">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
  <p><strong>Error : </strong>{!!  Session::get(config('pl_{wildcard_identifier}.SESS_KEY').'error_msg') !!}</p>
</div>
{{--*/ Session::forget(config('pl_{wildcard_identifier}.SESS_KEY').'error_msg') /*--}} 
@endif
@if(Session::has(config('pl_{wildcard_identifier}.SESS_KEY').'info_msg'))
<div class="alert alert-info alert-dismissable">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
  <p><strong>Info : </strong>{!!  Session::get(config('pl_{wildcard_identifier}.SESS_KEY').'info_msg') !!}</p>
</div>
{{--*/ Session::forget(config('pl_{wildcard_identifier}.SESS_KEY').'info_msg') /*--}} 
@endif