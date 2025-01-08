$(document).ready(function(){

  $('#diseaseWiseModal').DataTable();

    $('#queryHeader').text("Farmer Query Information");

    options = {
        chart: {
          type: 'bar',
          height: 300
        },
        plotOptions: {
          bar: {
            horizontal: true
          }
        },
        series: [{
          data: [{
            x: 'Queries Received',
            y: 560
          }, {
            x: 'Queries Moderated',
            y: 435
          }, {
            x: 'Queries Answered',
            y: 300
          }]
        }],
        title: {
            text: "Farmer Queries"
        }
      }

    var chartFarmersQryInfo = new ApexCharts(document.querySelector("#queryChart"),options);
    chartFarmersQryInfo.render();


    var optionsPiChart = {
        series: [80,30,90],
        chart: {
        width: '360',
        type: 'donut',
      },
      labels: ["General","Schemes", "FAQ"],
      dataLabels: {
        enabled: false
      },
      responsive: [{
        breakpoint: 480,
        options: {
          chart: {
            width: 200
          },
          legend: {
            show: false
          }
        }
      }],
      theme: {
        monochrome: {
          enabled: true
        }
      },
      // plotOptions: {
      //   pie: {
      //     dataLabels: {
      //       offset: -5
      //     }
      //   }
      // },
      title: {
        text: "Agri News Pie"
      },
      // dataLabels: {
      //   formatter(val, opts) {
      //     const name = opts.w.globals.labels[opts.seriesIndex]
      //     return [name, val.toFixed(1) + '%']
      //   }
      // },
      legend: {
        position: 'right',
        offsetY: 0,
        height: 230,
      }
    };

    var chartAgriNews = new ApexCharts(document.querySelector("#newsChart"), optionsPiChart);
    chartAgriNews.render();
    
    // createEmptyChart('line');

    // function createEmptyChart(chartType)
    // {
    //     var options = {
    //         chart: {
    //             height: 350,
    //             type: chartType,
    //         },
    //         dataLabels: {
    //             enabled: false
    //         },
    //         series: [],
    //         title: {
    //             text: 'Ajax Example',
    //         },
    //         noData: {
    //         text: 'Loading...'
    //         }
    //     }
        
    //     var chart = new ApexCharts(
    //         document.querySelector("#queryChart"),
    //         options
    //     );
    //     chart.render();
    // }
    
    $.ajax(
      {
        url: "http://43.205.45.246:81/adminjson", 
        success: function(response)
        {
          chartFarmersQryInfo.updateSeries([{
                   name: 'Queries',
                    data: response
                }]);
        }
      });


    // $.getJSON("http://43.205.45.246:81/adminjson", function(response) {
    //   chartFarmersQryInfo.updateSeries([{
    //         name: 'Queries',
    //         data: response
    //     }]);
    // });


    $.getJSON("http://43.205.45.246:81/adminjsonAgriNews", function(response) {
      var arr = chartAgriNews.w.globals.series.slice()
      arr[0]= response[0];
      arr[1] = response[1];
      arr[2]= response[2];
      chartAgriNews.updateSeries(arr);
    });


    // $('#tableID').DataTable({
    //   "ajax": 'DataSetforDashboard_countDiseaseWise.txt'
    // });
    



    $("#tblDiseaseWise").on("click", ".OpenDiseaseWiseModalBtn", function(){

      $('#diseaseWiseModal').on('shown.bs.modal', function (e) {
        // $('#editState').focus();
      }).modal('show');
    });


    $("#tblDistrictWise").on("click", ".OpenDistrictWiseModalBtn", function(){

      $('#districtWiseModal').on('shown.bs.modal', function (e) {
        // $('#editState').focus();
      }).modal('show');
    });
});
