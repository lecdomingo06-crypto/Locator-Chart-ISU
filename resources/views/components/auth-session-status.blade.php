@props(['status'])

@if ($status)
    <x-flash-toast :message="$status" />
@endif
