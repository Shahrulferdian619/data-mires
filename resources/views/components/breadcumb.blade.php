<nav aria-label="breadcrumb" >
    <ol class="breadcrumb">
        <li class="breadcrumb-item" >
            <a href="{{ url('admin/dashboard') }}">Home</a>
        </li>
        @foreach ( request()->segments() as $index => $value)
            <li class="breadcrumb-item">
                @if (count(request()->segments()) == 3)
                    @if ($index == 1)
                        <a href="{{ url('admin/' . request()->segments()[$index]) }}">{{$value}}</a>
                    @else
                        {{ $value }}
                    @endif
                @else
                    {{ $value }}
                @endif
            </li>
        @endforeach
    </ol>
</nav>