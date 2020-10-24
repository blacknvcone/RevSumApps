<!-- create.blade.php -->

@extends('layout.mainlayout')
@section('content')
<div class="container">
  <div class="page-header">
    <h1>Company Revenue</h1>
  </div>

  <form method="GET" action="{{ URL::route('listCompany')}}"  class="col-12 form-inline">
    <div class="col-4 form-group">
      <label>CMGSegmentName :</label>
      <input type="text" class="form-control" placeholder="CMGSegmentName" name="CMGSegmentName" value="{{ $CMGSegmentName != null ? $CMGSegmentName :'' }}">
    </div>
    <div class="col-4 form-group">
      <label>CMGUnmaskedName :</label>
      <input type="text" class="form-control"  placeholder="CMGUnmaskedName" name="CMGUnmaskedName" value="{{ $CMGUnmaskedName != null ? $CMGUnmaskedName :'' }}">
    </div>
    <div class="col-4 form-group">
      <label>ClientTier :</label>
      <input type="text" class="form-control" placeholder="ClientTier" name="ClientTier" value="{{ $ClientTier != null ? $ClientTier :'' }}">
    </div>
    <button type="submit" class="btn btn-primary">Filter</button>
  </form>

  <br>

  <div class="row">
    <div class="col-xs-12">
      <div class="table-responsive">
        <table summary="This table shows how to create responsive tables using Bootstrap's default functionality" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>CMGUnmaskedID</th>
              <th>CMGUnmaskedName</th>
              <th>ClientTier</th>
              <th>GCPStream</th>
              <th>GCPBusiness</th>
              <th>CMGGlobalBU</th>
              <th>CMGSegmentName</th>
            </tr>
          </thead>
          <tbody>
            @foreach($companies as $company)
            <tr>
              <td><a href="{{ route('detailCompany', $company->offset ) }}">{{ $company->CMGUnmaskedID }}</a></td>
              <td>{{ $company->CMGUnmaskedName }}</td>
              <td>{{ $company->ClientTier }}</td>
              <td>{{ $company->GCPStream }}</td>
              <td>{{ $company->GCPBusiness }}</td>
              <td>{{ $company->CMGGlobalBU}}</td>
              <td>{{ $company->CMGSegmentName }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div><!--end of .table-responsive-->
    </div>
  </div>

  {{ $paginator->appends(['CMGSegmentName'=>$CMGSegmentName,'CMGUnmaskedName'=>$CMGUnmaskedName,'ClientTier'=>$ClientTier])->links() }}


</div>
@endsection