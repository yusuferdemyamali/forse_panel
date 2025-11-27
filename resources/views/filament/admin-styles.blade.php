<style>
/* Bahnschrift Light fontu */
@font-face {
    font-family: 'Bahnschrift';
    src: local('Bahnschrift Light'), local('Bahnschrift');
    font-weight: 300;
    font-style: normal;
}

/* Sidebar arka plan rengi */
.fi-sidebar {
    background-color: #22c1c3 !important;
}

/* Sidebar içindeki header (logo alanı) */
.fi-sidebar-header {
    background-color: #22c1c3 !important;
}

/* Sidebar navigasyon alanı */
.fi-sidebar-nav {
    background-color: #22c1c3 !important;
}

/* Sidebar içerik wrapper */
.fi-sidebar .fi-sidebar-content,
aside.fi-sidebar,
aside[class*="fi-sidebar"] {
    background-color: #22c1c3 !important;
}

/* Scrollbar taşma sorununu düzelt */
.fi-sidebar,
.fi-sidebar-nav,
aside.fi-sidebar {
    overflow-x: hidden !important;
}

/* Navigasyon linkleri - beyaz renk */
.fi-sidebar-nav a,
.fi-sidebar-nav .fi-sidebar-item-label,
.fi-sidebar-nav .fi-sidebar-item a span,
.fi-sidebar .fi-sidebar-nav-groups a,
.fi-sidebar-item-button span,
.fi-sidebar-item a span {
    color: white !important;
    font-family: 'Bahnschrift', 'Segoe UI', sans-serif !important;
    font-weight: 300 !important;
}

/* Navigasyon ikonları - beyaz renk */
.fi-sidebar-nav svg,
.fi-sidebar-nav .fi-sidebar-item-icon,
.fi-sidebar-item-button svg,
.fi-sidebar-item svg,
.fi-sidebar .fi-sidebar-nav-groups svg {
    color: white !important;
}

/* Navigasyon grup başlıkları - beyaz renk ve büyük font */
.fi-sidebar-group-label,
.fi-sidebar-nav .fi-sidebar-group button span,
.fi-sidebar-group-button span {
    color: white !important;
    font-size: 0.95rem !important;
    font-weight: 600 !important;
}

/* Grup okları - beyaz renk */
.fi-sidebar-group-button svg,
.fi-sidebar-group svg {
    color: white !important;
}

/* Hover durumunda koyu arka plan ve beyaz yazı */
.fi-sidebar-nav a:hover,
.fi-sidebar-item-button:hover {
    background-color: rgba(0, 0, 0, 0.15) !important;
}

.fi-sidebar-nav a:hover span,
.fi-sidebar-nav a:hover svg,
.fi-sidebar-item-button:hover span,
.fi-sidebar-item-button:hover svg {
    color: white !important;
}

/* Aktif link - biraz daha koyu arka plan */
.fi-sidebar-item-active,
.fi-sidebar-item-active a {
    background-color: rgba(0, 0, 0, 0.2) !important;
}

.fi-sidebar-item-active span,
.fi-sidebar-item-active svg {
    color: white !important;
}

/* Logo beyaz filtresi */
.fi-sidebar-header img,
.fi-logo img,
.fi-sidebar .fi-logo img {
    filter: brightness(0) invert(1) !important;
}

/* Scrollbar stilleri - sidebar ile uyumlu */
.fi-sidebar::-webkit-scrollbar,
.fi-sidebar-nav::-webkit-scrollbar {
    width: 6px;
    background-color: #22c1c3;
}

.fi-sidebar::-webkit-scrollbar-track,
.fi-sidebar-nav::-webkit-scrollbar-track {
    background-color: #22c1c3;
}

.fi-sidebar::-webkit-scrollbar-thumb,
.fi-sidebar-nav::-webkit-scrollbar-thumb {
    background-color: rgba(255, 255, 255, 0.3);
    border-radius: 3px;
}

.fi-sidebar::-webkit-scrollbar-thumb:hover,
.fi-sidebar-nav::-webkit-scrollbar-thumb:hover {
    background-color: rgba(255, 255, 255, 0.5);
}

/* Breadcrumb stilleri */
.fi-breadcrumbs,
.fi-breadcrumbs ol,
.fi-breadcrumbs li,
.fi-breadcrumbs a,
.fi-breadcrumbs span {
    font-size: 1.5rem !important;
    font-weight: 600 !important;
    color: #22c1c3 !important;
}

/* Breadcrumb ayırıcı ok */
.fi-breadcrumbs svg {
    color: #22c1c3 !important;
}

/* Sayfa başlığı (breadcrumb altındaki title) */
.fi-header-heading,
.fi-header h1 {
    font-size: 1.4rem !important;
    font-weight: 550 !important;
}

/* Sağ üst profil avatarı - tüm olası seçiciler */
.fi-avatar {
    --c-50: 34 193 195 !important;
    --c-400: 34 193 195 !important;
    --c-600: 34 193 195 !important;
    background-color: #22c1c3 !important;
    background: #22c1c3 !important;
}

div[class*="fi-avatar"],
span[class*="fi-avatar"],
.filament-avatar,
.fi-user-menu-trigger .fi-avatar,
[x-data] .fi-avatar,
.fi-topbar .fi-avatar {
    background-color: #22c1c3 !important;
    background: #22c1c3 !important;
}

/* Avatar inline style override */
.fi-avatar[style],
div.fi-avatar[style] {
    background-color: #22c1c3 !important;
    background: #22c1c3 !important;
}

/* Buton yazı boyutları */
.fi-btn,
.fi-btn span,
.fi-btn-label {
    font-size: 0.8rem !important;
    font-weight: 500 !important;
}

/* Tarayıcı oturumları bölümü - buton sağa hizalama */
#tarayici-oturumlari .fi-section-content .fi-fo-component-ctn {
    display: flex;
    flex-wrap: nowrap;
    align-items: flex-end;
    gap: 1rem;
}

#tarayici-oturumlari .fi-section-content .fi-fo-component-ctn > div:first-child {
    flex: 1;
}

#tarayici-oturumlari .fi-section-content .fi-fo-component-ctn > div:last-child {
    flex-shrink: 0;
    margin-bottom: 0rem;
}
</style>



