@extends('layouts.main')

@section('title', 'Edit Assessment')

@section('content')
<div class="container mt-5">
    <h1>Edit Assessment</h1>

    <form action="{{ route('assessments.update', $assessment->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="typeID" class="form-label">Assessment Type</label>
            <select class="form-select" id="typeID" name="typeID" required>
                @foreach ($assessmentTypes as $type)
                    <option value="{{ $type->id }}" {{ $type->id == $assessment->typeID ? 'selected' : '' }}>
                        {{ $type->type }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $assessment->title) }}" maxlength="20" required>
        </div>

        <div class="mb-3">
            <label for="maxScore" class="form-label">Max Score</label>
            <input type="number" class="form-control" id="maxScore" name="maxScore" value="{{ old('maxScore', $assessment->maxScore) }}" min="1" max="100" required>
        </div>

        <div class="mb-3">
            <label for="deadline" class="form-label">Due Date and Time</label>
            <input type="datetime-local" class="form-control" id="deadline" name="deadline"
                   value="{{ old('deadline', isset($assessment->deadline) ? date('Y-m-d\TH:i', strtotime($assessment->deadline)) : '') }}" required>
        </div>
        
        <div class="mb-3">
            <label for="instruction" class="form-label">Instructions</label>
            <textarea class="form-control" id="instruction" name="instruction" required>{{ old('instruction', $assessment->instruction) }}</textarea>
        </div>

        <!-- Peer Review Type, if applicable -->

        <div class="mb-3" id="peerReviewTypeSection" style="{{ $assessment->typeID == 1 ? '' : 'display:none;' }}">
            <label for="reviewNumber" class="form-label">Number of Reviews Required</label>
            <input type="number" class="form-control" id="reviewNumber" name="reviewNumber" value="{{ old('reviewNumber', $assessment->reviewNumber) }}" min="1">
        </div>


        <div class="mb-3" id="peerReviewTypeSection" style="{{ $assessment->typeID == 1 ? '' : 'display:none;' }}">
            <label for="peerReviewType" class="form-label">Peer Review Type</label>
            <select class="form-select" id="peerReviewType" name="peerReviewType">
                @foreach ($peerReviewTypes as $peerType)
                    <option value="{{ $peerType->id }}" {{ $peerType->id == $assessment->peer_review_type_id ? 'selected' : '' }}>
                        {{ $peerType->type }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update Assessment</button>
        <a href="{{ route('assessments.show', $assessment->id) }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection


@section('scripts')
<script>
    document.getElementById('typeID').addEventListener('change', function() {
        const peerReviewTypeSection = document.getElementById('peerReviewTypeSection');
        if (this.value == 1) { // Assuming 1 is for peer review
            peerReviewTypeSection.style.display = 'block';
        } else {
            peerReviewTypeSection.style.display = 'none';
        }
    });
</script>
@endsection
