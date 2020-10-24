@extends('layout.mainlayout')
@section('content')
<div class="container-fluid" style="margin-bottom: 30px">
  <div class="page-header">
    <h1>Company Rev Details</h1>
  </div>

    <div class="row">
      <div class="col-md-12">
        <div class="col-xs-3">
          <label>Client Tier :</label>
          <br>
          <input type="text" class="form-control" value="{{ $company->ClientTier }}" readonly>
        </div>
        <div class="col-xs-3">
          <label>Comercial Stream :</label>
          <br>
          <input type="text" class="form-control" value="{{ $company->GCPStream }}" readonly>
        </div>
        <div class="col-xs-3">
          <label>Commercial Bussiness :</label>
          <br>
          <input type="text" class="form-control" value="{{ $company->GCPBusiness }}" readonly>
        </div>
        <div class="col-xs-3">
          <label>Bussiness Category :</label>
          <br>
          <input type="text" class="form-control" value="{{ $company->CMGGlobalBU }}" readonly>
        </div>

      </div>
    </div>

    <br>

    <div class="row">
      <div class="col-md-12">
        <div class="col-xs-3">
          <label>Bussiness Segment :</label>
          <br>
          <input type="text" class="form-control" value="{{ $company->CMGSegmentName }}" readonly>
        </div>
        <div class="col-xs-3">
          <label>Country :</label>
          <br>
          <input type="text" class="form-control" value="{{ $company->GlobalControlPoint }}" readonly>
        </div>

        <div class="col-xs-3">
          <label>World Region :</label>
          <br>
          <input type="text" class="form-control" value="{{ $company->GCPGeography }}" readonly>
        </div>

        <div class="col-xs-3">
          <label>Manager In Contact :</label>
          <br>
          <input type="text" class="form-control" value="{{ $company->GlobalRelationshipManagerName }}" readonly>
        </div>
      </div>

    </div>

    <br>

    <div class="row">
      <div class="col-md-12">
        <div class="col-xs-3">
          <canvas id="roe_chart" width="400" height="400"></canvas>
        </div>
        <div class="col-xs-3">
          <canvas id="revenue_chart" width="400" height="400"></canvas>
        </div>
        <div class="col-xs-3">
          <canvas id="eop_chart" width="400" height="400"></canvas>
        </div>
        <div class="col-xs-3">
          <canvas id="act_chart" width="400" height="400"></canvas>
        </div>
      </div>
    </div>


  <br>


  <div class="panel panel-primary">
    <div class="panel-heading">Edit Form</div>
    <div class="panel-body">

      <form action="{{URL::route('updateCompany')}}" method="POST" >
      {{csrf_field()}}
      <div class="row">
        <div class="col-md-12">
          <div class="col-xs-3">
            <input type="hidden" class="form-control" name="offset" value="{{ $company->offset }}" >

            <label>ROE_FY14 :</label>
            <br>
            <input type="text" class="form-control" name="ROE_FY14" value="{{ $company->ROE_FY14 }}" >
          </div>
          <div class="col-xs-3">
            <label>ROE_FY15 :</label>
            <br>
            <input type="text" class="form-control" name="ROE_FY15" value="{{ $company->ROE_FY15 }}" >
          </div>
          <div class="col-xs-3">
            <label>REVENUE_FY14 :</label>
            <br>
            <input type="text" class="form-control" name="REVENUE_FY14" value="{{ $company->REVENUE_FY14 }}" >
          </div>
          <div class="col-xs-3">
            <label>REVENUE_FY14 :</label>
            <br>
            <input type="text" class="form-control" name="REVENUE_FY15" value="{{ $company->REVENUE_FY15 }}" >
          </div>

        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <div class="col-xs-3">
            <label>RWA FY14 :</label>
            <br>
            <input type="text" class="form-control" name="REV_RWA_FY14" value="{{ $company['REV/RWA FY14'] }}" >
          </div>
          <div class="col-xs-3">
            <label>RWA FY15 :</label>
            <br>
            <input type="text" class="form-control" name="REV_RWA_FY15" value="{{ $company['REV/RWA FY15'] }}" >
          </div>
          <div class="col-xs-3">
            <label>TotalLimits_EOP_FY14 :</label>
            <br>
            <input type="text" class="form-control" name="TotalLimits_EOP_FY14" value="{{ $company[' TotalLimits_EOP_FY14'] }}" >
          </div>
          <div class="col-xs-3">
            <label>TotalLimits_EOP_FY15 :</label>
            <br>
            <input type="text" class="form-control" name="TotalLimits_EOP_FY15" value="{{ $company[' TotalLimits_EOP_FY15'] }}" >
          </div>

        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <div class="col-xs-3">
            <label>Company_Avg_Activity_FY14 :</label>
            <br>
            <input type="text" class="form-control" name="Company_Avg_Activity_FY14" value="{{ $company['Company_Avg_Activity_FY14'] }}" >
          </div>
          <div class="col-xs-3">
            <label>Company_Avg_Activity_FY15:</label>
            <br>
            <input type="text" class="form-control" name="Company_Avg_Activity_FY15" value="{{ $company['Company_Avg_Activity_FY15'] }}" >
          </div>

        </div>
      </div>
    </div>
    <div class="panel-footer">
      <button type="submit" class="btn btn-danger">Re-Submit</button>
    </div>

    </form>
  </div>




</div>
@endsection

@section('javascript')
    <script src="{{ URL::to('/engine/chartjs/dist/Chart.js') }}"></script>
    <script>
      var ctx = document.getElementById('roe_chart');
      let ROE_FY14 = {{str_replace(["-","%"],"",$company->ROE_FY14)}};
      let ROE_FY15 = {{str_replace(["-","%"],"",$company->ROE_FY15)}};

      var myChart = new Chart(ctx, {
        type: 'pie',
        data: {
          labels: ['ROE FY14', 'ROE FY15',],
          datasets: [{
            data: [ROE_FY14, ROE_FY15],
            backgroundColor: [
              'rgba(0, 99, 132, 0.6)',
              'rgba(99, 132, 0, 0.6)'
            ],
            borderWidth: 1
          }]
        },
        options: {
          tooltips: {
            enabled: true,
            callbacks: {
              label: function(tooltipItem, data) {
                var label = data.labels[tooltipItem.index];
                var val = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                return label + ':-' + val + '% (' + (100 * val / 130).toFixed(2) + '%)';
              }
            }
          },
          title: {
            display: true,
            position: 'top',
            fontSize: 20,
            text: 'ROE FY14 vs FY15'
          }
        }
      });
    </script>

    <script>
      var ctx = document.getElementById('revenue_chart');

      let REV_FY14 ={{str_replace([","],"",str_replace(["-","%",""],"",$company['REVENUE_FY14']))}};
      let REV_RWA_FY14 = {{str_replace(["-","%"],"",$company['REV/RWA FY14'])}};
      let REV_FY15 = {{str_replace([","],"",str_replace(["-","%",""],"",$company['REVENUE_FY15']))}};
      let REV_RWA_FY15 = {{str_replace(["-","%"],"",$company['REV/RWA FY15'])}};

      var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: ['REVENUE_FY14', 'REV/RWA FY14','REVENUE_FY15', 'REV/RWA FY15'],
          datasets: [{
            data: [REV_FY14, REV_RWA_FY14, REV_FY15, REV_RWA_FY15],
            backgroundColor: [
              'rgba(255,0,54,0.2)',
              'rgba(222,58,82,0.2)',
              'rgba(50,255,0,0.2)',
              'rgba(78,210,95,0.2)'

            ],
            borderColor: [
              'rgb(255,0,54)',
              'rgb(222,58,82)',
              'rgb(50,255,0)',
              'rgb(78,210,95)'
            ],
            borderWidth: 1
          }]
        },
        options: {
          legend:{
            display: false
          },
          tooltips: {
            enabled: true,
            callbacks: {
              label: function(tooltipItem, data) {
                var label = data.labels[tooltipItem.index];
                var val = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                return label + ':' + val + '(' + (100 * val / 130).toFixed(2) + '%)';
              }
            }
          },
          title: {
            display: true,
            position: 'top',
            fontSize: 20,
            text: 'Revenue & RWA FY14 vs FY15'
          }
        }
      });
    </script>

    <script>
      var ctx = document.getElementById('eop_chart');

      let TL_EOP_FY14 = {{str_replace([","],"",str_replace(["-","%"," "],"0",$company[' TotalLimits_EOP_FY14']))}};
      let TL_EOP_FY15 = {{str_replace([","],"",str_replace(["-","%"," "],"0",$company[' TotalLimits_EOP_FY15']))}};

      var myChart = new Chart(ctx, {
        type: 'line',
        data: {
          labels: ['TotalLimits_EOP_FY14', 'TotalLimits_EOP_FY15'],
          datasets: [{
            data: [TL_EOP_FY14, TL_EOP_FY15],
            borderColor: [
              'rgba(0, 99, 132, 0.6)',
              'rgba(99, 132, 0, 0.6)',
            ],
            borderWidth: 1
          }]
        },
        options: {
          tooltips: {
            enabled: true,
            callbacks: {
              label: function(tooltipItem, data) {
                var label = data.labels[tooltipItem.index];
                var val = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                return label + ':' + val + '(' + (100 * val / 130).toFixed(2) + '%)';
              }
            }
          },
          title: {
            display: true,
            position: 'top',
            fontSize: 20,
            text: 'Total Limit EOP FY14 vs FY15'
          }
        }
      });
    </script>

    <script>
      var ctx = document.getElementById('act_chart');

      let Company_Avg_Activity_FY14 = {{str_replace([","],"",str_replace(["-","%"," "],"",$company['Company_Avg_Activity_FY14']))}};
      let Company_Avg_Activity_FY15 = {{str_replace([","],"",str_replace(["-","%"," "],"",$company['Company_Avg_Activity_FY15']))}};

      let NPAT_AllocEq_FY14 = {{str_replace([","],"",str_replace(["-","%"," "],"",$company[' NPAT_AllocEq_FY14']))}};
      let NPAT_AllocEq_FY15 = {{str_replace([","],"",str_replace(["-","%"," "],"",$company[' NPAT_AllocEq_FY15X']))}};

      let Deposits_EOP_FY14 = {{str_replace([","],"",str_replace(["-","%"," "],"0",$company[' Deposits_EOP_FY14']))}};
      let Deposits_EOP_FY15 = {{str_replace([","],"",str_replace(["-","%"," "],"0",$company[' Deposits_EOP_FY15x']))}};

      var fiscalDataFY14 = {
        label: 'FY14',
        data: [Company_Avg_Activity_FY14, NPAT_AllocEq_FY14, TL_EOP_FY14, Deposits_EOP_FY14],
        backgroundColor: 'rgba(0, 99, 132, 0.6)',
        borderWidth: 0
      };

      var fiscalDataFY15= {
        label: 'FY14',
        data: [Company_Avg_Activity_FY15, NPAT_AllocEq_FY15, TL_EOP_FY15, Deposits_EOP_FY15],
        backgroundColor: 'rgba(99, 132, 0, 0.6)',
        borderWidth: 0
      };

      var myChart = new Chart(ctx, {
        type: 'horizontalBar',
        data: {
          labels: ['Avg Regulatory Capital', 'NP AT Allocation','TotalLimits EOP','Deposits EOP'],
          datasets: [
            fiscalDataFY14, fiscalDataFY15
          ]
        },
        options: {
          tooltips: {
            enabled: true,
            callbacks: {
              label: function(tooltipItem, data) {
                var label = data.labels[tooltipItem.index];
                var val = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                return label + ':' + val + '(' + (100 * val / 130).toFixed(2) + '%)';
              }
            }
          },
          title: {
            display: true,
            position: 'top',
            fontSize: 20,
            text: 'Company Average Activity FY14 vs FY15'
          }
        }
      });
    </script>
@endsection