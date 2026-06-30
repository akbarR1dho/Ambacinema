@extends('layouts.admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-slate-900">{{ __('Dashboard Overview') }}</h2>
        <p class="text-sm text-slate-500">{{ __('Welcome to the Ambacinema administration panel.') }}</p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Stat Card 1 -->
    <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">{{ __('Total Studios') }}</p>
                <p class="text-3xl font-bold text-slate-900 mt-1">{{ \App\Models\Studio::count() }}</p>
            </div>
            <div class="p-3 bg-blue-100 rounded-lg text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
        </div>
    </div>

    <!-- Stat Card 2 -->
    <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">{{ __('Total Movies') }}</p>
                <p class="text-3xl font-bold text-slate-900 mt-1">{{ \App\Models\Movie::count() }}</p>
            </div>
            <div class="p-3 bg-blue-100 rounded-lg text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Stat Card 3 -->
    <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">{{ __('Total Showtimes') }}</p>
                <p class="text-3xl font-bold text-slate-900 mt-1">{{ \App\Models\Showtime::count() }}</p>
            </div>
            <div class="p-3 bg-blue-100 rounded-lg text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Stat Card 4 -->
    <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">{{ __('Total Orders') }}</p>
                <p class="text-3xl font-bold text-slate-900 mt-1">{{ \App\Models\Order::count() }}</p>
            </div>
            <div class="p-3 bg-blue-100 rounded-lg text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
            </div>
        </div>
    </div>
</div>

<div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm mb-8">
    <div class="mb-4 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
        <h3 class="text-lg font-bold text-slate-900">{{ __('Revenue Chart') }}</h3>
        <div class="flex flex-col sm:flex-row flex-wrap gap-4 items-start sm:items-end w-full lg:w-auto">
            <div class="w-full sm:w-auto">
                <label for="filter-date" class="block text-xs font-medium text-slate-500 mb-1">{{ __('Time Range') }}</label>
                <select id="filter-date" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 sm:min-w-[150px]">
                    <option value="">{{ __('All Time') }}</option>
                    <option value="today">{{ __('Today') }}</option>
                    <option value="weekly">{{ __('This Week') }}</option>
                    <option value="monthly">{{ __('This Month') }}</option>
                    <option value="annual">{{ __('This Year') }}</option>
                </select>
            </div>
            
            <div class="w-full sm:w-auto flex-1">
                <label for="filter-studio" class="block text-xs font-medium text-slate-500 mb-1">{{ __('Studio') }}</label>
                <x-infinite-select 
                    id="filter-studio" 
                    api-url="{{ route('admin.api.studios') }}" 
                    default-label="{{ __('All Studios') }}" 
                    placeholder="{{ __('Search studio...') }}"
                />
            </div>

            <div class="w-full sm:w-auto flex-1">
                <label for="filter-movie" class="block text-xs font-medium text-slate-500 mb-1">{{ __('Movie') }}</label>
                <x-infinite-select 
                    id="filter-movie" 
                    api-url="{{ route('admin.api.movies') }}" 
                    default-label="{{ __('All Movies') }}" 
                    placeholder="{{ __('Search movie...') }}"
                />
            </div>

            <div class="flex gap-2 w-full sm:w-auto mt-2 sm:mt-0">
                <button id="btn-reset-filters" class="w-full sm:w-auto px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-sm font-medium rounded-lg transition-colors shadow-sm">{{ __('Reset') }}</button>
            </div>
        </div>
    </div>
    <div class="relative h-64 sm:h-80 lg:h-[400px] w-full">
        <canvas id="revenueChart"></canvas>
    </div>
    <div class="mt-6 pt-4 border-t border-slate-100 flex justify-end">
        <div class="text-right">
            <p class="text-sm font-medium text-slate-500">{{ __('Total Period Revenue') }}</p>
            <p id="chart-total-revenue" class="text-3xl font-bold text-blue-600 mt-1">Rp 0</p>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        let chartInstance = null;

        // Create Gradient for chart
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(37, 99, 235, 0.8)'); // blue-600
        gradient.addColorStop(1, 'rgba(37, 99, 235, 0.1)'); // transparent blue

        function loadChartData() {
            const dateFilter = document.getElementById('filter-date').value;
            const studioId = document.getElementById('filter-studio').value;
            const movieId = document.getElementById('filter-movie').value;
            
            let url = new URL("{{ route('admin.dashboard.chartData') }}");
            if (dateFilter) url.searchParams.append('date_filter', dateFilter);
            if (studioId) url.searchParams.append('studio_id', studioId);
            if (movieId) url.searchParams.append('movie_id', movieId);

            fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                // // --- SIMULASI 30 HARI ---
                // data.labels = [];
                // data.data = [];
                // for(let i=1; i<=30; i++) {
                //     data.labels.push('2026-06-' + String(i).padStart(2, '0'));
                //     data.data.push(Math.floor(Math.random() * 1500000) + 100000);
                // }
                // // ------------------------
                let totalRevenue = 0;
                if (data.data && Array.isArray(data.data)) {
                    totalRevenue = data.data.reduce((sum, current) => sum + Number(current), 0);
                }
                const formattedTotal = new Intl.NumberFormat('id-ID', { 
                    style: 'currency', 
                    currency: 'IDR', 
                    minimumFractionDigits: 0 
                }).format(totalRevenue);
                document.getElementById('chart-total-revenue').innerText = formattedTotal;

                if (chartInstance) {
                    chartInstance.destroy();
                }

                let chartType = dateFilter === 'today' ? 'bar' : 'line';

                chartInstance = new Chart(ctx, {
                    type: chartType,
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Income',
                            data: data.data,
                            backgroundColor: gradient,
                            borderColor: 'rgb(37, 99, 235)', // blue-600
                            borderWidth: 2,
                            fill: true,
                            tension: 0.05,
                            pointRadius: 0,
                            pointHoverRadius: 6,
                            pointHoverBackgroundColor: 'rgb(37, 99, 235)',
                            clip: false
                        }]
                    },
                    options: {
                        layout: {
                            padding: {
                                left: 0,
                                right: 8
                            }
                        },
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            mode: 'index',
                            intersect: false,
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false,
                                    drawBorder: false
                                },
                                ticks: {
                                    display: false
                                }
                            },
                            y: {
                                beginAtZero: true,
                                border: { dash: [4, 4], display: false },
                                grid: {
                                    color: '#e2e8f0',
                                    drawBorder: false
                                },
                                ticks: {
                                    color: '#64748b',
                                    font: { family: "'Inter', sans-serif" },
                                    callback: function(value, index, values) {
                                        return 'Rp ' + (value / 1000) + 'k';
                                    }
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: 'rgba(15, 23, 42, 0.9)',
                                titleColor: '#fff',
                                bodyColor: '#fff',
                                padding: 12,
                                cornerRadius: 8,
                                displayColors: false,
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        if (context.parsed.y !== null) {
                                            label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(context.parsed.y);
                                        }
                                        return label;
                                    }
                                }
                            }
                        }
                    },
                    plugins: [{
                        id: 'crosshairLine',
                        afterDraw: chart => {
                            if (chart.tooltip?._active?.length) {
                                let activePoint = chart.tooltip._active[0];
                                let ctx = chart.ctx;
                                let x = activePoint.element.x;
                                let topY = chart.scales.y.top;
                                let bottomY = chart.scales.y.bottom;
                                
                                ctx.save();
                                ctx.beginPath();
                                ctx.moveTo(x, topY);
                                ctx.lineTo(x, bottomY);
                                ctx.lineWidth = 1;
                                ctx.strokeStyle = '#94a3b8'; // slate-400
                                ctx.setLineDash([4, 4]);
                                ctx.stroke();
                                ctx.restore();
                            }
                        }
                    }]
                });
            })
            .catch(error => console.error('Error fetching chart data:', error));
        }

        // Initial load
        loadChartData();

        const filterIds = ['filter-date', 'filter-studio', 'filter-movie'];

        // Auto-update helper
        function onFilterChange(e) {
            loadChartData();
        }

        filterIds.forEach(id => {
            document.getElementById(id).addEventListener('change', onFilterChange);
        });

        // Reset button
        document.getElementById('btn-reset-filters').addEventListener('click', function() {
            filterIds.forEach(id => {
                const el = document.getElementById(id);
                el.value = '';
                
                if (el.classList.contains('infinite-select-input')) {
                    const container = el.closest('.infinite-select-container');
                    const label = container.querySelector('.infinite-select-label');
                    const defaultItem = container.querySelector('[data-value=""]');
                    if (defaultItem) {
                        label.textContent = defaultItem.dataset.name;
                    }
                }
            });
            loadChartData();
        });
    });
</script>
@endpush
