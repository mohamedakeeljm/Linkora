@extends('layouts.app')
@section('title', 'Analytics')

@section('content')

<div class="topbar animate-fade-up">
    <div class="topbar-title">
        <h1>Profile <span class="text-gradient">Analytics</span></h1>
        <p>Monitor your audience views, link interactions, and conversion trends.</p>
    </div>
    <div class="topbar-actions">
        {{-- Time Range selector --}}
        <div style="display:flex; gap:0.4rem; background:var(--bg-input); padding:0.25rem; border-radius:var(--radius-full); border:1px solid var(--border);">
            @foreach([7 => '7D', 14 => '14D', 30 => '30D', 90 => '90D'] as $d => $txt)
                <a href="{{ route('analytics.index', ['days' => $d]) }}"
                   class="btn btn-sm {{ $days === $d ? 'btn-primary' : 'btn-ghost' }}"
                   style="padding:0.25rem 0.75rem; font-size:0.75rem;">
                    {{ $txt }}
                </a>
            @endforeach
        </div>
    </div>
</div>

{{-- Metric Highlights --}}
<div class="stats-grid">
    <div class="stat-card animate-fade-up stagger-1">
        <div class="stat-icon stat-icon-purple"><i class="fas fa-eye"></i></div>
        <div>
            <div class="stat-value">{{ number_format($rangeViews) }}</div>
            <div class="stat-label">Views (Last {{ $days }} Days)</div>
            <div class="stat-change" style="color:var(--text-muted);">{{ number_format($totalViews) }} all-time</div>
        </div>
    </div>

    <div class="stat-card animate-fade-up stagger-2">
        <div class="stat-icon stat-icon-pink"><i class="fas fa-hand-pointer"></i></div>
        <div>
            <div class="stat-value">{{ number_format($rangeClicks) }}</div>
            <div class="stat-label">Clicks (Last {{ $days }} Days)</div>
            <div class="stat-change" style="color:var(--text-muted);">{{ number_format($totalClicks) }} all-time</div>
        </div>
    </div>

    <div class="stat-card animate-fade-up stagger-3">
        <div class="stat-icon stat-icon-green"><i class="fas fa-arrow-trend-up"></i></div>
        <div>
            <div class="stat-value">{{ $rangeViews > 0 ? round(($rangeClicks / $rangeViews) * 100, 1) : 0 }}%</div>
            <div class="stat-label">Range Conversion</div>
            <div class="stat-change" style="color:var(--text-muted);">{{ $overallCtr }}% all-time CTR</div>
        </div>
    </div>
</div>

{{-- Main Traffic Chart --}}
<div class="card animate-fade-up" style="margin-bottom:1.5rem; animation-delay:.15s;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem;">
        <h4 style="margin:0;">Views & Clicks Over Time</h4>
        <span class="badge badge-purple">Trends</span>
    </div>
    <div style="position:relative; height:280px;">
        <canvas id="trafficTrendChart"></canvas>
    </div>
</div>

<div class="dashboard-grid">

    {{-- Link Breakdown Table --}}
    <div class="card animate-fade-up" style="animation-delay:.2s;">
        <h4 style="margin-bottom:1.25rem;">Link Click Breakdown</h4>
        @if($linksPerformance->isEmpty())
            <div class="empty-state" style="padding:1.5rem;">
                <p>No links available to track yet.</p>
            </div>
        @else
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Platform & Label</th>
                            <th style="text-align:right;">Recent Clicks</th>
                            <th style="text-align:right;">Total Clicks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($linksPerformance as $l)
                            <tr>
                                <td>
                                    <div style="display:flex; align-items:center; gap:0.75rem;">
                                        <div style="color:{{ $l->brandColor() }}; font-size:1.1rem; width:24px; text-align:center;">
                                            <i class="{{ $l->iconClass() }}"></i>
                                        </div>
                                        <div>
                                            <div style="font-weight:600; color:var(--text-primary);">{{ $l->label }}</div>
                                            <div style="font-size:0.75rem; color:var(--text-muted); max-width:240px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                                                {{ $l->url }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td style="text-align:right; font-weight:700; color:var(--accent);">
                                    {{ number_format($l->range_clicks) }}
                                </td>
                                <td style="text-align:right; color:var(--text-secondary);">
                                    {{ number_format($l->click_count) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Referral Sources --}}
    <div class="card animate-fade-up" style="animation-delay:.25s;">
        <h4 style="margin-bottom:1.25rem;">Top Referrers</h4>
        @if($topReferrers->isEmpty())
            <div class="empty-state" style="padding:1.5rem;">
                <div style="font-size:1.8rem; margin-bottom:0.5rem;">🌐</div>
                <p style="font-size:0.85rem;">Direct traffic or private browsers.</p>
            </div>
        @else
            <div style="display:flex; flex-direction:column; gap:0.75rem;">
                @foreach($topReferrers as $ref)
                    <div style="display:flex; justify-content:space-between; align-items:center; padding:0.6rem 0.85rem; background:var(--bg-input); border-radius:var(--radius); border:1px solid var(--border);">
                        <span style="font-size:0.85rem; color:var(--text-primary); max-width:200px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                            {{ parse_url($ref->referer, PHP_URL_HOST) ?? $ref->referer }}
                        </span>
                        <span class="badge badge-purple">{{ number_format($ref->count) }}</span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</div>

@endsection

@push('scripts')
<script>
const ctx = document.getElementById('trafficTrendChart');
if (ctx) {
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($labels),
            datasets: [
                {
                    label: 'Page Views',
                    data: @json($viewsData),
                    borderColor: '#8b5cf6',
                    backgroundColor: 'rgba(139, 92, 246, 0.15)',
                    borderWidth: 2.5,
                    fill: true,
                    tension: 0.35,
                    pointRadius: 3
                },
                {
                    label: 'Link Clicks',
                    data: @json($clicksData),
                    borderColor: '#ec4899',
                    backgroundColor: 'rgba(236, 72, 153, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.35,
                    pointRadius: 3
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    labels: { color: '#a09ac0', font: { size: 12 } }
                },
                tooltip: {
                    backgroundColor: '#1a1a2e',
                    borderColor: 'rgba(139, 92, 246, 0.3)',
                    borderWidth: 1,
                    titleColor: '#f1f0ff',
                    bodyColor: '#a09ac0'
                }
            },
            scales: {
                x: {
                    grid: { color: 'rgba(255, 255, 255, 0.04)' },
                    ticks: { color: '#6b6589', font: { size: 11 } }
                },
                y: {
                    grid: { color: 'rgba(255, 255, 255, 0.04)' },
                    ticks: { color: '#6b6589', font: { size: 11 }, precision: 0 },
                    beginAtZero: true
                }
            }
        }
    });
}
</script>
@endpush
