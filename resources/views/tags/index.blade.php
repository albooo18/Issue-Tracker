@extends('layouts.app')

@section('title', 'Tags')

@section('content')
    <section class="overflow-hidden rounded-4xl border border-white/10 bg-linear-to-br from-slate-900/90 via-slate-900/80 to-slate-800/70 p-6 shadow-[0_25px_70px_rgba(2,6,23,0.35)] backdrop-blur-xl lg:p-8">
        <div class="grid gap-6 lg:grid-cols-[1.6fr_0.9fr] lg:items-end">
            <div class="space-y-4">
                <p class="text-[0.7rem] font-black uppercase tracking-[0.35em] text-sky-300">Label library</p>
                <h1 class="text-4xl font-black tracking-[-0.06em] text-white lg:text-6xl">Tags</h1>
                <p class="max-w-2xl text-sm text-slate-400 lg:text-base">Create color-coded labels to organize work across projects and keep issues searchable and readable.</p>
            </div>

            <form class="space-y-3 rounded-3xl border border-white/10 bg-white/5 p-4" method="POST" action="{{ route('tags.store') }}">
                @csrf
                <label class="field">
                    <span class="field-label">Tag name</span>
                    <input class="control" type="text" name="name" value="{{ old('name') }}" placeholder="Frontend" required>
                    @include('partials.field-error', ['name' => 'name'])
                </label>

                <label class="field">
                    <span class="field-label">Color</span>
                    <input class="control h-14 overflow-hidden p-2" type="color" name="color" value="{{ old('color', '#4f46e5') }}">
                    @include('partials.field-error', ['name' => 'color'])
                </label>

                <button class="inline-flex w-full items-center justify-center rounded-full bg-white px-4 py-3 text-sm font-bold text-slate-950 transition hover:bg-slate-100" type="submit">Create tag</button>
            </form>
        </div>
    </section>

    <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        @forelse ($tags as $tag)
            <article class="overflow-hidden rounded-[1.75rem] border border-white/10 bg-slate-900/70 p-5 shadow-[0_20px_60px_rgba(2,6,23,0.25)] transition hover:-translate-y-1 hover:border-sky-400/40 hover:bg-slate-900/90">
                <div class="flex items-start justify-between gap-3">
                    <div class="space-y-2">
                        <span class="tag-pill" style="--tag-color: {{ $tag->color ?: '#4f46e5' }}">{{ $tag->name }}</span>
                        <p class="text-sm text-slate-400">Used on {{ $tag->issues_count }} issues</p>
                    </div>
                    <div class="h-10 w-10 rounded-2xl border border-white/10" style="background: {{ $tag->color ?: '#4f46e5' }}"></div>
                </div>
                <p class="mt-4 text-sm text-slate-500">Color: {{ $tag->color ?: 'none' }}</p>
            </article>
        @empty
            <div class="rounded-[1.75rem] border border-dashed border-white/15 bg-white/5 p-10 text-center md:col-span-2 xl:col-span-3">
                <h2 class="text-2xl font-black tracking-[-0.04em] text-white">No tags yet</h2>
                <p class="mt-2 text-sm text-slate-400">Create a tag to organize issues and improve filtering.</p>
            </div>
        @endforelse
    </section>

    <div class="pagination-shell">
        {{ $tags->links() }}
    </div>
@endsection
