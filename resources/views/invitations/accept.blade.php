<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Accept Invitation</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-5">

        <div class="row justify-content-center">
            <div class="col-md-6">

                <div class="card shadow-sm">

                    <div class="card-header">
                        <h4>Accept Invitation</h4>
                    </div>

                    <div class="card-body">

                        <p>
                            You have been invited to join
                            <strong>{{ $invitation->company->name }}</strong>.
                        </p>

                        <p>
                            Role: <strong>{{ $invitation->role->name }}</strong>
                        </p>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif

                        <form method="POST" action="{{ route('invitations.accept.submit', $invitation->token) }}">

                            @csrf

                            <div class="mb-3">
                                <label>Email</label>

                                <input type="email" class="form-control" value="{{ $invitation->email }}" readonly>
                            </div>

                            <div class="mb-3">
                                <label>Name</label>

                                <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label>Password</label>

                                <input type="password" name="password" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label>Confirm Password</label>

                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                Accept Invitation
                            </button>

                        </form>

                    </div>
                </div>

            </div>
        </div>

    </div>

</body>

</html>
