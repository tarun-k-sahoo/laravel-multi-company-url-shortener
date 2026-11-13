@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between mb-3">
            <h3>Invitations</h3>

            <a href="{{ route('invitations.create') }}" class="btn btn-primary">
                Create Invitation
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if (session('invitation_link'))
            <div class="alert alert-info">
                <label>Invitation Link</label>

                <div class="input-group">
                    <input type="text" id="invitationLink" class="form-control" value="{{ session('invitation_link') }}"
                        readonly>

                    <button type="button" class="btn btn-dark" onclick="copyInvitationLink()">
                        Copy
                    </button>
                </div>
            </div>
        @endif

        <div class="card">
            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered">

                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Email</th>
                                <th>Company</th>
                                <th>Role</th>
                                <th>Invited By</th>
                                <th>Status</th>
                                <th>Expiry</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($invitations as $invitation)
                                <tr>
                                    <td>{{ $invitations->firstItem() + $loop->index }}</td>

                                    <td>{{ $invitation->email }}</td>

                                    <td>{{ $invitation->company->name ?? '-' }}</td>

                                    <td>{{ $invitation->role->name ?? '-' }}</td>

                                    <td>{{ $invitation->inviter->name ?? '-' }}</td>

                                    <td>
                                        {{ ucfirst($invitation->status) }}
                                    </td>

                                    <td>
                                        {{ $invitation->expires_at ? $invitation->expires_at->format('d-m-Y h:i A') : '-' }}
                                    </td>

                                    <td>
                                        @if ($invitation->status === 'pending')
                                            <form method="POST"
                                                action="{{ route('invitations.cancel', $invitation->id) }}"
                                                onsubmit="return confirm('Cancel this invitation?')">

                                                @csrf
                                                @method('PATCH')

                                                <button class="btn btn-danger btn-sm">
                                                    Cancel
                                                </button>
                                            </form>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>

                            @empty

                                <tr>
                                    <td colspan="8" class="text-center">
                                        No invitations found.
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

                {{ $invitations->links() }}

            </div>
        </div>

    </div>

    <script>
        function copyInvitationLink() {
            const input = document.getElementById('invitationLink');

            navigator.clipboard.writeText(input.value);

            alert('Invitation link copied!');
        }
    </script>
@endsection
