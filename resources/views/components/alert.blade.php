<div class="px-4 pt-5">
    @if ($message = session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <p class="text-white mb-0">{{ session()->get('success') }}</p>
        </div>
    @endif
    @if ($message = session()->has('error'))
        <div class="alert alert-danger" role="alert">
            <p class="text-white mb-0">{{ session()->get('error') }}</p>
        </div>
    @endif
    @if ($errors->any())
        <div class="card p-4 alert alert-danger" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li style="color: white">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
