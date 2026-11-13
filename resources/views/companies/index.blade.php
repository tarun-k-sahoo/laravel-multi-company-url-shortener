@extends('layouts.app')

@section('title', 'Companies')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h3>Companies</h3>

        <a href="{{ route('companies.create') }}" class="btn btn-primary">
            + Add Company
        </a>

    </div>

    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Company Name</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th width="150">Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($companies as $company)
                            <tr>
                                <td>{{ $companies->firstItem() + $loop->index }}</td>

                                <td>{{ $company->name }}</td>

                                <td>
                                    @if ($company->status)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>

                                <td>{{ $company->created_at->format('d-m-Y') }}</td>

                                <td>
                                    <form action="{{ route('companies.toggle-status', $company->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')

                                        <button type="submit"
                                            class="btn btn-sm {{ $company->status ? 'btn-danger' : 'btn-success' }}">
                                            {{ $company->status ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="5" class="text-center">
                                    No companies found.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

            {{ $companies->links() }}

        </div>

    </div>

@endsection
