{{-- Printable CV rendered by DomPDF: real selectable text in reading order (section title, then content), so it parses cleanly in ATS tools. --}}
@php
    $fonts = resource_path('fonts');
    $host = parse_url(config('app.url'), PHP_URL_HOST);
    $portfolio = in_array($host, ['localhost', '127.0.0.1', null], true) ? null : preg_replace('#^https?://#', '', rtrim(config('app.url'), '/'));
    $contact = collect([
        $settings['contact_email'] ?: null,
        $settings['phone'] ?: null,
        $settings['location'] ?: null,
    ])
        ->merge($socialLinks->map(fn ($l) => \Illuminate\Support\Str::of($l->url)->after('://')->rtrim('/')->toString()))
        ->push($portfolio)
        ->filter();
    $hasExperience = $experiences['employment']->isNotEmpty() || $experiences['project']->isNotEmpty();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $settings['full_name'] }} — CV</title>
    <style>
        /* DomPDF only distinguishes normal/bold, so medium and semibold are separate families. */
        @font-face { font-family: 'Inter'; font-weight: normal; src: url('{{ $fonts }}/Inter-Regular.ttf') format('truetype'); }
        @font-face { font-family: 'Inter'; font-weight: bold; src: url('{{ $fonts }}/Inter-Bold.ttf') format('truetype'); }
        @font-face { font-family: 'Inter Medium'; font-weight: normal; src: url('{{ $fonts }}/Inter-Medium.ttf') format('truetype'); }
        @font-face { font-family: 'Inter SemiBold'; font-weight: normal; src: url('{{ $fonts }}/Inter-SemiBold.ttf') format('truetype'); }

        @page { margin: 16mm 18mm 14mm; }
        * { box-sizing: border-box; }
        body { font-family: 'Inter', 'DejaVu Sans', sans-serif; font-size: 9pt; line-height: 1.42; color: #4b5563; margin: 0; }
        p { margin: 0; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 0; vertical-align: top; }

        .name { font-weight: bold; font-size: 24pt; line-height: 1.05; color: #111827; letter-spacing: -0.5pt; }
        .role { font-size: 10pt; color: #6b7280; margin-top: 6pt; }
        .contact { font-size: 8.5pt; color: #6b7280; margin-top: 4pt; }
        .contact .sep { color: #d1d5db; padding: 0 5pt; }

        .section { margin-top: 10pt; padding-top: 8pt; border-top: 0.5pt solid #e5e7eb; }
        .label { width: 20%; padding-right: 12pt; }
        .label p { font-family: 'Inter SemiBold', sans-serif; font-size: 7.5pt; letter-spacing: 1.3pt; text-transform: uppercase; color: #0f766e; padding-top: 1.5pt; }

        .title { font-family: 'Inter SemiBold', sans-serif; color: #111827; }
        .soft { color: #9ca3af; }
        .item { margin-bottom: 5pt; }
        .skills td { padding-bottom: 3pt; }
        .skills tr:last-child td { padding-bottom: 0; }
        .skill-name { width: 31%; padding-right: 10pt; }
        .last { margin-bottom: 0; }
        ul { margin: 2pt 0 0; padding-left: 10pt; }
        li { margin-bottom: 1pt; }
    </style>
</head>
<body>
    <p class="name">{{ $settings['full_name'] }}</p>
    <p class="role">{{ $settings['professional_title'] }}</p>
    @if ($contact->isNotEmpty())
        <p class="contact">{!! $contact->map(fn ($c) => e($c))->implode('<span class="sep">|</span>') !!}</p>
    @endif

    <table class="section"><tr>
        <td class="label"><p>Profile</p></td>
        <td><p>{{ $settings['summary'] }}</p></td>
    </tr></table>

    @if ($skillCategories->isNotEmpty())
        <table class="section"><tr>
            <td class="label"><p>Skills</p></td>
            <td>
                <table class="skills">
                    @foreach ($skillCategories as $category)
                        <tr>
                            <td class="skill-name title">{{ $category->name }}</td>
                            <td>{{ $category->visibleSkills->pluck('name')->implode(', ') }}</td>
                        </tr>
                    @endforeach
                </table>
            </td>
        </tr></table>
    @endif

    @foreach (['employment' => 'Experience', 'project' => 'Contributions'] as $type => $heading)
        @if ($experiences[$type]->isNotEmpty())
            <table class="section"><tr>
                <td class="label"><p>{{ $heading }}</p></td>
                <td>
                    @foreach ($experiences[$type] as $item)
                        <div @class(['item' => ! $loop->last, 'last' => $loop->last])>
                            <p>
                                <span class="title">{{ $item->heading() }}</span>@if ($item->position && $item->organization), {{ $item->organization }}@endif
                                @if ($item->period())<span class="soft"> &nbsp;{{ $item->period() }}</span>@endif
                            </p>
                            @php $meta = collect([$item->employment_type, $item->location, $item->project?->title])->filter()->implode(' · '); @endphp
                            @if ($meta)<p class="soft">{{ $meta }}</p>@endif
                            @if ($item->summary)<p>{{ $item->summary }}</p>@endif
                            @php $bullets = array_merge($item->responsibilities ?? [], $item->contributions ?? []); @endphp
                            @if ($bullets)
                                <ul>@foreach ($bullets as $line)<li>{{ $line }}</li>@endforeach</ul>
                            @endif
                            @if (filled($item->technologies))<p class="soft">{{ implode(', ', $item->technologies) }}</p>@endif
                        </div>
                    @endforeach
                </td>
            </tr></table>
        @endif
    @endforeach

    @if ($projects->isNotEmpty() || $otherProjects->isNotEmpty())
        <table class="section"><tr>
            <td class="label"><p>Projects</p></td>
            <td>
                @foreach ($projects as $project)
                    <div @class(['item' => ! $loop->last || $otherProjects->isNotEmpty(), 'last' => $loop->last && $otherProjects->isEmpty()])>
                        <p><span class="title">{{ $project->title }}</span>@if ($project->category)<span class="soft"> &nbsp;{{ $project->category->name }}</span>@endif</p>
                        @if ($project->role)<p class="soft">{{ $project->role }}</p>@endif
                        <p>{{ $project->short_description }}</p>
                        @if (filled($project->technologies))<p class="soft">{{ implode(', ', $project->technologies) }}</p>@endif
                    </div>
                @endforeach
                @if ($otherProjects->isNotEmpty())
                    <p class="last"><span class="title">Also:</span> {{ $otherProjects->implode(', ') }}</p>
                @endif
            </td>
        </tr></table>
    @endif

    @if ($education->isNotEmpty())
        <table class="section"><tr>
            <td class="label"><p>Education</p></td>
            <td>
                @foreach ($education as $entry)
                    <div @class(['item' => ! $loop->last, 'last' => $loop->last])>
                        @php
                            $line = collect([
                                $entry->institution,
                                $entry->locationLabel(),
                                $entry->period(),
                                $entry->isAwarded() ? null : $entry->statusLabel(),
                            ])->filter()->implode(' · ');
                        @endphp
                        <p><span class="title">{{ $entry->displayTitle() }}</span>@if ($line)<span class="soft"> &nbsp;{{ $line }}</span>@endif</p>
                    </div>
                @endforeach
            </td>
        </tr></table>
    @endif

    @if ($certifications->isNotEmpty())
        <table class="section"><tr>
            <td class="label"><p>Certifications</p></td>
            <td>
                @foreach ($certifications as $cert)
                    <p @class(['item' => ! $loop->last, 'last' => $loop->last])>
                        <span class="title">{{ $cert->name }}</span>@if ($cert->issuer), {{ $cert->issuer }}@endif
                        @if ($cert->issued_on)<span class="soft"> &nbsp;{{ $cert->issued_on->format('M Y') }}</span>@endif
                    </p>
                @endforeach
            </td>
        </tr></table>
    @endif
</body>
</html>
