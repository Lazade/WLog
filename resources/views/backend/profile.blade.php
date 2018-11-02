@extends('backend.Layouts.avalon', ['current' => 'profile'])

@section('content')

    <h4>PROFILE </h4>

    @include('backend.Widgets.alert')

    <div class="wlog-card">
        
        <div class="wlog-tabs layout-tabs">
            <nav id="wlog-tab-bar" class="layout-tabs__container">
                <span class="layout-tab wlog-tab wlog-tab--active">Password</span>
                <span class="layout-tab wlog-tab">Email</span>
                <span class="layout-tab wlog-tab">Username</span>
                <span class="wlog__indicator layout-tab-bar__indicator"></span>
            </nav>
        </div>

        <section id="wlog-tab-panels" class="wlog-tab-panels layout-tab-panels">
            <div class="layout-tab-panels__container">
                <div class="wlog-tab-panel layout-tab-panel">
                    <div class="wlog-form-panel layout-form-panel">
                        <form method="POST" action="{{ route('profile.resetPassword') }}" aria-label="{{ __('Reset Password') }}">
                            {{ csrf_field() }}
                            <fieldset>
                                <h5>Password</h5>
                                <div class="other-subjects">
                                    <div class="layout-input__control wlog-input__control wlog-control">
                                        <label>Password</label>
                                        <input type="password" name="password" required>
                                    </div>
                                    <div class="layout-input__control wlog-input__control wlog-control">
                                        <label>Confirm</label>
                                        <input type="password" name="password_confirmation" required>
                                    </div>
                                </div>
                                <div class="button-container full-width">
                                    <button type="submit" class="wlog-button-rect">Reset</button>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
                <div class="wlog-tab-panel layout-tab-panel">
                    <div class="wlog-form-panel layout-form-panel">
                        <form method="POST" action="{{ route('profile.resetEmail') }}" aria-label="{{ __('Reset Email') }}">
                            {{ csrf_field() }}
                            <fieldset>
                                <h5>Email </h5>
                                <div class="other-subjects">
                                    <div class="layout-input__control wlog-input__control wlog-control">
                                        <label>Email </label>
                                        <input type="email" name="email" value="{{ $old_email }}" required>
                                    </div>
                                </div>
                                <div class="button-container full-width">
                                    <button type="submit" class="wlog-button-rect">Reset</button>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
                <div class="wlog-tab-panel layout-tab-panel">
                    <div class="wlog-form-panel layout-form-panel">
                        <form method="POST" action="{{ route('profile.resetUsername') }}" aria-label="{{ __('Reset Username') }}">
                            {{ csrf_field() }}
                            <fieldset>
                                <h5>Username </h5>
                                <div class="other-subjects">
                                    <div class="layout-input__control wlog-input__control wlog-control">
                                        <label>Username </label>
                                        <input type="text" name="username" value="{{ $old_username }}" required>
                                    </div>
                                </div>
                                <div class="button-container full-width">
                                    <button type="submit" class="wlog-button-rect">Reset</button>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </section>

    </div>

@endsection