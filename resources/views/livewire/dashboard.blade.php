<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8 font-sans">
    <div class="bg-white shadow-xl sm:rounded-lg p-6">
        <h2 class="text-2xl font-bold text-gray-800 border-b pb-4 mb-6">Grafik Arus Kas Tahun {{ $year }}</h2>

        <div class="w-full relative" style="height: 500px;">
            <canvas id="cashFlowChart"></canvas>
        </div>
    </div>

    <!-- Script Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Inisialisasi Chart via Alpine untuk memastikan ter-render setiap navigasi -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            const renderChart = () => {
                const ctx = document.getElementById('cashFlowChart');
                if (!ctx) return;
                
                // Destroy previous instance if exists to prevent overlapping on Livewire navigation
                if (window.myChart) {
                    window.myChart.destroy();
                }

                window.myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: {!! $labels !!},
                        datasets: [
                            {
                                label: 'Pemasukan',
                                data: {!! $incomes !!},
                                backgroundColor: 'rgba(34, 197, 94, 0.8)',
                                borderColor: 'rgb(21, 128, 61)',
                                borderWidth: 1,
                                borderRadius: 6,
                            },
                            {
                                label: 'Pengeluaran',
                                data: {!! $expenses !!},
                                backgroundColor: 'rgba(239, 68, 68, 0.8)',
                                borderColor: 'rgb(185, 28, 28)',
                                borderWidth: 1,
                                borderRadius: 6,
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            mode: 'index',
                            intersect: false,
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: '#e5e7eb',
                                },
                                ticks: {
                                    callback: function(value) {
                                        if (value >= 1000000) {
                                            return 'Rp ' + (value / 1000000) + ' Jt';
                                        } else if (value >= 1000) {
                                            return 'Rp ' + (value / 1000) + ' rb';
                                        }
                                        return 'Rp ' + value;
                                    }
                                }
                            }
                        },
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        if (context.parsed.y !== null) {
                                            label += 'Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                                        }
                                        return label;
                                    }
                                }
                            },
                            legend: {
                                labels: {
                                    font: {
                                        family: 'ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif',
                                        size: 14
                                    }
                                }
                            }
                        }
                    }
                });
            };

            renderChart();
        });
        
        // Also render if livewire:navigated is fired (Livewire 3 wire:navigate support)
        document.addEventListener('livewire:navigated', () => {
            if(document.getElementById('cashFlowChart') && window.Chart) {
                // If chart.js is already loaded, we dispatch an event to trigger render again
                const event = new Event('render-chart-now');
                document.dispatchEvent(event);
            }
        });

        document.addEventListener('render-chart-now', () => {
             const ctx = document.getElementById('cashFlowChart');
             if(ctx && window.Chart) {
                 if (window.myChart) window.myChart.destroy();
                 
                 window.myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: {!! $labels !!},
                        datasets: [
                            {
                                label: 'Pemasukan',
                                data: {!! $incomes !!},
                                backgroundColor: 'rgba(34, 197, 94, 0.8)',
                                borderColor: 'rgb(21, 128, 61)',
                                borderWidth: 1,
                                borderRadius: 6,
                            },
                            {
                                label: 'Pengeluaran',
                                data: {!! $expenses !!},
                                backgroundColor: 'rgba(239, 68, 68, 0.8)',
                                borderColor: 'rgb(185, 28, 28)',
                                borderWidth: 1,
                                borderRadius: 6,
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            mode: 'index',
                            intersect: false,
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: '#e5e7eb',
                                },
                                ticks: {
                                    callback: function(value) {
                                        if (value >= 1000000) {
                                            return 'Rp ' + (value / 1000000) + ' Jt';
                                        } else if (value >= 1000) {
                                            return 'Rp ' + (value / 1000) + ' rb';
                                        }
                                        return 'Rp ' + value;
                                    }
                                }
                            }
                        },
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        if (context.parsed.y !== null) {
                                            label += 'Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                                        }
                                        return label;
                                    }
                                }
                            }
                        }
                    }
                });
             }
        });
    </script>
</div>
