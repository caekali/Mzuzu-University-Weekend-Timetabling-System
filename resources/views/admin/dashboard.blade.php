<div>{{auth()->user()}}</div>

<form action="{{ route('logout') }}" method="post">
    @csrf
    <button>logout</button>
</form>