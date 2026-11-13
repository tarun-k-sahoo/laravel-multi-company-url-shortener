
@extends('layouts.app')

@section('title', 'Add Company')

@section('content')

<div class="row">

    <div class="col-md-7">

        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white">
                <h5 class="mb-0">Create New Company</h5>
            </div>

            <div class="card-body">

                <form action="{{ route('companies.store') }}" method="POST">

                    @csrf

                    <div class="mb-3">

                        <label class="form-label">Company Name</label>

                        <input type="text"
                               name="name"
                               class="form-control"
                               value="{{ old('name') }}"
                               placeholder="Enter company name"
                               required>

                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                    </div>

                    <button type="submit" class="btn btn-primary">
                        Create Company
                    </button>

                    <a href="{{ route('companies.index') }}"
                       class="btn btn-secondary">
                        Cancel
                    </a>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection
