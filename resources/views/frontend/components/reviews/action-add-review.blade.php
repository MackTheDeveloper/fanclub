@php($authRole = getAuthProps())
@if (Auth::check() && $authRole != 2)
    {{-- @if (Auth::check() && Auth::user()->role_id != 2) --}}
    <button class="border-btn" data-toggle="modal" value="{{ route('showAddReview', [$artistId, $songId]) }}"
        id="btnAddReview">Add Review</button>
@endif
