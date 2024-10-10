@extends('layouts.app')

@section('content')
    <h1>Peer Reviews for Assessment #{{ $assessmentId }}</h1>

    <a href="{{ route('peer_reviews.create', $assessmentId) }}" class="btn btn-primary">Add New Peer Review</a>

    @if($peerReviews->isEmpty())
        <p>No peer reviews found for this assessment.</p>
    @else
        <ul>
            @foreach($peerReviews as $review)
                <li>
                    <strong>Reviewer:</strong> {{ $review->reviewer->name }}
                    <strong>Reviewee:</strong> {{ $review->reviewee->name }}
                    <strong>Score:</strong> {{ $review->score }}
                    <strong>Group:</strong> {{ $review->group }}
                    <p>{{ $review->comment }}</p>

                    <a href="{{ route('peer_reviews.edit', [$assessmentId, $review->id]) }}">Edit</a>
                    <form action="{{ route('peer_reviews.destroy', [$assessmentId, $review->id]) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Delete</button>
                    </form>
                </li>
            @endforeach
        </ul>
    @endif
@endsection
