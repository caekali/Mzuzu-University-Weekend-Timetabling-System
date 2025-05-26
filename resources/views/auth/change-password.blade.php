@extends('layouts.guest')
@section('subtitle', 'Set New Password')
<form class="space-y-4">
    <x-inputs.input label="New Password" name="password" type="password" id="password" required />
    <x-inputs.input label="Confirm Password" name="confirm-password" type="password" id="confirm-password" required />
    <div>
        <x-button text='Change Password' type='submit' class="w-full" />
    </div>
</form>
@endsection
