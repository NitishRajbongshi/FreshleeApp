@extends('admin.common.layout')

@section('title', 'Dashboard')

@section('custom_header')
    <meta name="X-CSRF-Token" content="{{ csrf_token() }}" />
@endsection

@section('main')
    {{-- <h1>Dashboard</h1> --}}
    {{-- {{Session::get('current_role')}} --}}
    {{-- {{!! dd(Session::pull('roles')) !!}} --}}

    <div class="row">
        <div class="col-lg-12 mb-3 order-0">
            <div class="card">
                <div class="col-lg-12 mb-2 order-1 dashboard-marquee">
                    <marquee behavior="scroll" direction="left" scrollamount="6">
                        <label class="text-danger">Last Issue Diagnosed: {{ $lastDiagnosedDiseaseName }}, at
                            {{ $lastDiagnosedDiseaseAtlocation }}, {{ $lastDiagnosedDiseaseAtDistrict }}.</label>
                    </marquee>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 mb-4 order-0">
            <div class="card mb-3">
                <div id='map_in_frame'></div>
                {{-- <iframe id='map_in_frame' width='80%' height='600px'
                    src="https://api.mapbox.com/styles/v1/crophealth22/clivl924z00y801qv28v3aoc1.html?title=false&access_token=pk.eyJ1IjoiY3JvcGhlYWx0aDIyIiwiYSI6ImNsZmM3NmxwbzBobmMzeHBna3QwYTNqcDYifQ.cDrgvqT9hpd8JSWaOO-Cdw&zoomwheel=false#6.33/25.931/93.132"
                    title="AssamMap" style="border:2px solid rgb(24, 223, 230);"></iframe> --}}
            </div>
        </div>
    </div>
    <script></script>
    <div class="row">
        <div class="col-lg-8 mb-4 order-0">

            {{-- <div class="card mb-3">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-7">
                        <div class="card-body">
                            <h5 class="card-title text-primary">Congratulations, {{ auth()->user()->name }}</h5>
                            <p class="mb-4">Welcome {{ auth()->user()->name }}, to the Dashboard of
                                Crop Health Diagnosis System (BETA). Here are some key system information at a glance.</p>
                        </div>
                    </div>
                    <div class="col-sm-5 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-4">
                            <img src="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template-free/demo/assets/img/illustrations/man-with-laptop-light.png"
                                height="140" alt="View Badge User"
                                data-app-dark-img="illustrations/man-with-laptop-dark.png"
                                data-app-light-img="illustrations/man-with-laptop-light.png">
                        </div>
                    </div>
                </div>
            </div> --}}
            <div class="card">

                <div class="card-body" id="chartDiseaseWise">
                    {{-- Java Script Chart on queries --}}
                </div>
            </div>

        </div>

        <div class="col-lg-4 col-md-4 order-1">
            <div class="card">
                <div class="card-header" id="">
                    <h3 id="newsHeader">Agri News</h3>
                </div>
                <div class="card-body" id="newsChart">
                    {{-- Java Script Chart on queries --}}
                </div>
            </div>
            {{-- <div class="row">
                <div class="col-lg-6 col-md-12 col-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0 text-success">
                                    <i class='bx bx-user-pin'></i>
                                </div>
                            </div>

                            <span class="fw-semibold d-block mb-1">Moderators</span>
                            <h3 class="card-title mb-2">{{ $moderators }}</h3>
                            <small class="text-success fw-semibold"><i class='bx bx-user-plus'></i>
                                +{{ $moderatorThisMonth }} This Month</small>
                        </div>
                    </div>
                </div> --}}
            {{-- <div class="col-lg-6 col-md-12 col-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0  text-info">

                                <i class='bx bxs-user-pin'></i>
                            </div>

                        </div>
                        <span class="fw-semibold d-block mb-1">Agri-Expert</span>
                        <h3 class="card-title mb-2">{{ $agriExperts }}</h3>
                        <small class="text-info fw-semibold"><i class='bx bxs-user-plus'></i>
                            +{{ $agriExpertThisMonth }} This Month</small>
                    </div>
                </div>
            </div> --}}
        </div>

    </div>


    </div>


    <div class="row">
        <div class="col-lg-8 mb-4 order-0">

            <div class="card">
                <div class="d-flex align-items-center">
                    <h5 class="card-header">Diseasewise Data </h5>
                </div>
                <div class="table-responsive text-nowrap px-4">
                    <table class="table" id="tblDiseaseWise">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Disease Name</th>
                                <th>No. of Diagnosed</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">


                            @foreach ($jsonFromDataSetFile as $index => $item)
                                <tr>
                                    {{-- @forelse ($item as $x => $val) --}}
                                    <td>
                                        <strong>{{ $index + 1 }}</strong>
                                    </td>
                                    <td>{{ $item->disease_name }}</td>
                                    <td>{{ $item->total }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary OpenDiseaseWiseModalBtn"
                                            data-id="{{ $item->disease_cd }}">
                                            <i class='bx bx-show'></i>Show</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
        <div class="col-lg-4 mb-4 order-0">
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
    <div class="row">
        <div class="card">
            <div class="d-flex align-items-center">
                <h5 class="card-header">Districtwise Data </h5>
            </div>
            <div class="table-responsive text-nowrap px-4">
                <table class="table" id="tblDistrictWise">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>District Name</th>
                            <th>No. of Diagnosed</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($districtWisejsonData as $index => $item)
                            <tr>
                                {{-- @forelse ($item as $x => $val) --}}
                                <td>
                                    <strong>{{ $index + 1 }}</strong>
                                </td>
                                <td>{{ $item->district_name }}</td>
                                <td>{{ $item->total }}</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary OpenDistrictWiseModalBtn"
                                        data-id="{{ $item->district_cd }} , {{ json_encode($item->district_center_lat) }}, {{ json_encode($item->district_center_lat) }}">

                                        <i class='bx bx-show'></i>Show</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    {{-- Modal Window for Diseasewise Data --}}
    <div class="modal modal-lg" id="diseaseWiseModal" tabindex="-1" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="diseaseWiseTitle">Disease Wise Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body modal-dialog-centered modal-lg" id='map' style='width: 700px; height: 500px;'>
                    <form action="#" id="diseaseWiseDataForm">
                        @csrf
                    </form>
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
            max-width: 200px;
        }

        .mapboxgl-popup-content {
            text-align: center;
            font-family: 'Open Sans', sans-serif;
        }

        .circleMarker {
            background-image: url("{{ asset('admin_assets/img/icons/mapbox_icons/circle.png') }}");
            background-size: cover;
            /* display: block; */
            width: 20px;
            height: 20px;
            border: None;
            border-radius: 50%;
            cursor: pointer;
            padding: 10;
        }
    </style>
    <script>
        $(document).ready(function() {
            var geojson;
            var markers = [];
            // $("#tblDiseaseWise").on("click", ".OpenDiseaseWiseModalBtn", function(){

            //   $('#diseaseWiseModal').on('shown.bs.modal', function (e) {
            //     // $('#editState').focus();
            //     map.resize();
            //   }).modal('show');
            // });

            // mapboxgl.accessToken =
            //     'pk.eyJ1IjoiY3JvcGhlYWx0aDIyIiwiYSI6ImNsZmM3NmxwbzBobmMzeHBna3QwYTNqcDYifQ.cDrgvqT9hpd8JSWaOO-Cdw';
            mapboxgl.accessToken =
                'pk.eyJ1IjoiY3JvcGhlYWx0aDIyIiwiYSI6ImNsZmM3NmxwbzBobmMzeHBna3QwYTNqcDYifQ.cDrgvqT9hpd8JSWaOO-Cdw';
            const map_in_frame = new mapboxgl.Map({
                container: 'map_in_frame',
                style: 'mapbox://styles/crophealth22/clivl924z00y801qv28v3aoc1',
                center: [92.657, 26.191],
                zoom: 6.7
            });

            // $.ajax({
            //     url: "{{ route('admin.loadDataSetOfAllDiseasesDistrictWise') }}",
            //     headers: {
            //         'X-CSRF-Token': '{{ csrf_token() }}',
            //         'accept': 'application/json'
            //     },
            //     type: "GET",
            //     data: {
            //         dist_cd: dist
            //     },
            //     success: function(data) {
            //         console.log(data);

            //         for (let i = 0; i < data.length; i++) {
            //             let jsonObj = data[i];
            //             console.log(jsonObj.latLon);
            //             const el = document.createElement('div');
            //             el.className = 'circleMarker';
            //             let c_marker = new mapboxgl.Marker(el)
            //                 .setLngLat(jsonObj.latLon)
            //                 .setPopup(
            //                     new mapboxgl.Popup({
            //                         offset: 15
            //                     }) //add popups
            //                     .setHTML(`<P>Disease Name : ${jsonObj.disease_name}</P>
        //                   	<P>District: Kamrup Metro</P>
        // 	<P>Long Lat : ${jsonObj.latLon}</P>
        // 	<P>Date: 10-01-2024</P>`)
            //                 ).addTo(mapIfrm);
            //             markers.push(c_marker);
            //         }
            //     },
            //     error: function(xhr, status, error) {
            //         alert('Something went wrong');
            //         console.log("Something went wrong" + error);

            //     }
            // });


            const mapx = new mapboxgl.Map({
                container: 'mapzzzzx',
                style: 'mapbox://styles/crophealth22/clivl924z00y801qv28v3aoc1',
                center: [92.657, 26.191],
                zoom: 6,
            });





            $("#tblDistrictWise").on("click", ".OpenDistrictWiseModalBtn", function() {

                var distName_distCenter = $(this).data("id").split(",");
                var dist = distName_distCenter[0];
                var lat = distName_distCenter[1];
                var lon = distName_distCenter[2];
                // mapx.flyTo({
                //   center: [lat,lon],
                //   zoom:10
                // });

                $('#districtWiseModal').on('shown.bs.modal', function(e) {
                    // $('#editState').focus();
                    mapx.resize();
                }).modal('show');
                markers.forEach((marker) => marker.remove());
                markers = [];
                $.ajax({
                    url: "{{ route('admin.loadDataSetOfAllDiseasesDistrictWise') }}",
                    headers: {
                        'X-CSRF-Token': '{{ csrf_token() }}',
                        'accept': 'application/json'
                    },
                    type: "GET",
                    data: {
                        dist_cd: dist
                    },
                    success: function(data) {
                        console.log(data);

                        for (let i = 0; i < data.length; i++) {
                            let jsonObj = data[i];
                            console.log(jsonObj.latLon);
                            const el = document.createElement('div');
                            el.className = 'circleMarker';
                            let c_marker = new mapboxgl.Marker(el)
                                .setLngLat(jsonObj.latLon)
                                .setPopup(
                                    new mapboxgl.Popup({
                                        offset: 15
                                    }) //add popups
                                    .setHTML(`<P>Disease Name : ${jsonObj.disease_name}</P>
                                      <P>District: Kamrup Metro</P>
                    <P>Long Lat : ${jsonObj.latLon}</P>
                    <P>Date: 10-01-2024</P>`)
                                ).addTo(mapx);
                            markers.push(c_marker);
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Something went wrong');
                        console.log("Something went wrong" + error);

                    }
                });

                for (const feature of geojson.features) {
                    if ((feature.district_cd).trim() == dist.trim()) {
                        console.log("Milise");
                        let marker = new mapboxgl.Marker({
                                color: '#ED7D31',
                                rotation: 20
                            })
                            .setLngLat(feature.geometry.coordinates)
                            .setPopup(
                                new mapboxgl.Popup({
                                    offset: 15
                                }) //add popups
                                .setHTML(`<P>${feature.properties.title}</P>
                      <p>${feature.properties.description}</p>
                      <p>${feature.disease_names}</p>`)
                            ).addTo(mapx);
                        markers.push(marker);
                        break;
                    }

                }


            });


            $.getJSON('http://127.0.0.1:8000/loadDistrictWiseGeoJsonData', function(jd) {
                geojson = JSON.parse(jd);
                for (const feature of geojson.features) {
                    const el = document.createElement('div');
                    el.className = 'marker';
                    new mapboxgl.Marker(el)
                        .setLngLat(feature.geometry.coordinates)
                        .setPopup(
                            new mapboxgl.Popup({
                                offset: 15
                            }) //add popups
                            .setHTML(`<h4>${feature.properties.title}</h4>
                        <p>${feature.properties.description}</p>
                        <p>${feature.disease_names}</p>`)
                        ).addTo(mapx);
                }
            });


            // $('#diseaseWiseModal').DataTable();

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

            var chartFarmersQryInfo = new ApexCharts(document.querySelector("#queryChart"), options);
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

            $.ajax({
                url: "{{ route('admin.dashboard1') }}",
                type: "GET",

                headers: {
                    'X-CSRF-Token': '{{ csrf_token() }}',
                    'accept': 'application/json'
                },
                contentType: "application/json",
                success: function(response) {
                    chartFarmersQryInfo.updateSeries([{
                        name: 'Queries',
                        data: response
                    }]);
                }
            });

            $.ajax({
                url: "{{ route('admin.jsondataAgriNews') }}",
                success: function(response) {
                    var arr = chartAgriNews.w.globals.series.slice()
                    arr[0] = response[0];
                    arr[1] = response[1];
                    arr[2] = response[2];
                    chartAgriNews.updateSeries(arr);
                }
            });



            var disCountData = null;
            $.ajax({
                url: "{{ route('getDiseaseWiseCount') }}",
                type: "GET",

                headers: {
                    'X-CSRF-Token': '{{ csrf_token() }}',
                    'accept': 'application/json'
                },
                contentType: "application/json",
                success: function(response) {
                    optionsDiseaseWiseChart = {
                        chart: {
                            type: 'bar',
                            height: 300
                        },
                        plotOptions: {
                            bar: {
                                horizontal: false
                            }
                        },
                        series: [{
                            data: response
                        }],
                        title: {
                            text: "Disease Wise Bar Chart"
                        }
                    }

                    var chartDiseaseWise = new ApexCharts(document.querySelector("#chartDiseaseWise"),
                        optionsDiseaseWiseChart);
                    chartDiseaseWise.render();
                }
            });
            console.log(disCountData);


        });
    </script>
    {{-- <script src="{{asset('admin_assets/js/dashboard/admin.js')}}"></script> --}}
@endsection
