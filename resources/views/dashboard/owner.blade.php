
<div class="modal fade" id="modalFilter" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <h3 class="text-center m-1">Filter Revenue</h3>
                <label for="">Month</label>
                <select name="" class="form-control" id="month">
                    <option value="0">All</option>
                    <option value="1">Januari</option>
                    <option value="2">Februari</option>
                    <option value="3">Maret</option>
                    <option value="4">April</option>
                    <option value="5">Mei</option>
                    <option value="6">Juni</option>
                    <option value="7">Juli</option>
                    <option value="8">Agustus</option>
                    <option value="9">September</option>
                    <option value="10">Oktober</option>
                    <option value="11">November</option>
                    <option value="12">Desember</option>
                </select>
                <label for="">Year</label>
                <select name="" class="form-control" id="year">
                    <option value="0">All</option>
                    @for($j = 2015; $j <= 2215; $j++) <option value="{{ $j }}">{{ $j }}</option>
                        @endfor
                </select>
            </div>
            <div class="modal-footer mb-2">
                <div onclick="getRevenueFilter()" class="btn btn-primary">Ya!</div>
            </div>
        </div>
    </div>
</div>




<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table-dashboard table table-condensed table-striped table-bordered">
                <tr>
                    <th> PO Belum Diterima (Recieve) </th>
                    <td> {{ $po_ri }} </td>
                </tr>
                <tr>
                    <th> Penjualan Belum Dikirim (Delivery)</th>
                    <td> {{ $so_do }} </td>
                </tr>
            </table>
        </div>
    </div>
</div>



<script type="text/javascript">
    $(document).ready(function() {
        $('.table-employee').DataTable();
    })
</script>

@section('myjs')
<script type="text/javascript">
    $(document).ready(function() {
        $('.table-employee').DataTable();
    })

    function modalFilter() {
        $('#modalFilter').modal('show');
    }

    function getRevenueFilter() {
        let day = $('#day').val();
        let month = $('#month').val();
        let year = $('#year').val();
        $.ajax({
            method: "GET",
            url: "/admin/filterrevenue/" + month + "/" + year + "",
            dataType: "JSON",
            success: function(data) {
                $('#revenue').html("Rp." + data)
            },
            error: function(e) {
                console.log(e)
            }
        })
        $('#modalFilter').modal('hide');
    }

    function formatRupiah(angka, prefix) {
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        // tambahkan titik jika yang di input sudah menjadi angka ribuan
        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
    }

    var chartWrapper = $('.chartjs'),
        flatPickerOmsetPenjualan = $('.flat-picker-omset-penjualan'),
        barChartOmsetPenjualan = $('.bar-chart-omset-penjualan');

    // Color Variables
    var primaryColorShade = '#836AF9',
        yellowColor = '#ffe800',
        successColorShade = '#28dac6',
        warningColorShade = '#ffe802',
        warningLightColor = '#FDAC34',
        infoColorShade = '#299AFF',
        greyColor = '#4F5D70',
        blueColor = '#2c9aff',
        blueLightColor = '#84D0FF',
        greyLightColor = '#EDF1F4',
        tooltipShadow = 'rgba(0, 0, 0, 0.25)',
        lineChartPrimary = '#666ee8',
        lineChartDanger = '#ff4961',
        labelColor = '#6e6b7b',
        grid_line_color = 'rgba(200, 200, 200, 0.2)'; // RGBA color helps in dark layout

    // Detect Dark Layout
    if ($('html').hasClass('dark-layout')) {
        labelColor = '#b4b7bd';
    }

    // Wrap charts with div of height according to their data-height
    if (chartWrapper.length) {
        chartWrapper.each(function() {
            $(this).wrap($('<div style="height:' + this.getAttribute('data-height') + 'px"></div>'));
        });
    }


    //BAR Chart OMSET PENJUALAN
    if (flatPickerOmsetPenjualan.length) {
        var now = new Date();
        var date = new Date();
        var lastWeekDate = new Date(date.setDate(date.getDate() - 7));
        flatPickerOmsetPenjualan.each(function() {
            $(this).flatpickr({
                mode: 'range',
                defaultDate: [lastWeekDate, now],
                onChange: function(dateObj, dateStr) {
                    if (dateStr.length == 24) {
                        toastr['success']('👋 Sukses melakukan filter, Data sedang diproses....', 'Success!', {
                            closeButton: true,
                            tapToDismiss: true,
                        });
                        var tanggalAwal = dateStr.substr(0, 10);
                        var tanggalAkhir = dateStr.substr(dateStr.length - 10);
                        chartNya($('.bar-chart-omset-penjualan'), tanggalAwal, tanggalAkhir);
                    }
                }
            });
        });
    }

    chartNya(barChartOmsetPenjualan);

    function chartNya(barChartOmsetPenjualan, tanggal_awal = null, tanggal_akhir = null) {
        var tanggalNya = [];
        var subTotalNya = [];
        var subTotalCustomNya = [];
        if (barChartOmsetPenjualan.length) {
            $.ajax({
                type: 'post',
                url: "{{ route('admin.dashboard.omset') }}",
                data: {
                    tanggal_awal: tanggal_awal,
                    tanggal_akhir: tanggal_akhir,
                },
                async: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: (result) => {
                    for (let i = 0; i < result.length; i++) {
                        tanggalNya.push(result[i].tanggal_custom);
                        subTotalNya.push(result[i].sub_total);
                        subTotalCustomNya.push(result[i].sub_total_custom);
                    }
                },
                error: function(data) {
                    Swal.fire({
                        title: 'Error!',
                        text: "Error pada server, Silahkan hubungi Administrator!",
                        icon: 'error',
                        customClass: {
                            confirmButton: 'btn btn-primary'
                        },
                        buttonsStyling: false
                    });
                },
            });

            var barChartOmsetPenjualan = new Chart(barChartOmsetPenjualan, {
                type: 'bar',
                options: {
                    elements: {
                        rectangle: {
                            borderWidth: 2,
                            borderSkipped: 'bottom'
                        }
                    },
                    responsive: true,
                    maintainAspectRatio: false,
                    responsiveAnimationDuration: 500,
                    legend: {
                        display: false
                    },
                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem, data) {
                                var label = data.labels[tooltipItem.index];
                                var val = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                                var total = subTotalCustomNya;

                                return "Tanggal: " + data.labels[tooltipItem.index] + " Total Omset: " + total[tooltipItem.index];
                            }
                        },
                        // Updated default tooltip UI
                        shadowOffsetX: 1,
                        shadowOffsetY: 1,
                        shadowBlur: 8,
                        shadowColor: tooltipShadow,
                        backgroundColor: window.colors.solid.white,
                        titleFontColor: window.colors.solid.black,
                        bodyFontColor: window.colors.solid.black
                    },
                    scales: {
                        xAxes: [{
                            display: true,
                            gridLines: {
                                display: true,
                                color: grid_line_color,
                                zeroLineColor: grid_line_color
                            },
                            scaleLabel: {
                                display: false
                            },
                            ticks: {
                                fontColor: labelColor
                            }
                        }],
                        yAxes: [{
                            display: true,
                            gridLines: {
                                color: grid_line_color,
                                zeroLineColor: grid_line_color
                            },
                            ticks: {
                                // Include a dollar sign in the ticks
                                callback: function(value, index, values) {
                                    return value;
                                }
                            },
                        }]
                    }
                },
                data: {
                    labels: tanggalNya,
                    datasets: [{
                        barThickness: 15,
                        data: subTotalNya,
                        backgroundColor: successColorShade,
                        borderColor: 'transparent'
                    }]
                }
            });
        }
    }

    // SAMPAI SINI
    $(document).ready(function() {
        $('.table-dashboard').DataTable();
    })
</script>
@endsection