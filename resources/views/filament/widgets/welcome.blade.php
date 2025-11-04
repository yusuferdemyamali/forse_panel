<x-filament-widgets::widget>
    <div class="bg-gradient-to-r from-gray-50 via-gray-100 to-gray-50 dark:from-blue-800 dark:via-blue-900 dark:to-blue-950 rounded-xl p-4 md:p-8 text-gray-800 dark:text-white relative overflow-hidden border border-gray-200 dark:border-transparent shadow-lg">
        <!-- Dekoratif elementler -->
        <div class="absolute top-0 right-0 transform translate-x-4 -translate-y-4 opacity-10 dark:opacity-20">
            <div class="w-32 h-32 bg-primary-200 dark:bg-white rounded-full"></div>
        </div>
        <div class="absolute bottom-0 left-0 transform -translate-x-4 translate-y-4 opacity-5 dark:opacity-10">
            <div class="w-24 h-24 bg-primary-100 dark:bg-white rounded-full"></div>
        </div>

        <!-- Ana içerik -->
        <div class="relative z-10">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-4 gap-4">
                <div class="flex items-center space-x-3">
                    <div>
                        <h1 class="text-lg md:text-2xl font-bold text-gray-800 dark:text-white">{{ $greeting }}, {{ $user_name }}!</h1>
                        <p class="text-gray-600 dark:text-blue-100 text-xs md:text-sm">Forse Reklam Yönetim Paneli'ne hoş geldiniz</p>
                    </div>
                </div>
            </div>

            <!-- Hızlı bilgiler -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4 mt-6">
                <div class="bg-white/60 dark:bg-white/10 rounded-lg p-2 md:p-3 backdrop-blur-sm border border-gray-200 dark:border-white/20">
                    <div class="flex items-center space-x-1 md:space-x-2">
                        <svg class="w-4 h-4 md:w-5 md:h-5 text-primary-600 dark:text-blue-200 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="min-w-0">
                            <div class="text-[10px] md:text-xs text-gray-600 dark:text-blue-200">Yerel Saat</div>
                            <div class="font-semibold text-xs md:text-sm text-gray-800 dark:text-white truncate">{{ now()->format('H:i') }}</div>
                        </div>
                    </div>
                </div>
                <div class="bg-white/60 dark:bg-white/10 rounded-lg p-2 md:p-3 backdrop-blur-sm border border-gray-200 dark:border-white/20">
                    <div class="flex items-center space-x-1 md:space-x-2">
                        <svg class="w-4 h-4 md:w-5 md:h-5 text-primary-600 dark:text-blue-200 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <div class="min-w-0">
                            <div class="text-[10px] md:text-xs text-gray-600 dark:text-blue-200">Bugün</div>
                            <div class="font-semibold text-xs md:text-sm text-gray-800 dark:text-white truncate">{{ now()->format('d.m') }}</div>
                        </div>
                    </div>
                </div>
                <div class="bg-white/60 dark:bg-white/10 rounded-lg p-2 md:p-3 backdrop-blur-sm border border-gray-200 dark:border-white/20">
                    <div class="flex items-center space-x-1 md:space-x-2">
                        <svg class="w-4 h-4 md:w-5 md:h-5 text-primary-600 dark:text-blue-200 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <div class="min-w-0">
                            <div class="text-[10px] md:text-xs text-gray-600 dark:text-blue-200">Durum</div>
                            <div class="font-semibold text-xs md:text-sm text-gray-800 dark:text-white truncate">Aktif</div>
                        </div>
                    </div>
                </div>
                <div class="bg-white/60 dark:bg-white/10 rounded-lg p-2 md:p-3 backdrop-blur-sm border border-gray-200 dark:border-white/20">
                    <div class="flex items-center space-x-1 md:space-x-2">
                        <svg class="w-4 h-4 md:w-5 md:h-5 text-primary-600 dark:text-blue-200 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        <div class="min-w-0">
                            <div class="text-[10px] md:text-xs text-gray-600 dark:text-blue-200">Güvenlik</div>
                            <div class="font-semibold text-xs md:text-sm text-gray-800 dark:text-white truncate">Güvenli</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Motivasyon mesajı -->
        </div>
    </div>
</x-filament-widgets::widget>
