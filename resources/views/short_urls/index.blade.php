@extends('layouts.app')

@section('content')
    <div class="container py-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>Short URLs</h4>

            @if (in_array(auth()->user()->role->slug, ['admin', 'member']))
                <a href="{{ route('short-urls.create') }}" class="btn btn-primary">
                    Create Short URL
                </a>
            @endif
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Company</th>
                            <th>Created By</th>
                            <th>Original URL</th>
                            <th>Short URL</th>
                            <th>Created At</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($shortUrls as $url)
                            <tr>
                                <td>{{ $shortUrls->firstItem() + $loop->index }}</td>

                                <td>{{ $url->company->name ?? '-' }}</td>

                                <td>{{ $url->user->name ?? '-' }}</td>

                                <td>
                                    <a href="{{ $url->original_url }}" target="_blank">
                                        {{ Str::limit($url->original_url, 40) }}
                                    </a>
                                </td>

                                <td>
                                    <div class="d-flex align-items-center gap-2">

                                        <a href="{{ route('short-urls.redirect', $url->short_code) }}" target="_blank">
                                            {{ route('short-urls.redirect', $url->short_code) }}
                                        </a>

                                        <button type="button" class="btn btn-sm btn-outline-primary copy-btn"
                                            data-url="{{ route('short-urls.redirect', $url->short_code) }}">
                                            Copy
                                        </button>

                                    </div>
                                </td>

                                <td>{{ $url->created_at->format('d M Y h:i A') }}</td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="6" class="text-center">
                                    No short URLs found.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>

                {{ $shortUrls->links() }}

            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('.copy-btn').forEach(function(button) {
            button.addEventListener('click', function() {

                const url = this.dataset.url;

                navigator.clipboard.writeText(url).then(() => {
                    this.innerText = 'Copied!';

                    setTimeout(() => {
                        this.innerText = 'Copy';
                    }, 1500);
                }).catch(() => {
                    alert('Unable to copy URL. Please copy it manually.');
                });

            });
        });
    </script>
@endpush
