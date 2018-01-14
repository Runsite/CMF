@extends('runsite::layouts.users')

@section('user')
<div class="pad">
    <div class="callout callout-success">
        <h4>{{ trans('runsite::users.invite.Invite form user') }}: {{ $invite->user->name }} ({{ $invite->user->email }})</h4>

        <p><a href="{{ route('admin.invite.form', $invite->token) }}" target="_blank">{{ route('admin.invite.form', $invite->token) }}</a></p>

        <p>{{ trans('runsite::users.invite.Expires at') }}: {{ $invite->expires_at->format('d.m.Y H:i') }}</p>
    </div>
</div>
@endsection
