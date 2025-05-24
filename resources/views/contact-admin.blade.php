<x-layouts.guest subheader="Contact Admin">
    <div class="max-w-md mx-auto mt-10 p-6 bg-white rounded shadow">
        <h2 class="text-2xl font-bold mb-4 text-center">Contact Admin</h2>

        @if (session('status'))
            <div class="mb-4 text-green-600">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="mb-4 text-red-600">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('contact.admin') }}">
            @csrf
            <div class="mb-4">
                <label>Name</label>
                <input type="text" name="name" class="w-full border px-3 py-2 rounded" required>
            </div>

            <div class="mb-4">
                <label>Email</label>
                <input type="email" name="email" class="w-full border px-3 py-2 rounded" required>
            </div>

            <div class="mb-4">
                <label>Message</label>
                <textarea name="message" rows="4" class="w-full border px-3 py-2 rounded" required></textarea>
            </div>

            <div>
                <button type="submit" class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700">
                    Send Message
                </button>
            </div>
        </form>
    </div>
    {{-- @endsection --}}
</x-layouts.guest>
