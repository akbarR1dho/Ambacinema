@props([
    'id',
    'name' => null,
    'apiUrl',
    'defaultLabel' => 'Select an option',
    'placeholder' => 'Search...'
])

@php
    $name = $name ?? $id;
@endphp

<div class="relative w-full sm:min-w-[12rem] sm:w-auto infinite-select-container" data-api-url="{{ $apiUrl }}">
    <input type="hidden" id="{{ $id }}" name="{{ $name }}" class="infinite-select-input" value="">
    <button type="button" class="infinite-select-btn bg-white border border-slate-300 rounded-lg py-2 px-3 text-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 w-full flex justify-between items-center text-slate-700">
        <span class="infinite-select-label truncate">{{ $defaultLabel }}</span>
        <svg class="w-4 h-4 text-slate-400 flex-shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
    </button>
    <div class="infinite-select-dropdown absolute z-50 w-full mt-1 bg-white border border-slate-300 rounded-lg shadow-lg hidden">
        <div class="p-2 border-b border-slate-100">
            <input type="text" class="infinite-select-search w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500" placeholder="{{ $placeholder }}">
        </div>
        <ul class="infinite-select-list max-h-40 overflow-y-auto py-1">
            <li class="px-4 py-2 hover:bg-blue-50 cursor-pointer text-sm text-slate-700 font-medium dropdown-item" data-value="" data-name="{{ $defaultLabel }}">
                {{ $defaultLabel }}
            </li>
            <li class="loading-sentinel px-3 py-2 text-sm text-center text-slate-500 hidden">
                {{ __('Loading...') }}
            </li>
        </ul>
    </div>
</div>

@once
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.infinite-select-container').forEach(container => {
            const btn = container.querySelector('.infinite-select-btn');
            const dropdown = container.querySelector('.infinite-select-dropdown');
            const searchInput = container.querySelector('.infinite-select-search');
            const list = container.querySelector('.infinite-select-list');
            const sentinel = container.querySelector('.loading-sentinel');
            const label = container.querySelector('.infinite-select-label');
            const hiddenInput = container.querySelector('.infinite-select-input');
            const apiUrl = container.dataset.apiUrl;
            
            let nextCursor = null;
            let isLoading = false;
            let searchQuery = '';
            let initialLoaded = false;
            
            btn.addEventListener('click', () => {
                dropdown.classList.toggle('hidden');
                if (!dropdown.classList.contains('hidden')) {
                    searchInput.focus();
                    if (!initialLoaded) {
                        fetchData(true);
                        initialLoaded = true;
                    }
                }
            });

            document.addEventListener('click', (e) => {
                if (!container.contains(e.target)) {
                    dropdown.classList.add('hidden');
                }
            });
            
            let searchTimeout;
            searchInput.addEventListener('input', (e) => {
                clearTimeout(searchTimeout);
                searchQuery = e.target.value;
                searchTimeout = setTimeout(() => {
                    fetchData(true);
                }, 500);
            });

            function fetchData(reset = false) {
                if (isLoading) return;
                if (reset) {
                    nextCursor = null;
                    list.querySelectorAll('.dynamic-item').forEach(el => el.remove());
                }
                
                isLoading = true;
                sentinel.classList.remove('hidden');
                
                const url = new URL(apiUrl, window.location.origin);
                if (searchQuery) url.searchParams.append('search', searchQuery);
                if (nextCursor) url.searchParams.append('cursor', nextCursor);
                
                fetch(url)
                    .then(res => res.json())
                    .then(data => {
                        data.data.forEach(item => {
                            const li = document.createElement('li');
                            li.className = 'px-3 py-2 text-sm hover:bg-blue-50 cursor-pointer text-slate-700 dynamic-item dropdown-item';
                            li.dataset.value = item.id;
                            li.dataset.name = item.name;
                            li.textContent = item.name;
                            list.insertBefore(li, sentinel);
                        });
                        
                        nextCursor = data.next_cursor;
                        isLoading = false;
                        
                        if (!nextCursor) {
                            sentinel.classList.add('hidden');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        isLoading = false;
                        sentinel.classList.add('hidden');
                    });
            }

            const observer = new IntersectionObserver((entries) => {
                if (entries[0].isIntersecting && nextCursor && !isLoading) {
                    fetchData();
                }
            }, {
                root: list,
                threshold: 1.0
            });
            observer.observe(sentinel);

            list.addEventListener('click', (e) => {
                const item = e.target.closest('.dropdown-item');
                if (item) {
                    hiddenInput.value = item.dataset.value;
                    label.textContent = item.dataset.name;
                    dropdown.classList.add('hidden');
                    
                    // Trigger native change event for listeners
                    const event = new Event('change', { bubbles: true });
                    hiddenInput.dispatchEvent(event);
                }
            });
        });
    });
</script>
@endpush
@endonce
