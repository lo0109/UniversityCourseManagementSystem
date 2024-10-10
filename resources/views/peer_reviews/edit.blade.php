@extends('layouts.app')

@section('content')
    <h1>Edit Peer Review for Assessment #{{ $assessmentId }}</h1>

    <form action="{{ route('peer_reviews.update', [$assessmentId, $peerReview->id]) }}" method="POST">
        @csrf
        @method('PUT')

        <label for="group">Group:</label>
        <input type="text" name="group" value="{{ $peerReview->group }}" required>

        <label for="score">Score:</label>
        <input type="number" name="score" value="{{ $peerReview->score }}" min="0" max="100" required>

        <label for="comment">Comment:</label>
        <textarea name="comment" required>{{ $peerReview->comment }}</textarea>

        <button type="submit">Update</button>
    </form>
@endsection
