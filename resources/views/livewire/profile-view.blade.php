<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Profile</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Profile Information Section -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Profile Information</h3>
                        </div>
                        <div class="card-body">
                            <p>Update your account's profile information and email address.</p>
                            <form method="post" action="{{ route('profile.update') }}">
                                @csrf
                                @method('patch')
    
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name', auth()->user()->name) }}" required autofocus autocomplete="name">
                                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
    
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email', auth()->user()->email) }}" required autocomplete="username">
                                    @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
    
                                @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
                                    <div class="mt-2">
                                        <p>Your email address is unverified.</p>
                                        <button form="send-verification" class="btn btn-secondary btn-sm">Resend Verification Email</button>
                                    </div>
                                @endif
    
                                <button type="submit" class="btn btn-primary">Save</button>
    
                                @if (session('status') === 'profile-updated')
                                    <div class="alert alert-success mt-2">Saved.</div>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
    
                <!-- Update Password Section -->
                <div class="col-md-6">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Update Password</h3>
                        </div>
                        <div class="card-body">
                            <p>Ensure your account is using a long, random password to stay secure.</p>
                            <form method="post" action="{{ route('password.update') }}">
                                @csrf
                                @method('put')
    
                                <div class="form-group">
                                    <label for="current_password">Current Password</label>
                                    <input type="password" id="current_password" name="current_password" class="form-control" autocomplete="current-password">
                                    @error('current_password') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
    
                                <div class="form-group">
                                    <label for="password">New Password</label>
                                    <input type="password" id="password" name="password" class="form-control" autocomplete="new-password">
                                    @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
    
                                <div class="form-group">
                                    <label for="password_confirmation">Confirm Password</label>
                                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" autocomplete="new-password">
                                    @error('password_confirmation') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
    
                                <button type="submit" class="btn btn-primary">Save</button>
    
                                @if (session('status') === 'password-updated')
                                    <div class="alert alert-success mt-2">Saved.</div>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
</div>
