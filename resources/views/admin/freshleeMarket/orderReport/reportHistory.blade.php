@extends('admin.common.layout')
@section('title', 'User Role Management')
@section('custom_header')
@endsection
@section('main')
    <div class="card">
        <div class="card-body lh-1">
            <form action="{{ route('admin.order.report.history') }}" method="POST">
                @csrf
                <div class="row align-items-end text-sm">
                    <div class="col-12 col-md-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input class="form-control form-control-sm" type="date" name="start_date"
                            value="{{ $first ?? '' }}">
                    </div>
                    <div class="col-12 col-md-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input class="form-control form-control-sm" type="date" name="end_date"
                            value="{{ $today ?? '' }}">
                    </div>
                    <div class="col-12 col-md-3">
                        <button type="submit" class="btn btn-sm btn-primary">View History</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card my-1">
        <div class="card-header">
            <h5 class="text-md lh-1">Item Report History
                <br>
                <span class="text-xs text-secondary">Showing results between <span class="text-primary">
                        {{ $first ?? '' }}</span> to
                    <span class="text-primary">{{ $today ?? '' }}</span>.</span>
            </h5>
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
