<x-filament-widgets::widget>
    <div class="bg-gradient-to-r from-gray-50 via-gray-100 to-gray-50 dark:from-blue-800 dark:via-blue-900 dark:to-blue-950 rounded-xl px-6 md:px-8 py-5 md:py-6 text-gray-800 dark:text-white relative overflow-hidden border border-gray-200 dark:border-transparent shadow-lg">
        <!-- Dekoratif elementler -->
        <div class="absolute top-0 right-0 transform translate-x-4 -translate-y-4 opacity-10 dark:opacity-20">
            <div class="w-32 h-32 bg-primary-200 dark:bg-white rounded-full"></div>
        </div>
        <div class="absolute bottom-0 left-0 transform -translate-x-4 translate-y-4 opacity-5 dark:opacity-10">
            <div class="w-24 h-24 bg-primary-100 dark:bg-white rounded-full"></div>
        </div>

        <!-- Ana içerik - Tek satır düzeni -->
        <div class="relative z-10">
            <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-5">
                <!-- Sol taraf - Karşılama -->
                <div class="flex-shrink-0">
                    <h1 class="text-lg md:text-2xl font-bold text-gray-800 dark:text-white">Hoşgeldiniz, {{ $user_name }}</h1>
                    <p class="text-gray-600 dark:text-blue-100 text-xs md:text-sm mt-1">Forse Reklam Yönetim Paneli</p>
                </div>

                <!-- Sağ taraf - Hızlı bilgiler -->
                <div class="flex flex-wrap lg:flex-nowrap items-center gap-4">
                    <!-- Yerel Saat Kartı -->
                    <div class="bg-white/70 dark:bg-white/10 rounded-xl px-4 py-3 backdrop-blur-sm border border-gray-200 dark:border-white/20 shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-primary-100 dark:bg-white/20 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-primary-600 dark:text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500 dark:text-blue-200 font-medium">Yerel Saat</div>
                                <div class="font-bold text-base text-gray-800 dark:text-white">{{ now()->format('H:i') }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Bugün Kartı -->
                    <div class="bg-white/70 dark:bg-white/10 rounded-xl px-4 py-3 backdrop-blur-sm border border-gray-200 dark:border-white/20 shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-primary-100 dark:bg-white/20 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-primary-600 dark:text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500 dark:text-blue-200 font-medium">Bugün</div>
                                <div class="font-bold text-base text-gray-800 dark:text-white">{{ now()->translatedFormat('d F Y') }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Durum Kartı -->
                    <div class="bg-white/70 dark:bg-white/10 rounded-xl px-4 py-3 backdrop-blur-sm border border-gray-200 dark:border-white/20 shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-primary-100 dark:bg-white/20 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-primary-600 dark:text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500 dark:text-blue-200 font-medium">Durum</div>
                                <div class="font-bold text-base text-green-600 dark:text-white">Aktif</div>
                            </div>
                        </div>
                    </div>

                    <!-- Güvenlik Kartı -->
                    <div class="bg-white/70 dark:bg-white/10 rounded-xl px-4 py-3 backdrop-blur-sm border border-gray-200 dark:border-white/20 shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-primary-100 dark:bg-white/20 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-primary-600 dark:text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500 dark:text-blue-200 font-medium">Güvenlik</div>
                                <div class="font-bold text-base text-gray-800 dark:text-white">Güvenli</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
