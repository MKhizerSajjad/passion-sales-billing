<x-guest-layout>
    <!-- loader -->
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
                            <div class="col-lg-6">
                                <h2 class="mb-2">{{ __('Reset Password') }}</h2>
                                    <p class="mb-4">
                                        {{ __('Please Enter New Password') }}
                                    </p>
                                    <form method="POST" action="{{ route('password.store') }}">
                                        @csrf

                                        <!-- Password Reset Token -->
                                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                                        <!-- Email Address -->
                                        <div class="floating-label form-group">
                                            <x-text-input id="email" class="floating-input form-control" type="email" name="email" :value="old('email', $request->email)" required autocomplete="username" />
                                            <x-input-label for="email" :value="__('Email')" />
                                            <x-input-error :messages="$errors->get('email')" class="text text-danger" />
                                        </div>

                                        <!-- Password -->
                                        <div class="floating-label form-group">
                                            <x-text-input id="password" class="floating-input form-control" type="password" name="password" required autofocus autocomplete="new-password" />
                                            <x-input-label for="password" :value="__('Password')" />
                                            <x-input-error :messages="$errors->get('password')" class="text text-danger" />
                                        </div>

                                        <!-- Confirm Password -->
                                        <div class="floating-label form-group">
                                            <x-text-input id="password_confirmation" class="floating-input form-control" type="password" name="password_confirmation" required autocomplete="new-password" />
                                            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                                            <x-input-error :messages="$errors->get('password_confirmation')" class="text text-danger" />
                                        </div>

                                        {{-- <div class="flex items-center justify-end mt-4">
                                            <x-primary-button>
                                                {{ __('Reset Password') }}
                                            </x-primary-button>
                                        </div> --}}
                                        <button type="submit" class="btn btn-primary">{{ __('Reset Password') }}</button>
                                    </form>
                            </div>
                            <div class="col-lg-6 rmb-30">
                                <img src="{{ asset('admin/images/login/01.png') }}" class="img-fluid w-80" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</x-guest-layout>
