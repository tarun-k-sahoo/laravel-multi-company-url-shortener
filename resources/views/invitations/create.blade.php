@extends('layouts.app')

@section('content')

    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-7">

                <div class="card">

                    <div class="card-header">
                        <h4>Create Invitation</h4>
                    </div>

                    <div class="card-body">

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif

                        <form action="{{ route('invitations.store') }}" method="POST">

                            @csrf

                            <div class="mb-3">
                                <label>Email Address</label>

                                <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                                    required>
                            </div>

                            @if (auth()->user()->role->slug === 'super-admin')
                                <div class="mb-3">
                                    <label>Select Company</label>

                                    <select name="company_id" class="form-select" required>

                                        <option value="">Select Company</option>

                                        @foreach ($companies as $company)
                                            <option value="{{ $company->id }}">
                                                {{ $company->name }}
                                            </option>
                                        @endforeach

                                    </select>
                                </div>
                            @endif

                            <div class="mb-3">
                                <label>Select Role</label>

                                <select name="role_id" class="form-select" required>

                                    <option value="">Select Role</option>

                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">
                                            {{ $role->name }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>

                            <a href="{{ route('invitations.index') }}" class="btn btn-secondary">
                                Back
                            </a>

                            <button type="submit" class="btn btn-primary">
                                Create Invitation
                            </button>

                        </form>

                    </div>
                </div>

            </div>
        </div>

    </div>

@endsection
