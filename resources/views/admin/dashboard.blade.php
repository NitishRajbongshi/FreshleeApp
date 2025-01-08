@extends('admin.common.layout')

@section('title', 'Dashboard')

@section('custom_header')
    <meta name="X-CSRF-Token" content="{{ csrf_token() }}" />
@endsection

@section('main')
    {{-- <h1>Dashboard</h1> --}}
    {{-- {{Session::get('current_role')}} --}}
    {{-- {{!! dd(Session::pull('roles')) !!}} --}}
    @php
        $diseaseWiseCountArr = $diseaseWiseCountArr;
        $query_count_data = $query_count_data;
        $piChartData = $piChartData;
        $DataSet_count_diseaseWise_for_bar_chart = $DataSet_count_diseaseWise_for_bar_chart;
        $districtWiseAllDiseaseJsonData = $districtWiseAllDiseaseJsonData;
    @endphp
    <div class="row">
        <div class="col-lg-12 mb-2 order-0">
            <div class="card">
                <div class="col-lg-12 my-2 px-2 order-0 dashboard-marquee">
                    <marquee behavior="scroll" direction="left" scrollamount="6">
                        <label class="text-danger">Last Issue Diagnosed: {{ $lastDiagnosedDiseaseName }}, at
                            {{ $lastDiagnosedDiseaseAtlocation }}, {{ $lastDiagnosedDiseaseAtDistrict }} on
                            {{ $lastDiagnosedOn }}.</label>
                    </marquee>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="row">
        <div class="card mb-2">
            <form class="frmGenerateDataForMap" id="frmGenerateDataForMap" method="POST"
                action="{{ route('getDataForDistrictWiseDiseaseWiseForMap') }}">
                <div class="row col-md-12">
                    <div class="col-md-2 mb-1">
                        <label for="name" class="col-form-label">From Date:</label>
                        <input type="date" value="" name="frm_date" id="frm_date" class="form-control">
                        
                    </div>
                    <div class="col-md-2 mb-1">
                        <label for="name" class="col-form-label">To Date:</label>
                        <input type="date" value="" name="to_date" id="to_date" class="form-control">

                    </div>
                    <div class="col-md-2 mb-1">
                        <label for="name" class="col-form-label">District</label>
                        <select name="sel_dist_cd" id="sel_dist_cd" class="text-xs form-control">
                            <option value="A" selected>All District</option>
                            @foreach ($distMaster as $item)
                                @if ($item->state_cd == '1')
                                    <option value="{{ $item->district_cd }}" class="text-xs text-uppercase">
                                        {{ $item->district_name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-1">
                        <label for="name" class="col-form-label">Disease</label>
                        <select name="sel_disease_cd" id="sel_disease_cd" class="text-xs form-control">
                            <option value="A" selected>All Disease</option>
                            @foreach ($digeaseMaster as $item)
                                <option value="{{ $item->disease_cd }}" class="text-xs text-uppercase">
                                    {{ $item->disease_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-1">
                        <label for="name"
                            class="col-form-label">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</label>
                        <button type="submit" class="btn btn-primary" style="border-radius:5px"><i
                                class="fa-solid fa-floppy-disk"></i> Show</button>
                    </div>
                </div>
            </form>
        </div>
    </div> --}}
    {{-- <div class="row">
        <div class="col-sm-12 p-1 col-md-2">
            <div class="card mb-4"> --}}
                
                {{-- <h6 class="py-1 border-bottom"><i class="fa fa-bars mr-1 text-xs"></i>Summary Data</h6> --}}
                {{-- <div id="disease_summary_info" name="disease_summary_info" class="overflow-auto p-3 bg-light"
                    style="max-width: 260px; max-height: 600px;">

                    @foreach ($dataSetCountDistrictWiseDiseaseWise as $item)
                        <p> <b>{{ $item->district_name }} </b><br>
                            @php
                                $arr_disease_dtls = $item->disease_dtls;

                            @endphp
                            @foreach ($arr_disease_dtls as $arrItem)
                                {{ $arrItem->disease_name }} ({{ $arrItem->total }})<br>
                                <br>
                            @endforeach

                        </p>
                    @endforeach
                </div> --}}

            {{-- </div>
        </div> --}}
        {{-- <div class="col-lg-10 mb-4 order-0">
            <div class="card mb-3">
                <div id='map_in_frame'></div> --}}
                
                {{-- <div class="modal fade" id="mapModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-body">
                              
                                <h2 id="marker_title" class=""></h2>
                                <img id="disease_name" class="" src="" />
                                <p id="district" class=""></p>
                                <address id="locality" class=""></address>
                                <p id="lat_lon" class=""></p>
                                <p id="date" class=""></p>
                            </div>
                        </div>
                    </div>
                </div> --}}
            {{-- </div>
        </div> --}}
    {{-- </div> --}}
    {{-- <script></script> --}}
    <div class="row">
        <div class="col-lg-8 mb-2 order-0">
            <div class="card">
                <div class="card-body" id="chartDiseaseWise">
                </div>
            </div>
        </div>
    </div>
    <div class="row">

        <div class="col-lg-6 col-md-4 order-1">
            <div class="card">
                <div class="card-header" id="">
                    <h3 id="newsHeader">Agri News</h3>
                </div>
                <div class="card-body" id="newsChart">
                    {{-- Java Script Chart on queries --}}
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-2 order-0">
            <div class="card">
                <div class="card-header" id="">
                    <h3 id="queryHeader">Header Text</h3>
                </div>
                <div class="card-body" id="queryChart">
                    {{-- Java Script Chart on queries --}}
                </div>
            </div>
        </div>
    </div>
    <br>
    {{-- Modal Window for Diseasewise Data --}}
    <div class="modal modal-xl" id="diseaseWiseModal" tabindex="-1" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="diseaseWiseTitle">Disease Wise Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body modal-dialog-centered modal-lg" id='map'
                    style='width: 700px; height: 500px;'>
                    {{-- <form action="#" id="diseaseWiseDataForm">
                        @csrf
                    </form> --}}
                </div>
            </div>
        </div>
    </div>


    {{-- Modal Window for District Wise Data --}}
    <div class="modal fade" id="districtWiseModal" tabindex="-1" aria-hidden="true"
        style="display: none; width: 100%; height: 700px;">
        <div class="modal-dialog modal-dialog-centered  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="districtWiseTitle">District Wise Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body mapboxgl-map  modal-lg" id='mapzzzzx' style='width: 100%; height: 500px;'>
                    <form action="#" id="districtWiseDataForm">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css" />
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>

    <script src='https://api.mapbox.com/mapbox-gl-js/v2.9.1/mapbox-gl.js'></script>
    <link href='https://api.mapbox.com/mapbox-gl-js/v2.9.1/mapbox-gl.css' rel='stylesheet' />
    <script src="admin_assets/js/sweetalert2/sweetalert2.all.js"></script>
    <script src="admin_assets/js/sweetalert2/sweetalert2.all.min.js"></script>
    <script src="admin_assets/js/sweetalert2/sweetalert2.js"></script>
    <script src="admin_assets/js/sweetalert2/sweetalert2.min.js"></script>
    <style>
        #map {
            height: 600px;
        }

        #mapzzzzx {
            height: 600px;
        }

        #map_in_frame {
            height: 650px;
        }

        .modal-lg {
            max-width: 80%;
        }

        .mapboxgl-popup {
            max-width: 500px;
        }

        .mapboxgl-popup-anchor-top .mapboxgl-popup-tip,
        .mapboxgl-popup-anchor-top-left .mapboxgl-popup-tip,
        .mapboxgl-popup-anchor-top-right .mapboxgl-popup-tip {
            border-bottom-color: #fff;
        }

        .mapboxgl-popup-anchor-bottom .mapboxgl-popup-tip,
        .mapboxgl-popup-anchor-bottom-left .mapboxgl-popup-tip,
        .mapboxgl-popup-anchor-bottom-right .mapboxgl-popup-tip {
            border-top-color: #fff !important;
        }

        .mapboxgl-popup-anchor-left .mapboxgl-popup-tip {
            border-right-color: #fff;
        }

        .mapboxgl-popup-anchor-right .mapboxgl-popup-tip {
            border-left-color: #fff;
        }

        .mapboxgl-popup-content {
            text-align: left;
            font-family: 'Open Sans', sans-serif;
            font-size: 20px;
            width: 400px;
            border: 1px;
            border-block: 1px;
        }

        .circleMarker {
            background-image: url("{{ asset('admin_assets/img/icons/mapbox_icons/circle.png') }}");
            background-size: cover;
            display: block;
            width: 40px;
            height: 50px;
            border: 1px;
            border-radius: 50%;
            cursor: pointer;
            padding: 10;
        }
    </style>
    <script>
        $(document).ready(function() {
            var geojson;
            var markers = [];
            var data = <?php echo $diseaseWiseCountArr; ?>;
            var query_count_data = <?php echo $query_count_data; ?>;
            var piChartData = <?php echo $piChartData; ?>;
            var DataSet_count_diseaseWise_for_bar_chart = <?php echo $DataSet_count_diseaseWise_for_bar_chart; ?>;
            var districtWiseAllDiseaseJsonData = <?php echo $districtWiseAllDiseaseJsonData; ?>;
            // mapboxgl.accessToken =
            //     'pk.eyJ1IjoiY3JvcGhlYWx0aDIyIiwiYSI6ImNsZmM3NmxwbzBobmMzeHBna3QwYTNqcDYifQ.cDrgvqT9hpd8JSWaOO-Cdw';
            // const map_in_frame = new mapboxgl.Map({
            //     container: 'map_in_frame',
            //     style: 'mapbox://styles/crophealth22/clivl924z00y801qv28v3aoc1',
            //     center: [92.657, 26.191],
            //     zoom: 6.7
            // });
            // map_in_frame.addControl(new mapboxgl.NavigationControl());
            // let c_marker;
            // let el;


            // el = document.createElement('div');
            // el.className = 'circleMarker';
            // el.id = 'c_marker';
            // for (let i = 0; i < data.length; i++) {
            //     let jsonArr = data[i];
            //     // console.log(jsonArr);
            //     for (let j = 0; j < jsonArr.length; j++) {
            //         jsonObj = jsonArr[j];
            //         setMarkerInMap(jsonObj);

            //         // markers.push(c_marker);
            //     }
            // }

            // function setMarkerInMap(jsonObj) {
            //     el = document.createElement('div');
            //     el.className = 'circleMarker';
            //     el.id = 'c_marker';
            //     c_marker = new mapboxgl.Marker(el)
            //         .setLngLat(jsonObj.latLon)
            //         .setPopup(
            //             new mapboxgl.Popup({
            //                 offset: 50,
            //                 className: "mapboxgl-popup"
            //             }) //add popups
            //             .setHTML(`<P style="color:black;">Disease Name : ${jsonObj.disease_name}</P>
            //                 <P style="color:black;">District: ${jsonObj.district_name}</P>
            //                 <P style="color:black;">Locality: ${jsonObj.locality_name}</P>
            //                 <P style="color:black;">Long Lat : ${jsonObj.latLon}</P>
            //                 <P style="color:black;">Date: ${jsonObj.date}</P>`)
            //         ).addTo(map_in_frame)
            // }

            // function removePreviousMarkerFromMap() {
            //     //Remove the previous Markers -- Start
            //     var markerElements = document.getElementsByClassName(
            //         'circleMarker');
            //     while (markerElements.length > 0) {
            //         markerElements[0].parentNode.removeChild(markerElements[
            //             0]);
            //     }
            //     //Remove the previous Markers -- End
            // }

            // //Dashboard Filterting Data to display in Map -- Start
            // $('form.frmGenerateDataForMap').on("submit", function(e) {
            //     e.preventDefault();
            //     var form = $(this);
            //     var formData = form.serialize();
            //     var selectedDistCD = $("#sel_dist_cd").val();
            //     var selectedDistName = $("#sel_dist_cd option:selected").text();
            //     var selectedDiseaseCD = $("#sel_disease_cd").val();
            //     var selectedDiseaseName = $("#sel_disease_cd option:selected").text();
            //     var from_date = $("#frm_date").val();
            //     var to_date = $("#to_date").val();
            //     $.ajax({
            //         type: "POST",
            //         url: form.attr('action'),
            //         headers: {
            //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //         },
            //         data: formData,
            //         cache: false,
            //         success: function(response) {
            //             var filtered_data = response;
            //             // console.log("Filered data : ".filtered_data));
            //             var htmlStr = "<p>";
            //             var i = 0;
            //             if (filtered_data != '') {
            //                 var old_dist_name = filtered_data[0].district_name;
            //                 $.each(filtered_data, function(index, val) {
            //                     var district_name = val["district_name"]
            //                     if (i > 0 && old_dist_name == district_name) {
            //                         old_dist_name = district_name;
            //                         return true;
            //                     }

            //                     var dis_dtls = val["disease_dtls"]
            //                     var disease_name = dis_dtls[0].disease_name;
            //                     var total_count = dis_dtls[0].total;
            //                     subHtml = "";
            //                     $.each(dis_dtls, function(index1, value1) {
            //                         // console.log(value1["disease_name"])
            //                         subHtml = subHtml + value1["disease_name"] +
            //                             "(" + value1["total"] + ")<br>"
            //                     });

            //                     htmlStr = htmlStr + "<b>" + district_name + "</b><br>" +
            //                         subHtml + "<br>"
            //                     i++;
            //                 });
            //                 htmlStr = htmlStr + "</p>"
            //                 $("#disease_summary_info").html(htmlStr);


            //                 if (selectedDistCD == "A") {
            //                     for (let i = 0; i < data.length; i++) {
            //                         let jsonArr = data[i];
            //                         for (let j = 0; j < jsonArr.length; j++) {
            //                             jsonObj = jsonArr[j];
            //                             setMarkerInMap(jsonObj);
            //                         }
            //                     }
            //                     return;
            //                 }

            //                 for (let i = 0; i < districtWiseAllDiseaseJsonData.length; i++) {
            //                     let jsondata = districtWiseAllDiseaseJsonData[i];
            //                     // console.log(jsondata.district_cd);
            //                     if (jsondata.district_cd == selectedDistCD && selectedDistCD !=
            //                         "A") {
            //                         removePreviousMarkerFromMap();

            //                         var dist_jason_data = jsondata.disease_dtls;
            //                         // console.log(dist_jason_data);
            //                         for (let j = 0; j < dist_jason_data.length; j++) {
            //                             jsonObj = dist_jason_data[j];
            //                             setMarkerInMap(jsonObj);
            //                         }
            //                         break;
            //                     }
            //                 }
            //             } else {
            //                 var msg = "No Data found for";
            //                 if (selectedDistCD != "A")
            //                     msg = msg + " District : " + selectedDistName;

            //                 if (selectedDiseaseCD != "A")
            //                     msg = msg + " Disease Name : " + selectedDiseaseName;

            //                 if (from_date != "" && to_date != "")
            //                     msg = msg + " From : " + from_date + " To: " + to_date;

            //                 Swal.fire({
            //                     icon: 'warning',
            //                     title: '',
            //                     text: msg,
            //                     showConfirmButton: true,
            //                     timer: 5000
            //                 }).then(() => {
            //                     location.reload();

            //                     // window.location.replace(location);
            //                 });
            //             }

            //         },
            //         error: function(response) {
            //             console.log(response);
            //             Swal.fire({
            //                 icon: 'error',
            //                 title: 'error',
            //                 text: "Requested Data Could Not Be Fetched Successfully!!!!\nPlease Try Again...",
            //                 showConfirmButton: true,
            //                 timer: 5000
            //             }).then(() => {
            //                 location.reload();

            //                 // window.location.replace(location);
            //             });
            //         }
            //     });
            //     //Dashboard Filterting Data to display in Map -- Start
            // });


            $('#queryHeader').text("Farmer Query Information");

            options = {
                chart: {
                    type: 'bar',
                    height: 300
                },
                plotOptions: {
                    bar: {
                        distributed: true,
                        horizontal: true
                    }
                },
                colors: ['#FC9F00', '#1033F0', '#21A407'],
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

            var chartFarmersQryInfo = new ApexCharts(document.querySelector("#queryChart"),
                options);
            chartFarmersQryInfo.render();

            var optionsPiChart = {
                series: [80, 30, 90],
                chart: {
                    width: '360',
                    type: 'donut',
                },
                labels: ["General", "Schemes", "FAQ"],
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

                title: {
                    text: "Agri News Pie"
                },

                legend: {
                    position: 'right',
                    offsetY: 0,
                    height: 230,
                }
            };

            var chartAgriNews = new ApexCharts(document.querySelector("#newsChart"), optionsPiChart);
            chartAgriNews.render();


            chartFarmersQryInfo.updateSeries([{
                name: 'Queries',
                data: query_count_data
            }]);



            var arr = chartAgriNews.w.globals.series.slice()
            arr[0] = piChartData[0];
            arr[1] = piChartData[1];
            arr[2] = piChartData[2];
            chartAgriNews.updateSeries(arr);


            optionsDiseaseWiseChart = {
                chart: {
                    type: 'bar',
                    height: 300
                },
                plotOptions: {
                    bar: {
                        distributed: true,
                        horizontal: false
                    }
                },
                colors: ['#E8FA00', '#21A407', '#F48319', '#1033F0', '#FC0F00', '#FC9F00'],

                series: [{
                    data: DataSet_count_diseaseWise_for_bar_chart
                }],
                title: {
                    text: "Disease Wise Bar Chart"
                }
            }

            var chartDiseaseWise = new ApexCharts(document.querySelector("#chartDiseaseWise"),
                optionsDiseaseWiseChart);
            chartDiseaseWise.render();
        });
    </script>
    {{-- <script src="{{asset('admin_assets/js/dashboard/admin.js')}}"></script> --}}
@endsection
