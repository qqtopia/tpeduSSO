@extends('layouts.dashboard')

@section('page_heading')
班級管理
@endsection

@section('section')
<div class="container">
	<div class="row">
	@if (session('error'))
	    <div class="alert alert-danger">
		{{ session('error') }}
	    </div>
	@endif
	@if (session('success'))
	    <div class="alert alert-success">
		{{ session('success') }}
	    </div>
	@endif
	<div class="col-sm-6">
		<div class="panel panel-default">	  
		<div class="panel-heading">
			<h4>
				<select id="grade" name="grade" onchange="location='{{ url()->current() }}?grade=' + $(this).val();">
				@foreach ($grades as $grade)
			    	<option value="{{ $grade }}" {{ $my_grade == $grade ? 'selected' : '' }}>{{ $grade }}年級</option>
			    @endforeach
				</select>
				班級一覽表
			</h4>
		</div>
		<div class="panel-body">
			<table class="table table-hover">
				<thead>
					<tr>
						<th>班級代號</th>
						<th>班級名稱</th>
						<th>導師</th>
						<th>管理</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($classes as $class)
					<tr>
						<form role="form" method="POST" action="{{ route('school.updateClass', [ 'dc' => $dc, 'ou' => $class->ou ]) }}">
		    			@csrf
						<td style="vertical-align: inherit;">
							<label>{{ $class->ou }}</label>
						</td>
						<td>
							<input id="description" type="text" style="width:100px" class="form-control" name="description" value="{{ $class->description ? $class->description : old('description') }}">
						</td>
						<td>
							<select class="form-control" style="width:auto;display:inline" id="{{ $class->ou }}ou" name="{{ $class->ou }}ou" onchange="refresh_teachers('{{ $class->ou }}ou','{{ $class->ou }}teacher');">
							@foreach ($ous as $ou)
								<option value="{{ $ou->ou }}"{{ $my_ou == $ou->ou ? ' selected' : '' }}>{{ $ou->description }}</option>
							@endforeach
							</select><select class="form-control" style="width:auto;display:inline" id="{{ $class->ou }}teacher" name="{{ $class->ou }}teacher">
								<option></option>
							@if ($teachers)
							@foreach ($teachers as $teacher)
								<option value="{{ $teacher['cn'] }}"{{ isset($class->teacher) && $teacher['cn'] == $class->teacher ? ' selected' : '' }}>{{ $teacher['displayName'] }}</option>
							@endforeach
							@endif
							</select>
						</td>
						<td>
							<button type="submit" class="btn btn-primary">修改</button>
							<button type="button" class="btn btn-danger"
							 	onclick="$('#remove-form').attr('action','{{ route('school.removeClass', [ 'dc' => $dc, 'ou' => $class->ou ]) }}');
										 $('#remove-form').submit();">刪除</button>
						</td>
						</form>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
		</div>
		<form id="remove-form" action="" method="POST" style="display: none;">
			@csrf
		</form>			
	</div>
	<div class="col-sm-6">
		<div class="panel panel-default">
		<div class="panel-heading">
			<h4>新增班級</h4>
		</div>
		<div class="panel-body">
			<form role="form" method="POST" action="{{ route('school.class', [ 'dc' => $dc ]) }}">
		    	@csrf
			    <div class="form-group{{ $errors->has('new-ou') ? ' has-error' : '' }}">
					<label>班級代號</label>
					<input id="new-ou" type="text" pattern="[0-9]{3,5}" class="form-control" name="new-ou" value="{{ $errors->has('new-ou') ? old('new-ou') : '' }}" placeholder="請輸入 3 到 5 位數字" required>
					@if ($errors->has('new-ou'))
						<p class="help-block">
							<strong>{{ $errors->first('new-ou') }}</strong>
						</p>
					@endif
				</div>
			    <div class="form-group{{ $errors->has('new-desc') ? ' has-error' : '' }}">
					<label>班級名稱</label>
					<input id="new-desc" type="text" class="form-control" name="new-desc" value="{{ $errors->has('new-desc') ? old('new-desc') : '' }}" required>
					@if ($errors->has('new-desc'))
						<p class="help-block">
							<strong>{{ $errors->first('new-desc') }}</strong>
						</p>
					@endif
				</div>
				@if (empty($ous))
					因為尚未設定教師職稱，無法編排導師，因此您無法新增班級！
				@else
			    <div class="form-group{{ $errors->has('new-desc') ? ' has-error' : '' }}">
					<label>導師</label>
					<select class="form-control" id="new-ou" name="new-ou" onchange="refresh_teachers('new-ou','new-teacher');">
						@foreach ($ous as $ou)
							<option value="{{ $ou->ou }}"{{ $my_ou == $ou->ou ? ' selected' : '' }}>{{ $ou->description }}</option>
						@endforeach
						</select>
						<select class="form-control" id="new-teacher" name="new-teacher">
							<option></option>
						@if ($teachers)
						@foreach ($teachers as $teacher)
							<option value="{{ $teacher['cn'] }}">{{ $teacher['displayName'] }}</option>
						@endforeach
						@endif
					</select>
				</div>
				<div class="form-group">
					<button type="submit" class="btn btn-success">新增</button>
				</div>
				@endif
			</form>
		</div>
		</div>
	</div>
	</div>
	<script type="text/javascript">
		function refresh_teachers(ou_select, teacher_select) {
			axios.get('/school/{{ $dc }}/teachers/' + $('#' + ou_select).val())
    			.then(response => {
    				$('#' + teacher_select).find('option').remove();
					$('#' + teacher_select).append('<option></option>');
					response.data.forEach(
    					function add_options(teacher) {
    						$('#' + teacher_select).append('<option value="' + teacher.idno + '">' + teacher.name + '</option>');
    					}
        			);
				})
				.catch(function (error) {
					console.log(error);
  				});
      	}
	</script>
</div>
@endsection
