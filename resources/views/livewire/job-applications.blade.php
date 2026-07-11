<div class="py-8 bg-gradient-to-b from-gray-50 to-white min-h-screen">
    <div class="max-w-6xl mx-auto px-4">
        <!-- Header with CTA -->
        <div class="mb-8">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h2 class="text-4xl font-bold text-gray-900 mb-2">🧳 Job Applications</h2>
                    <p class="text-gray-600 text-lg">Track your career journey</p>
                </div>
                <a href="/amrTechAdmin/job-portals" class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-bold px-6 py-3 rounded-lg hover:shadow-lg transition-all duration-300 transform hover:scale-105">
                    🌐 Browse Job Portals
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-3xl font-bold text-gray-900">{{ $stats['total'] }}</div>
                <div class="text-sm text-gray-600">Total Applications</div>
            </div>
            <div class="bg-blue-50 rounded-lg shadow p-6">
                <div class="text-3xl font-bold text-blue-600">{{ $stats['by_status']['Oferta'] ?? 0 }}</div>
                <div class="text-sm text-gray-600">Offers</div>
            </div>
            <div class="bg-purple-50 rounded-lg shadow p-6">
                <div class="text-3xl font-bold text-purple-600">{{ $stats['by_status']['Entrevista'] ?? 0 }}</div>
                <div class="text-sm text-gray-600">Interviews</div>
            </div>
            <div class="bg-green-50 rounded-lg shadow p-6">
                <div class="text-3xl font-bold text-green-600">{{ number_format($stats['avg_score'] ?? 0, 1) }}</div>
                <div class="text-sm text-gray-600">Avg Score</div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <input
                    type="text"
                    placeholder="Search company or position..."
                    wire:model.live="search"
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
                <select
                    wire:model.live="statusFilter"
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                    <option value="">All Status</option>
                    <option value="Postulado">Postulado</option>
                    <option value="En Revisión">En Revisión</option>
                    <option value="Entrevista">Entrevista</option>
                    <option value="Prueba Técnica">Prueba Técnica</option>
                    <option value="Oferta">Oferta</option>
                    <option value="Rechazado">Rechazado</option>
                    <option value="Aceptado">Aceptado</option>
                </select>
                <select
                    wire:model.live="sortBy"
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                    <option value="application_date">Newest First</option>
                    <option value="created_at">Recently Added</option>
                </select>
            </div>
        </div>

        <!-- Applications Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Company</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Position</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Score</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Documents</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($applications as $app)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $app->company }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $app->position }}</td>
                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                                @if ($app->score)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $app->score }}
                                    </span>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if ($app->status === 'Oferta') bg-green-100 text-green-800
                                    @elseif ($app->status === 'Entrevista') bg-purple-100 text-purple-800
                                    @elseif ($app->status === 'En Revisión') bg-blue-100 text-blue-800
                                    @elseif ($app->status === 'Rechazado') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                    @endif
                                ">
                                    {{ $app->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm space-x-2">
                                @if ($app->evaluation?->coverLetter())
                                    <a href="{{ route('documents.view', ['evaluation' => $app->evaluation_id, 'type' => 'cover']) }}"
                                       class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-50 text-blue-700 hover:bg-blue-100 transition">
                                        📄 Cover
                                    </a>
                                @endif
                                @if ($app->evaluation?->summary())
                                    <a href="{{ route('documents.view', ['evaluation' => $app->evaluation_id, 'type' => 'summary']) }}"
                                       class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-50 text-green-700 hover:bg-green-100 transition">
                                        📊 Summary
                                    </a>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm space-x-2">
                                @if ($app->link)
                                    <a href="{{ $app->link }}" target="_blank" rel="noopener noreferrer"
                                       class="text-blue-600 hover:text-blue-900 font-medium">
                                        Visit
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                No applications found. Start tracking your career!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $applications->links() }}
        </div>

        <!-- CTA Section -->
        <div class="mt-12 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-8 text-white shadow-lg">
                <h3 class="text-2xl font-bold mb-2">Looking for new opportunities?</h3>
                <p class="text-blue-100 mb-6">Explore 34 curated job portals to find your next career move.</p>
                <a href="/amrTechAdmin/job-portals" class="inline-block bg-white text-blue-600 font-bold px-6 py-2 rounded-lg hover:bg-blue-50 transition">
                    Discover Job Portals →
                </a>
            </div>
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-8 text-white shadow-lg">
                <h3 class="text-2xl font-bold mb-2">Optimize your profile</h3>
                <p class="text-purple-100 mb-6">Use AI tools to improve your CV and application quality.</p>
                <a href="https://kickresume.com" target="_blank" class="inline-block bg-white text-purple-600 font-bold px-6 py-2 rounded-lg hover:bg-purple-50 transition">
                    Try Kickresume →
                </a>
            </div>
        </div>
    </div>
</div>
