@extends('admin.common.layout')
@section('title', 'User Role Management')
@section('custom_header')
@endsection
@section('main')
    <div class="card my-1">
        <div id="report" class="card-header d-flex flex-wrap justify-content-between align-items-center">
            <h5 class="text-md lh-1">
                Item Report <br>
                <span class="text-xs text-secondary">Listing all the ordered items between <span class="text-primary">
                        {{ $start }}</span> (Monday) to
                    <span class="text-primary">{{ $today }}</span> (Present Day).</span>
            </h5>
            <div class="d-flex align-items-center">
                <form action="{{ route('admin.order.report.history') }}" method="POST">
                    @csrf
                    <input type="hidden" id="start_date" name="start_date" value="{{ $first }}">
                    <input type="hidden" id="end_date" name="end_date" value="{{ $today }}">
                    <button type="submit"
                        class="btn btn-md text-xs btn-outline-none text-danger text-decoration-underline">
                        Report History
                    </button>
                </form>
            </div>
        </div>
        <div class="table-responsive text-nowrap px-4 pb-2 row">
            <div class="col-12 col-md-6">
                <table class="table text-xs border" id="tblUser">
                    <thead>
                        <tr>
                            <th scope="col">SL. No.</th>
                            <th scope="col" class="text-start">ITEM NAME</th>
                            <th scope="col" class="text-end">ITEM AMOUNT</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @php
                            $serialNumber = 1; // Initialize serial number counter
                        @endphp
                        @foreach ($data as $index => $item)
                            <tr class="text-center">
                                <td>{{ $serialNumber++ }}</td>
                                <td class="text-start">{{ $item->item_name }}</td>
                                <td class="text-end">{{ $item->total_quantity }} {{ $item->item_price_in }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="">
                    <form action="{{ route('download.report.weekly') }}" method="POST" id="invoiceForm">
                        @csrf
                        <div style="text-align: right;">
                            <button type="submit" class="btn btn-sm btn-warning mt-3">Download Report</button>
                        </div>
                    </form>
                </div>
            </div>
            <div id="pieChart" class="col-12 col-md-6"></div>
        </div>
    </div>

@endsection

@section('custom_js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var successAlert = document.getElementById('successAlert');

            if (successAlert) {
                setTimeout(function() {
                    successAlert.style.opacity = '0';
                    successAlert.style.transition = 'opacity 0.5s ease-out';
                    setTimeout(function() {
                        successAlert.remove();
                    }, 500);
                }, 5000);
            }

            // Data passed from the controller
            const chartLabels = @json($chartLabels);
            const chartData = @json($chartData);

            // ApexCharts configuration
            const options = {
                chart: {
                    type: 'pie',
                    height: 350,
                },
                series: chartData, // Data for the pie chart
                labels: chartLabels, // Labels for the pie chart
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 200
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            };

            // Render the chart
            const chart = new ApexCharts(document.querySelector("#pieChart"), options);
            chart.render();
        });

        $(document).ready(function() {
            $('#tblUser').DataTable();
        });
    </script>
@endsection
