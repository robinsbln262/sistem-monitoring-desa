@if ($paginator->hasPages())

    <nav
        role="navigation"
        aria-label="Pagination Navigation"
        class="d-flex flex-wrap justify-content-between align-items-center gap-3"
    >

        {{-- =====================================================
             INFO: Menampilkan X - Y dari Z
        ====================================================== --}}

        <div class="text-muted small">

            Menampilkan

            @if ($paginator->firstItem())

                <strong>{{ $paginator->firstItem() }}</strong>

                -

                <strong>{{ $paginator->lastItem() }}</strong>

            @else

                {{ $paginator->count() }}

            @endif

            dari

            <strong>{{ $paginator->total() }}</strong>

            data

        </div>


        {{-- =====================================================
             PAGINATION BUTTONS
        ====================================================== --}}

        <ul class="pagination pagination-sm mb-0 custom-pagination">

            {{-- PREVIOUS --}}

            @if ($paginator->onFirstPage())

                <li class="page-item disabled" aria-disabled="true">

                    <span class="page-link">

                        <i class="fas fa-chevron-left"></i>

                        <span class="d-none d-sm-inline ms-1">Sebelumnya</span>

                    </span>

                </li>

            @else

                <li class="page-item">

                    <a
                        href="{{ $paginator->previousPageUrl() }}"
                        class="page-link"
                        rel="prev"
                        aria-label="Sebelumnya"
                    >

                        <i class="fas fa-chevron-left"></i>

                        <span class="d-none d-sm-inline ms-1">Sebelumnya</span>

                    </a>

                </li>

            @endif


            {{-- PAGE NUMBERS --}}

            @foreach ($elements as $element)

                {{-- "..." Separator --}}

                @if (is_string($element))

                    <li class="page-item disabled" aria-disabled="true">

                        <span class="page-link">{{ $element }}</span>

                    </li>

                @endif


                {{-- Array of Links --}}

                @if (is_array($element))

                    @foreach ($element as $page => $url)

                        @if ($page == $paginator->currentPage())

                            <li class="page-item active" aria-current="page">

                                <span class="page-link">{{ $page }}</span>

                            </li>

                        @else

                            <li class="page-item">

                                <a
                                    href="{{ $url }}"
                                    class="page-link"
                                >{{ $page }}</a>

                            </li>

                        @endif

                    @endforeach

                @endif

            @endforeach


            {{-- NEXT --}}

            @if ($paginator->hasMorePages())

                <li class="page-item">

                    <a
                        href="{{ $paginator->nextPageUrl() }}"
                        class="page-link"
                        rel="next"
                        aria-label="Selanjutnya"
                    >

                        <span class="d-none d-sm-inline me-1">Selanjutnya</span>

                        <i class="fas fa-chevron-right"></i>

                    </a>

                </li>

            @else

                <li class="page-item disabled" aria-disabled="true">

                    <span class="page-link">

                        <span class="d-none d-sm-inline me-1">Selanjutnya</span>

                        <i class="fas fa-chevron-right"></i>

                    </span>

                </li>

            @endif

        </ul>

    </nav>

@endif