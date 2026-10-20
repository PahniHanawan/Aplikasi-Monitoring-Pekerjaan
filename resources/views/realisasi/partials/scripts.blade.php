<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
class RealisasiManager {
    constructor() {
        this.currentPage = 1;
        this.isLoading = false;
        this.searchTimeout = null;
        this.autoRefreshInterval = null;
        this.init();
    }

    init() {
        this.bindEvents();
        this.startAutoRefresh();
        this.initializeView();
    }

    bindEvents() {
        // Quick search with debounce
        $('#quick-search').on('input', () => this.debouncedSearch());

        // Filter changes
        $('#status-filter, #kantor-filter, #progress-filter, #bulan-filter, #tahun-filter, #sort-by, #per-page').on('change', () => this.applyFilters());

        // Date range
        $('#start-date, #end-date').on('change', () => this.applyFilters());

        // View toggle
        $('[data-view-type]').on('click', (e) => this.toggleView(e));

        // Auto refresh toggle
        $('#auto-refresh').on('change', (e) => this.toggleAutoRefresh(e.target.checked));


        // Reset
        $('#reset-filters').on('click', () => this.resetFilters());

        // Pagination
        $(document).on('click', '#pagination-container .pagination a', (e) => this.handlePagination(e));

        // Keyboard shortcuts
        $(document).on('keydown', (e) => this.handleKeyboardShortcuts(e));
    }

    debouncedSearch() {
        clearTimeout(this.searchTimeout);
        this.searchTimeout = setTimeout(() => {
            this.applyFilters();
        }, 300);
    }

    async applyFilters(page = 1) {
        if (this.isLoading) return;

        this.isLoading = true;
        this.currentPage = page;

        this.showLoading();

        const filters = this.getCurrentFilters();

        try {
            const response = await $.ajax({
                url: '{{ route("realisasi.index") }}',
                type: 'GET',
                data: { ...filters, page },
                dataType: 'json'
            });

            if (response.success) {
                this.updateUI(response);
                this.updateActiveFilters(filters);
            }
        } catch (error) {
            this.handleError(error);
        } finally {
            this.isLoading = false;
            this.hideLoading();
        }
    }

    getCurrentFilters() {
        return {
            search: $('#quick-search').val(),
            status: $('#status-filter').val(),
            kantor_id: $('#kantor-filter').val(),
            progress_range: $('#progress-filter').val(),
            bulan: $('#bulan-filter').val(),
            tahun: $('#tahun-filter').val(),
            start_date: $('#start-date').val(),
            end_date: $('#end-date').val(),
            sort: $('#sort-by').val(),
            per_page: $('#per-page').val()
        };
    }

    updateUI(response) {
        $('#data-container').html(response.html);
        $('#pagination-container').html(response.pagination || '');
        this.updateStatistics(response.stats);

        // Smooth scroll to top
        $('html, body').animate({
            scrollTop: $('#data-container').offset().top - 100
        }, 500);

        // Update URL without reload
        this.updateURL(response.filters);
    }

    updateStatistics(stats) {
        $('#total-data').text(stats.totalData || 0);
        $('#total-nilai').text('Rp ' + this.formatNumber(stats.totalRealisasi || 0));
        $('#proyek-selesai').text(stats.proyekSelesai || 0);
        $('#proyek-berjalan').text(stats.proyekBerjalan || 0);
        $('#total-badge').text(stats.totalData || 0);
    }

    updateActiveFilters(filters) {
        const activeFilters = [];

        Object.entries(filters).forEach(([key, value]) => {
            if (value && key !== 'per_page' && key !== 'sort') {
                activeFilters.push(`${this.getFilterLabel(key)}: ${value}`);
            }
        });

        if (activeFilters.length > 0) {
            $('#active-filters').show();
            $('#filters-text').html('<strong>Filter Aktif:</strong> ' + activeFilters.join(' | '));
            $('#results-count').text(filters.search_count + ' data');
        } else {
            $('#active-filters').hide();
        }
    }

    getFilterLabel(key) {
        const labels = {
            search: 'Pencarian',
            status: 'Status',
            kantor_id: 'Kantor',
            progress_range: 'Progress',
            bulan: 'Bulan',
            tahun: 'Tahun',
            start_date: 'Dari Tanggal',
            end_date: 'Sampai Tanggal'
        };
        return labels[key] || key;
    }

    formatNumber(number) {
        return new Intl.NumberFormat('id-ID').format(number);
    }

    showLoading() {
        $('#loading-indicator').show();
        $('#data-container').addClass('opacity-50');
    }

    hideLoading() {
        $('#loading-indicator').hide();
        $('#data-container').removeClass('opacity-50');
    }

    toggleView(e) {
        const viewType = $(e.currentTarget).data('view-type');

        $('[data-view-type]').removeClass('active').addClass('btn-outline-light');
        $(e.currentTarget).addClass('active').removeClass('btn-outline-light');

        // Implement view switching logic here
        this.switchView(viewType);
    }

    switchView(viewType) {
        // Add logic to switch between table and card views
        if (viewType === 'card') {
            $('#data-container').addClass('card-view');
            // Load card view via AJAX
        } else {
            $('#data-container').removeClass('card-view');
            // Load table view
        }
    }

    startAutoRefresh() {
        if ($('#auto-refresh').is(':checked')) {
            this.autoRefreshInterval = setInterval(() => {
                this.applyFilters(this.currentPage);
            }, 30000); // Refresh every 30 seconds
        }
    }

    toggleAutoRefresh(enabled) {
        clearInterval(this.autoRefreshInterval);
        if (enabled) {
            this.startAutoRefresh();
        }
    }

    handlePagination(e) {
        e.preventDefault();
        const url = $(e.target).attr('href');
        const page = new URL(url).searchParams.get('page');
        this.applyFilters(page);
    }

    handleKeyboardShortcuts(e) {
        // Ctrl+F for search
        if (e.ctrlKey && e.key === 'f') {
            e.preventDefault();
            $('#quick-search').focus();
        }

        // Escape to clear search
        if (e.key === 'Escape') {
            $('#quick-search').val('').trigger('input');
        }
    }

    resetFilters() {
        $('#quick-search, #status-filter, #kantor-filter, #progress-filter, #bulan-filter, #tahun-filter, #start-date, #end-date, #sort-by').val('');
        $('#per-page').val('100');
        this.applyFilters(1);
    }

    updateURL(filters) {
        const url = new URL(window.location);
        Object.entries(filters).forEach(([key, value]) => {
            if (value) {
                url.searchParams.set(key, value);
            } else {
                url.searchParams.delete(key);
            }
        });
        window.history.replaceState({}, '', url);
    }

    handleError(error) {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Gagal memuat data: ' + (error.responseJSON?.message || error.statusText)
        });
    }
}

// Initialize when document is ready
$(document).ready(() => {
    window.realisasiManager = new RealisasiManager();
});

function confirmDelete(id, nomorSpk) {
    Swal.fire({
        title: "Konfirmasi Hapus",
        html: `Apakah Anda yakin ingin menghapus realisasi untuk kontrak <strong>${nomorSpk}</strong>?`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#dc3545",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "Ya, Hapus",
        cancelButtonText: "Batal"
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + id).submit();
        }
    });
}
</script>

<style>
/* Enhanced Styling */
#data-container {
    transition: all 0.3s ease;
}

#data-container.opacity-50 {
    opacity: 0.5;
    pointer-events: none;
}

.table-responsive {
    max-height: 70vh;
    border: 1px solid #e9ecef;
    border-radius: 0.5rem;
}

.sticky-top {
    position: sticky;
    top: 0;
    background: #f8f9fa;
    z-index: 10;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* Custom scrollbar */
.table-responsive::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

.table-responsive::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

.table-responsive::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 4px;
}

.table-responsive::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Progress bar enhancements */
.progress {
    height: 20px;
    border-radius: 10px;
    overflow: hidden;
}

.progress-bar {
    transition: width 0.6s ease;
    font-weight: 600;
}

/* Card view styling */
.card-view .realisasi-card {
    border: 1px solid #e9ecef;
    border-radius: 0.5rem;
    margin-bottom: 1rem;
    transition: all 0.3s ease;
}

.card-view .realisasi-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

/* Responsive improvements */
@media (max-width: 768px) {
    .table-responsive {
        max-height: 60vh;
        font-size: 0.875rem;
    }

    .stats-cards .col-md-2 {
        margin-bottom: 0.5rem;
    }

    .btn-group-sm > .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
}

/* Animation for new rows */
@keyframes highlightRow {
    0% { background-color: #d1e7dd; }
    100% { background-color: transparent; }
}

.table-highlight {
    animation: highlightRow 2s ease;
}

/* Loading animation */
.spinner-border {
    width: 2rem;
    height: 2rem;
}

/* Badge enhancements */
.badge {
    font-size: 0.7em;
    font-weight: 500;
}

/* Filter section styling */
#advancedFilters .form-select-sm {
    font-size: 0.875rem;
}
</style>
