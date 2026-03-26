@extends('layouts.master')

@section('title','Dashboard')

@section('content')
    <div class="page-block">
        <div class="mb-6">
            <p class="text-slate-400 text-sm">Xoş gəldiniz, <span class="text-brand-400 font-semibold">Nicat</span> 👋 —
                Bu gün <span class="text-white font-semibold">3 müsahibə</span> var.</p>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-4 gap-4 mb-6">
            <div class="stat-card">
                <div class="flex items-center justify-between mb-3">
                    <div class="text-xs font-700 uppercase tracking-widest text-slate-500">Aktiv Vakansiya
                    </div>
                    <div class="w-9 h-9 rounded-lg bg-brand-500/20 flex items-center justify-center">
                        <svg class="w-4 h-4 text-brand-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 002-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
                <div class="font-display font-800 text-3xl text-white mb-1">14</div>
                <div class="text-xs text-success flex items-center gap-1"><span>▲</span> 3 bu ay əlavə edildi
                </div>
            </div>
            <div class="stat-card">
                <div class="flex items-center justify-between mb-3">
                    <div class="text-xs font-700 uppercase tracking-widest text-slate-500">Yeni Namizəd</div>
                    <div class="w-9 h-9 rounded-lg bg-cyan-500/15 flex items-center justify-center">
                        <svg class="w-4 h-4 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                </div>
                <div class="font-display font-800 text-3xl text-white mb-1">247</div>
                <div class="text-xs text-success flex items-center gap-1"><span>▲</span> 41 bu həftə</div>
            </div>
            <div class="stat-card">
                <div class="flex items-center justify-between mb-3">
                    <div class="text-xs font-700 uppercase tracking-widest text-slate-500">Məzuniyyət Sorğusu
                    </div>
                    <div class="w-9 h-9 rounded-lg bg-amber-500/15 flex items-center justify-center">
                        <svg class="w-4 h-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9" />
                        </svg>
                    </div>
                </div>
                <div class="font-display font-800 text-3xl text-white mb-1">8</div>
                <div class="text-xs text-warn flex items-center gap-1"><span>●</span> 3 gözləmədə</div>
            </div>
            <div class="stat-card">
                <div class="flex items-center justify-between mb-3">
                    <div class="text-xs font-700 uppercase tracking-widest text-slate-500">Cəmi İşçi</div>
                    <div class="w-9 h-9 rounded-lg bg-green-500/15 flex items-center justify-center">
                        <svg class="w-4 h-4 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                </div>
                <div class="font-display font-800 text-3xl text-white mb-1">183</div>
                <div class="text-xs text-slate-400">12 departament</div>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-4 mb-4">
            <!-- Hire Funnel -->
            <div class="card col-span-2">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <div class="font-display font-700 text-white">İşə Qəbul Funneli</div>
                        <div class="text-xs text-slate-500 mt-0.5">Bu ay bütün vakansiyalar üzrə</div>
                    </div>
                    <div class="badge badge-blue">Mart 2025</div>
                </div>
                <div class="space-y-3">
                    <div>
                        <div class="flex justify-between text-xs text-slate-400 mb-1.5">
                            <span>Müraciət</span><span class="text-white font-600">247</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill bg-gradient-to-r from-brand-600 to-brand-400" style="width:100%">
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-xs text-slate-400 mb-1.5"><span>CV
                                Keçdi</span><span class="text-white font-600">142</span></div>
                        <div class="progress-bar">
                            <div class="progress-fill bg-gradient-to-r from-cyan-600 to-cyan-400" style="width:57%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-xs text-slate-400 mb-1.5">
                            <span>Müsahibə</span><span class="text-white font-600">67</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill bg-gradient-to-r from-violet-600 to-violet-400" style="width:27%">
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-xs text-slate-400 mb-1.5">
                            <span>Təklif</span><span class="text-white font-600">24</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill bg-gradient-to-r from-amber-600 to-amber-400" style="width:10%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-xs text-slate-400 mb-1.5"><span>Qəbul
                                Edildi</span><span class="text-white font-600">18</span></div>
                        <div class="progress-bar">
                            <div class="progress-fill bg-gradient-to-r from-green-600 to-green-400" style="width:7%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- AI Activity -->
            <div class="card">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-2 h-2 rounded-full bg-brand-400 ai-pulse"></div>
                    <div class="font-display font-700 text-white text-sm">AI Fəaliyyəti</div>
                </div>
                <div class="space-y-3">
                    <div class="flex items-center gap-3 p-3 rounded-10 bg-surface-700 border border-brand-900/40">
                        <div
                            class="w-8 h-8 rounded-lg bg-brand-500/20 flex items-center justify-center flex-shrink-0 text-sm">
                            🤖</div>
                        <div>
                            <div class="text-xs font-600 text-white">12 CV analiz edildi</div>
                            <div class="text-[11px] text-slate-500">Backend Dev vakansiyası</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-3 rounded-10 bg-surface-700 border border-cyan-900/30">
                        <div
                            class="w-8 h-8 rounded-lg bg-cyan-500/15 flex items-center justify-center flex-shrink-0 text-sm">
                            💡
                        </div>
                        <div>
                            <div class="text-xs font-600 text-white">3 Talent tövsiyəsi</div>
                            <div class="text-[11px] text-slate-500">UX Designer üçün</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-3 rounded-10 bg-surface-700 border border-green-900/30">
                        <div
                            class="w-8 h-8 rounded-lg bg-green-500/15 flex items-center justify-center flex-shrink-0 text-sm">
                            ✅
                        </div>
                        <div>
                            <div class="text-xs font-600 text-white">5 müsahibə planlandı</div>
                            <div class="text-[11px] text-slate-500">Sabaha 3, bu günə 2</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-3 rounded-10 bg-surface-700 border border-amber-900/30">
                        <div
                            class="w-8 h-8 rounded-lg bg-amber-500/15 flex items-center justify-center flex-shrink-0 text-sm">
                            ⏰
                        </div>
                        <div>
                            <div class="text-xs font-600 text-white">8 icazə gözləmədə</div>
                            <div class="text-[11px] text-slate-500">Cavab tələb edir</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Requests -->
        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <div class="font-display font-700 text-white">Son Sorğular</div>
                <button class="btn-ghost text-xs">Hamısına bax</button>
            </div>
            <table class="tbl">
                <thead>
                    <tr>
                        <th>İşçi</th>
                        <th>Növ</th>
                        <th>Tarix</th>
                        <th>Müddət</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="flex items-center gap-2">
                                <div class="avatar bg-brand-600/30 text-brand-300 text-xs">AF</div><span>Aytən
                                    Fərəcova</span>
                            </div>
                        </td>
                        <td><span class="badge badge-green">Məzuniyyət</span></td>
                        <td class="text-slate-400">10–20 Mart</td>
                        <td>10 gün</td>
                        <td><span class="badge badge-yellow">Gözləmədə</span></td>
                        <td>
                            <div class="flex gap-2"><button class="btn-primary text-xs py-1 px-3">Təsdiqlə</button><button
                                    class="btn-danger text-xs py-1 px-3">Rədd</button></div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="flex items-center gap-2">
                                <div class="avatar bg-cyan-600/30 text-cyan-300 text-xs">RH</div><span>Rauf
                                    Həsənov</span>
                            </div>
                        </td>
                        <td><span class="badge badge-cyan">Ezamiyyət</span></td>
                        <td class="text-slate-400">15–18 Mart</td>
                        <td>3 gün</td>
                        <td><span class="badge badge-green">Təsdiqləndi</span></td>
                        <td><button class="btn-ghost text-xs py-1 px-3">Bax</button></td>
                    </tr>
                    <tr>
                        <td>
                            <div class="flex items-center gap-2">
                                <div class="avatar bg-violet-600/30 text-violet-300 text-xs">LM</div>
                                <span>Leyla Məmmədova</span>
                            </div>
                        </td>
                        <td><span class="badge badge-yellow">İcazə</span></td>
                        <td class="text-slate-400">Bu gün</td>
                        <td>2 saat</td>
                        <td><span class="badge badge-yellow">Gözləmədə</span></td>
                        <td>
                            <div class="flex gap-2"><button class="btn-primary text-xs py-1 px-3">Təsdiqlə</button><button
                                    class="btn-danger text-xs py-1 px-3">Rədd</button></div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
