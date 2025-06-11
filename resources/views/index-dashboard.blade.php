@extends('layouts.dashboard')

@section('title', 'Dashboard')

@push('styles')
    <link rel="stylesheet" href="{{ asset('modules/select2/dist/css/select2.min.css') }}">

    <style>
        .qr-wrapper {
            position: relative;
            width: 180px;
            height: 180px;
            margin: 0 auto;
        }

        .qr-code {
            width: 100%;
            height: 100%;
        }

        .qr-icon {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 36px;
            color: #25D366;
            background: white;
            border-radius: 50%;
            padding: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .qr-link {
            word-break: break-all;
            font-size: 0.9rem;
        }

        .qr-link a {
            display: inline-block;
            max-width: 180px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    </style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>@yield('title')</h1>

            </div>

        </section>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="whatsappGroupModal" tabindex="-1" role="dialog"
        aria-labelledby="whatsappGroupModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body p-3">
                    <div class="d-flex justify-content-between align-items-center" style="gap: .5rem">
                        <h5 class="modal-title mb-0" id="whatsappGroupModalLabel">WhatsApp Grup</h5>
                        <button type="button" class="px-2 py-1 close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <hr class="mt-3 mb-4">

                    <p class="text-lead text-center">Silakan pindai kode QR atau klik tautan di bawah ini:</p>

                    <div class="row justify-content-center align-items-center g-4">
                        @if (Auth::user()?->role !== 'family_parent')
                            <div class="col-md-6">
                                <div class="p-2 rounded border border-secondary">
                                    <h6 class="text-center mt-2">Khusus Petugas</h6>

                                    <div class="p-2 my-2 text-center">
                                        <div class="qr-wrapper" data-qr-id="officer_qr">
                                            <div class="qr-code" data-url="{{ $site->officer_wa_group_url }}"></div>
                                            <i class="fab fa-whatsapp qr-icon"></i>
                                        </div>
                                    </div>

                                    <div class="text-center qr-link">
                                        <a href="{{ $site->officer_wa_group_url }}"
                                            target="_blank">{{ $site->officer_wa_group_url }}</a>
                                    </div>
                                </div>
                            </div>

                            <div class="d-inline-block d-md-none">
                                <div class="col-12 my-2"></div>
                            </div>
                        @endif
                        <div class="col-md-6">
                            <div class="p-2 rounded border border-secondary">
                                <h6 class="text-center mt-2">Umum</h6>

                                <div class="p-2 my-2 text-center">
                                    <div class="qr-wrapper" data-qr-id="user_qr">
                                        <div class="qr-code" data-url="{{ $site->wa_group_url }}"></div>
                                        <i class="fab fa-whatsapp qr-icon"></i>
                                    </div>
                                </div>

                                <div class="text-center qr-link">
                                    <a href="{{ $site->wa_group_url }}" target="_blank">{{ $site->wa_group_url }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Page Specific JS File -->
    <script src="{{ asset('modules/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('modules/chart.min.js') }}"></script>

    <!-- QR Code Generator -->
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs/qrcode.min.js"></script>
    <script>
        function generateAllQRCodes() {
            const qrWrappers = document.querySelectorAll('.qr-code');

            qrWrappers.forEach(wrapper => {
                const url = wrapper.getAttribute('data-url');
                wrapper.innerHTML = ""; // prevent duplicate

                if (url) {
                    new QRCode(wrapper, {
                        text: url,
                        width: 180,
                        height: 180,
                        colorDark: "#000000",
                        colorLight: "#ffffff",
                        correctLevel: QRCode.CorrectLevel.H
                    });
                }
            });
        }

        $('#whatsappGroupModal').on('shown.bs.modal', function() {
            generateAllQRCodes();
        });

        $('#whatsappGroupModal').on('hidden.bs.modal', function() {
            document.querySelectorAll('.qr-code').forEach(wrapper => {
                wrapper.innerHTML = '';
            });
        });
    </script>

    <!-- Schedule filter -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.schedule-filter').forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();

                    const day = this.dataset.day;
                    const url = `{{ url('/schedule/ajax') }}/${day}`;

                    // Set active class
                    document.querySelectorAll('.schedule-filter').forEach(el => el.classList.remove(
                        'active'));
                    this.classList.add('active');

                    // Ubah label tombol dropdown
                    document.getElementById('dropdownMenuBtn').innerText = this.innerText;

                    // Tampilkan teks "Memuat..." sebelum fetch
                    const container = document.getElementById('schedule-container');
                    container.innerHTML = `<div class="text-center py-4">Memuat...</div>`;

                    // Load data via AJAX
                    fetch(url)
                        .then(res => res.json())
                        .then(data => {
                            container.innerHTML = data.html;
                        })
                        .catch(err => {
                            console.error(err);
                            container.innerHTML = `
                            <div class="text-danger text-center py-3">
                                Gagal memuat jadwal.
                            </div>
                        `;
                        });
                });
            });
        });
    </script>

    @if (Auth::user()?->role === 'family_parent')
        <!-- Date and time -->
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const timeEl = document.getElementById("server-time");
                const serverTimestamp = parseInt(timeEl.getAttribute("data-servertime"));

                // Hitung selisih waktu antara server dan client
                const clientTimestamp = new Date().getTime();
                const timeDiff = serverTimestamp - clientTimestamp;

                function updateTime() {
                    // Ambil waktu sekarang + selisih dari server
                    const now = new Date(new Date().getTime() + timeDiff);

                    const days = [
                        'Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'
                    ];
                    const months = [
                        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                    ];

                    const day = days[now.getDay()];
                    const date = now.getDate();
                    const month = months[now.getMonth()];
                    const year = now.getFullYear();

                    const hours = String(now.getHours()).padStart(2, '0');
                    const minutes = String(now.getMinutes()).padStart(2, '0');
                    const seconds = String(now.getSeconds()).padStart(2, '0');

                    const formatted = `${day}, ${date} ${month} ${year} | ${hours}:${minutes}:${seconds}`;
                    timeEl.textContent = formatted;
                }

                // Update setiap detik
                updateTime();
                setInterval(updateTime, 1000);
            });
        </script>

        <!-- Chart -->
        <script>
            $(document).ready(function() {
                function initChart({
                    selectId,
                    canvasId,
                    endpointUrl,
                    totalElementId,
                    chartLabel,
                    backgroundColor,
                    borderColor,
                    pointBorderColor
                }) {
                    const selectElem = $(`#${selectId}`);
                    selectElem.select2();

                    const canvas = document.getElementById(canvasId);
                    const ctx = canvas.getContext("2d");

                    const monthLabels = [
                        "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                        "Juli", "Agustus", "September", "Oktober", "November", "Desember"
                    ];

                    const chart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: monthLabels,
                            datasets: [{
                                label: chartLabel,
                                data: [],
                                borderWidth: 3,
                                borderColor: borderColor,
                                backgroundColor: backgroundColor,
                                pointBackgroundColor: '#fff',
                                pointBorderColor: pointBorderColor,
                                pointRadius: 4
                            }]
                        },
                        options: {
                            responsive: true,
                            legend: {
                                display: false
                            },
                            scales: {
                                yAxes: [{
                                    ticks: {
                                        beginAtZero: true,
                                        precision: 0,
                                        stepSize: 1,
                                        min: 0,
                                    },
                                    scaleLabel: {
                                        display: true,
                                        labelString: 'Jumlah Pemeriksaan'
                                    }
                                }],
                                xAxes: [{
                                    gridLines: {
                                        display: false,
                                        drawBorder: false,
                                    },
                                    scaleLabel: {
                                        display: true,
                                        labelString: 'Bulan'
                                    }
                                }],
                            }
                        }
                    });

                    function fetchData(year) {
                        canvas.style.position = 'relative';
                        canvas.innerHTML =
                            `<div style="position:absolute; top:50%; left:50%; transform: translate(-50%, -50%); font-size:18px;">Memuat...</div>`;

                        fetch(`${endpointUrl}/${year}`)
                            .then(response => response.json())
                            .then(data => {
                                chart.data.datasets[0].data = [...data.data];
                                chart.update();
                                document.getElementById(totalElementId).innerText = data.total || "0";
                            })
                            .catch(error => {
                                console.error("Gagal memuat data:", error);
                            })
                            .finally(() => {
                                canvas.innerHTML = '';
                            });
                    }

                    // Load data awal
                    const initialYear = selectElem.val();
                    fetchData(initialYear);

                    // Update saat dropdown berubah
                    selectElem.on('change', function() {
                        const selectedYear = $(this).val();
                        fetchData(selectedYear);
                    });
                }

                //  ==============================

                // Inisialisasi chart
                initChart({
                    selectId: 'pregnancyCheckYear',
                    canvasId: 'pregnancyCheckChart',
                    endpointUrl: "{{ url('/pregnancy-check-data/ajax') }}",
                    totalElementId: 'number_of_pregnancy_check',
                    chartLabel: 'Jumlah Pemeriksaan',
                    borderColor: 'fuchsia',
                    backgroundColor: 'rgba(255, 0, 255, 0.2)',
                    pointBorderColor: 'fuchsia'
                });
            });
        </script>
        <script>
            $(document).ready(function() {
                const nutritionStatusMap = {
                    'Buruk': 0,
                    'Kurang': 1,
                    'Baik': 2,
                    'Lebih': 3
                };
                const nutritionStatusLabel = ['Buruk', 'Kurang', 'Baik', 'Lebih'];
                const nutritionStatusColors = ['#ff6384', '#ffcd56', '#4bc0c0', '#999999'];

                function getColorFromId(id) {
                    const colors = ['#e74c3c', '#3498db', '#2ecc71', '#9b59b6', '#f39c12', '#1abc9c', '#34495e'];
                    return colors[id % colors.length];
                }

                $('.nutrition-chart-canvas').each(function() {
                    const canvasId = $(this).attr('id');
                    const childrenId = canvasId.replace('nutritionStatusChart', '');
                    const selectId = 'weighingYear' + childrenId;

                    const randomColor = getColorFromId(parseInt(childrenId));
                    const badge = $(`#badge_style${childrenId}`);
                    badge.css({
                        'background-color': randomColor,
                        'color': '#fff',
                        'font-weight': '600'
                    });

                    // Inisialisasi chart
                    initNutritionStatusChart({
                        selectId: selectId,
                        canvasId: canvasId,
                        endpointUrl: "{{ url('/children-nutrition-status/ajax') }}",
                        borderColor: randomColor,
                        backgroundColor: hexToRGBA(randomColor, 0.2),
                        pointBorderColor: randomColor,
                        childrenId: childrenId
                    });
                });

                function initNutritionStatusChart({
                    selectId,
                    canvasId,
                    endpointUrl,
                    backgroundColor,
                    borderColor,
                    pointBorderColor,
                    childrenId
                }) {
                    const selectElem = $(`#${selectId}`);
                    const canvas = document.getElementById(canvasId);
                    const ctx = canvas.getContext("2d");

                    const chart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: [],
                            datasets: [{
                                label: 'Status Gizi',
                                data: [],
                                borderWidth: 3,
                                borderColor: borderColor,
                                backgroundColor: backgroundColor,
                                pointBackgroundColor: '#fff',
                                pointBorderColor: pointBorderColor,
                                pointRadius: 5,
                                fill: false,
                                tension: 0.3
                            }]
                        },
                        options: {
                            responsive: true,
                            legend: {
                                display: false
                            },
                            tooltips: {
                                callbacks: {
                                    label: function(tooltipItem, data) {
                                        const point = data.datasets[tooltipItem.datasetIndex].data[
                                            tooltipItem.index];
                                        return [
                                            `Status Gizi: ${point.status}`,
                                            `Usia: ${point.age}`
                                        ];
                                    }
                                }
                            },
                            scales: {
                                yAxes: [{
                                    ticks: {
                                        beginAtZero: true,
                                        stepSize: 1,
                                        min: 0,
                                        max: 3,
                                        callback: function(value) {
                                            const labels = ['Buruk', 'Kurang', 'Baik', 'Lebih'];
                                            return labels[value] !== undefined ? labels[value] :
                                                value;
                                        }
                                    },
                                    scaleLabel: {
                                        display: true,
                                        labelString: 'Status Gizi'
                                    }
                                }],
                                xAxes: [{
                                    gridLines: {
                                        display: false,
                                        drawBorder: false,
                                    },
                                    scaleLabel: {
                                        display: true,
                                        labelString: 'Tanggal Penimbangan'
                                    }
                                }]
                            }
                        }
                    });

                    // Fetch data awal
                    fetchData(selectElem.val());

                    // Re-fetch saat tahun diganti
                    selectElem.select2();
                    selectElem.on('change', function() {
                        const selectedYear = $(this).val();
                        fetchData(selectedYear);
                    });

                    // Ambil dan isi data chart
                    function fetchData(year = null) {
                        canvas.style.position = 'relative';
                        canvas.innerHTML =
                            `<div style="position:absolute; top:50%; left:50%; transform: translate(-50%, -50%); font-size:18px;">Memuat...</div>`;

                        const url = `${endpointUrl}/${year}`;
                        console.log(`Memuat data: ${url}`);

                        fetch(url)
                            .then(response => response.json())
                            .then(data => {
                                const filtered = data.data.filter(item => item.children_id == childrenId);

                                // Urutkan berdasarkan tanggal
                                filtered.sort((a, b) => new Date(a.weighing_date) - new Date(b.weighing_date));

                                const chartData = filtered.map(item => {
                                    const date = new Date(item.weighing_date);
                                    const formattedDate = formatIndonesianDate(date);
                                    const yVal = nutritionStatusMap[item.nutrition_status];

                                    $(`#children_fullname${childrenId}`).text(item.fullname);

                                    return {
                                        x: formattedDate,
                                        y: yVal,
                                        status: item.nutrition_status,
                                        age: item.age_in_checks,
                                        formattedDate: formattedDate
                                    };
                                });

                                chart.data.labels = chartData.map(d => d.x);
                                chart.data.datasets[0].data = chartData;
                                chart.update();
                            })
                            .catch(error => {
                                console.error("Gagal memuat data:", error);
                            })
                            .finally(() => {
                                canvas.innerHTML = '';
                            });
                    }
                }

                // Utility warna transparan
                function hexToRGBA(hex, alpha) {
                    const r = parseInt(hex.slice(1, 3), 16);
                    const g = parseInt(hex.slice(3, 5), 16);
                    const b = parseInt(hex.slice(5, 7), 16);
                    return `rgba(${r}, ${g}, ${b}, ${alpha})`;
                }

                // Format tanggal ke format Indonesia
                function formatIndonesianDate(date) {
                    const monthNames = [
                        "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                        "Juli", "Agustus", "September", "Oktober", "November", "Desember"
                    ];
                    const day = date.getDate();
                    const monthIndex = date.getMonth();
                    const year = date.getFullYear();

                    return `${day} ${monthNames[monthIndex]} ${year}`;
                }
            });
        </script>
    @else
        <!-- Chart -->
        <script>
            $(document).ready(function() {
                function initChart({
                    selectId,
                    canvasId,
                    endpointUrl,
                    totalElementId,
                    chartLabel,
                    backgroundColor,
                    borderColor,
                    pointBorderColor
                }) {
                    const selectElem = $(`#${selectId}`);
                    selectElem.select2();

                    const canvas = document.getElementById(canvasId);
                    const ctx = canvas.getContext("2d");

                    const monthLabels = [
                        "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                        "Juli", "Agustus", "September", "Oktober", "November", "Desember"
                    ];

                    const chart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: monthLabels,
                            datasets: [{
                                label: chartLabel,
                                data: [],
                                borderWidth: 3,
                                borderColor: borderColor,
                                backgroundColor: backgroundColor,
                                pointBackgroundColor: '#fff',
                                pointBorderColor: pointBorderColor,
                                pointRadius: 4
                            }]
                        },
                        options: {
                            responsive: true,
                            legend: {
                                display: false
                            },
                            scales: {
                                yAxes: [{
                                    ticks: {
                                        beginAtZero: true,
                                        precision: 0,
                                        stepSize: 1,
                                        min: 0,
                                    },
                                    scaleLabel: {
                                        display: true,
                                        labelString: chartLabel
                                    }
                                }],
                                xAxes: [{
                                    gridLines: {
                                        display: false,
                                        drawBorder: false,
                                    },
                                    scaleLabel: {
                                        display: true,
                                        labelString: 'Bulan'
                                    }
                                }],
                            }
                        }
                    });

                    function fetchData(year) {
                        canvas.style.position = 'relative';
                        canvas.innerHTML =
                            `<div style="position:absolute; top:50%; left:50%; transform: translate(-50%, -50%); font-size:18px;">Memuat...</div>`;

                        fetch(`${endpointUrl}/${year}`)
                            .then(response => response.json())
                            .then(data => {
                                chart.data.datasets[0].data = [...data.data];
                                chart.update();
                                document.getElementById(totalElementId).innerText = data.total || "N/A";
                            })
                            .catch(error => {
                                console.error("Gagal memuat data:", error);
                            })
                            .finally(() => {
                                canvas.innerHTML = '';
                            });
                    }

                    // Load data awal
                    const initialYear = selectElem.val();
                    fetchData(initialYear);

                    // Update saat dropdown berubah
                    selectElem.on('change', function() {
                        const selectedYear = $(this).val();
                        fetchData(selectedYear);
                    });
                }

                function initBarChartMultipleDatasets({
                    selectId,
                    canvasId,
                    endpointUrl
                }) {
                    const selectElem = $(`#${selectId}`);
                    selectElem.select2();

                    const canvas = document.getElementById(canvasId);
                    const ctx = canvas.getContext("2d");

                    const monthLabels = [
                        "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                        "Juli", "Agustus", "September", "Oktober", "November", "Desember"
                    ];

                    const statusLabels = ["Baik", "Buruk", "Kurang", "Lebih"];

                    const colors = {
                        Baik: "rgba(76, 175, 80, 0.5)", // Hijau, status gizi baik
                        Buruk: "rgba(244, 67, 54, 0.5)", // Merah, status gizi buruk
                        Kurang: "rgba(255, 152, 0, 0.5)", // Oranye, status gizi kurang
                        Lebih: "rgba(33, 150, 243, 0.5)" // Biru, status gizi lebih
                    };

                    const chart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: monthLabels,
                            datasets: statusLabels.map(label => ({
                                label: label,
                                data: [],
                                backgroundColor: colors[label]
                            }))
                        },
                        options: {
                            responsive: true,
                            title: {
                                display: false,
                                text: 'Statistik Status Gizi per Bulan'
                            },
                            legend: {
                                display: true,
                                position: 'top'
                            },
                            tooltips: {
                                mode: 'index',
                                intersect: false
                            },
                            scales: {
                                xAxes: [{
                                    stacked: false,
                                    scaleLabel: {
                                        display: true,
                                        labelString: 'Bulan'
                                    }
                                }],
                                yAxes: [{
                                    stacked: false,
                                    ticks: {
                                        beginAtZero: true,
                                        stepSize: 1,
                                        precision: 0
                                    },
                                    scaleLabel: {
                                        display: true,
                                        labelString: 'Jumlah Anak'
                                    }
                                }]
                            }
                        }
                    });

                    function calculatePercentage(count, total) {
                        if (total === 0) return "0%";
                        const percentage = (count / total) * 100;
                        return percentage % 1 === 0 ? `${percentage.toFixed(0)}%` : `${percentage.toFixed(1)}%`;
                    }

                    function fetchData(year) {
                        canvas.style.position = 'relative';
                        canvas.innerHTML =
                            `<div style="position:absolute; top:50%; left:50%; transform: translate(-50%, -50%); font-size:18px;">Memuat...</div>`;

                        fetch(`${endpointUrl}/${year}`)
                            .then(response => response.json())
                            .then(data => {
                                // Update data chart per status dan per bulan
                                statusLabels.forEach((status, i) => {
                                    chart.data.datasets[i].data = [];
                                    for (let month = 1; month <= 12; month++) {
                                        chart.data.datasets[i].data.push(parseInt(data.data[month][
                                            status
                                        ]) || 0);
                                    }
                                });
                                chart.update();

                                // Update total anak per status beserta persentasenya
                                const totalGood = data.total?.Baik ?? 0;
                                const totalPoor = data.total?.Buruk ?? 0;
                                const totalLack = data.total?.Kurang ?? 0;
                                const totalExcess = data.total?.Lebih ?? 0;
                                const totalChildren = totalGood + totalPoor + totalLack + totalExcess;

                                document.getElementById("total_good").innerText = totalGood;
                                document.getElementById("total_poor").innerText = totalPoor;
                                document.getElementById("total_lack").innerText = totalLack;
                                document.getElementById("total_excess").innerText = totalExcess;

                                document.getElementById("percentage_good").innerText = calculatePercentage(
                                    totalGood, totalChildren);
                                document.getElementById("percentage_poor").innerText = calculatePercentage(
                                    totalPoor, totalChildren);
                                document.getElementById("percentage_lack").innerText = calculatePercentage(
                                    totalLack, totalChildren);
                                document.getElementById("percentage_excess").innerText = calculatePercentage(
                                    totalExcess, totalChildren);
                            })
                            .catch(error => {
                                console.error("Gagal memuat data:", error);
                            }).finally(() => {
                                canvas.innerHTML = '';
                            });
                    }

                    // Load data awal
                    const initialYear = selectElem.val();
                    fetchData(initialYear);

                    // Update saat dropdown berubah
                    selectElem.on('change', function() {
                        const selectedYear = $(this).val();
                        fetchData(selectedYear);
                    });
                }

                //  ==============================

                // Inisialisasi untuk semua chart
                initBarChartMultipleDatasets({
                    selectId: 'nutritionStatusYear',
                    canvasId: 'nutritionStatusChart',
                    endpointUrl: "{{ url('/nutrition-status/ajax') }}"
                });


                initChart({
                    selectId: 'immunizationYear',
                    canvasId: 'immunizationChart',
                    endpointUrl: "{{ url('/immunization-data/ajax') }}",
                    totalElementId: 'number_of_child_immunization',
                    chartLabel: 'Jumlah Anak',
                    borderColor: 'tomato',
                    backgroundColor: 'rgba(255, 99, 71, 0.2)',
                    pointBorderColor: 'tomato'
                });

                initChart({
                    selectId: 'weighingYear',
                    canvasId: 'weighingChart',
                    endpointUrl: "{{ url('/weighing-data/ajax') }}",
                    totalElementId: 'number_of_child_weighing',
                    chartLabel: 'Jumlah Anak',
                    borderColor: '#6777ef',
                    backgroundColor: 'rgba(103,119,239,0.1)',
                    pointBorderColor: '#6777ef'
                });

                initChart({
                    selectId: 'pregnancyCheckYear',
                    canvasId: 'pregnancyCheckChart',
                    endpointUrl: "{{ url('/pregnancy-check-data/ajax') }}",
                    totalElementId: 'number_of_pregnancy_check',
                    chartLabel: 'Jumlah Ibu Hamil',
                    borderColor: 'fuchsia',
                    backgroundColor: 'rgba(255, 0, 255, 0.2)',
                    pointBorderColor: 'fuchsia'
                });

                initChart({
                    selectId: 'elderlyCheckYear',
                    canvasId: 'elderlyCheckChart',
                    endpointUrl: "{{ url('/elderly-check-data/ajax') }}",
                    totalElementId: 'number_of_elderly_check',
                    chartLabel: 'Jumlah Lansia',
                    borderColor: 'darksalmon',
                    backgroundColor: 'rgba(233, 150, 122, 0.2)',
                    pointBorderColor: 'darksalmon'
                });
            });
        </script>
    @endif
@endpush
