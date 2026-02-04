@include('layouts.welcome')
  
    <div class="flex-center position-ref full-height">
        @if (Route::has('login'))
        <div class="top-right links color-white">
            @auth
                @if (auth()->user()->hasRole('admin'))
                    <a href="{{ url('/admin') }}">Admin</a>
                @else
                    <a href="{{ route('user.attendance.index') }}">Absensi</a>
                @endif
            @else
            <a style="color: white" href="{{ route('login') }}">Masuk</a>

            @if (Route::has('register'))
            <a href="{{ route('register') }}">Daftar</a>
            @endif
            @endauth
        </div>
        @endif

        <div class="content">
            <div class="title m-b-md">
                <div class="clockStyle" id="clock">123</div>
            </div>

            
        </div>
    </div>

