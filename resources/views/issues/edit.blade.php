@extends('layouts.app')

@section('title', 'Edit Issue')

@section('content')
    <section class="panel">
        <div class="section-head">
            <div>
                <p class="eyebrow">Issues</p>
                <h1 class="panel-title">Edit issue</h1>
            </div>
            <a class="btn btn-secondary" href="{{ route('issues.show', $issue) }}">Back</a>
        </div>

        @include('issues._form', [
            'issue' => $issue,
            'projects' => $projects,
            'tags' => $tags,
            'action' => route('issues.update', $issue),
            'method' => 'PUT',
            'button' => 'Update issue',
        ])
    </section>
@endsection
