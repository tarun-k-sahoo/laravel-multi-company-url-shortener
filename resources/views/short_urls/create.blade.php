@extends('layouts.app')

@section('content')
    <div class="container py-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>Create Short URL</h4>

            <a href="{{ route('short-urls.index') }}" class="btn btn-secondary">
                Back
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">

                <form action="{{ route('short-urls.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Original URL</label>

                        <input type="url" name="original_url"
                            class="form-control @error('original_url') is-invalid @enderror"
                            value="{{ old('original_url') }}" placeholder="https://example.com/page" required>

                        @error('original_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Generate Short URL
                    </button>

                </form>

            </div>
        </div>

    </div>
@endsection
