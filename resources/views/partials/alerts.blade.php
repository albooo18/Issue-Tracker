@if (session('status') || $errors->any())
    <section class="space-y-3">
        @if (session('status'))
            <div class="rounded-[1.5rem] border border-emerald-400/20 bg-emerald-400/10 px-4 py-3 text-sm font-medium text-emerald-100 shadow-lg shadow-emerald-950/10">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-[1.5rem] border border-rose-500/20 bg-rose-500/10 px-4 py-3 text-sm text-rose-100 shadow-lg shadow-rose-950/10">
                <p class="text-base font-bold text-white">Please fix the highlighted form errors.</p>
                <ul class="mt-2 list-disc space-y-1 pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </section>
@endif
