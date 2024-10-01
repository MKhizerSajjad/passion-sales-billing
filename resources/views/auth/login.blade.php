<x-guest-layout>
    {{-- <div id="loading">
        <div id="loading-center">
        </div>
    </div> --}}

    <div class="wrapper">
        <section class="login-content">
            <div class="container h-100">
                <div class="row align-items-center justify-content-center h-100">
                    <div class="col-12">
                        <div class="row align-items-center">
                            <!-- Session Status -->
                            <x-auth-session-status class="alert alert-success" :status="session('status')" />
                            <div class="col-lg-6">
                                <h2 class="mb-2">Sign In</h2>
                                <p>To Keep connected with us please login with your personal info.</p>
                                <form method="POST" action="{{ route('login') }}">
                                    @csrf
                                    <div class="row" bis_skin_checked="1">
                                        <!-- Email Address -->
                                        <div class="col-lg-12">
                                            <div class="floating-label form-group">
                                                <x-text-input id="email" class="floating-input form-control" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                                                <x-input-label for="email" :value="__('Email')"/>
                                                <x-input-error :messages="$errors->get('email')" class="text text-danger" />
                                            </div>
                                        </div>
                                        <!-- Password -->
                                        <div class="col-lg-12">
                                            <div class="floating-label form-group">
                                                <x-text-input id="password" class="floating-input form-control" type="password" name="password" required autocomplete="current-password" />
                                                <x-input-label for="password" :value="__('Password')" />
                                                <x-input-error :messages="$errors->get('password')"/>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="custom-control custom-checkbox mb-3">
                                                <input type="checkbox" class="custom-control-input" id="remember_me" name="remember">
                                                <label class="custom-control-label" for="remember_me">{{ __('Remember me') }}</label>
                                            </div>
                                        </div>
                                        {{-- <div class="col-lg-6 rtl-left">
                                            @if (Route::has('password.request'))
                                                <a href="{{ route('password.request') }}" class="text-primary float-right">{{ __('Forgot your password?') }}</a>
                                            @endif
                                        </div> --}}
                                    </div>
                                    <button type="submit" class="btn btn-primary">{{ __('Log in') }}</button>
                                    <p class="mt-3">

                                        {{-- @if (Route::has('register'))
                                            Create an Account <a href="{{ route('register') }}" class="text-primary">{{ __('Sign Up') }}</a>
                                        @endif --}}
                                    </p>
                                </form>
                            </div>
                            <div class="col-lg-6 mb-lg-0 mb-4 mt-lg-0 mt-4">
                                <img src="{{ asset('admin/images/logo.png') }}" class="img-fluid w-80" alt="Floating image">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    {{-- <div class="custom-control-inline change-rtl">
        <a href="#" class="switch-rtl" data-active="true" for="rtl-mode" data-mode="rtl">RTL</a>
    </div> --}}
</x-guest-layout>
