<style>
/* ============================================================
   LEGACY ENTERPRISE ERP/CRM TRANSFORMATION
   Visual Reference: AdminLTE 2 / Classic Bootstrap 3 (2015 Era)
   ============================================================ */

/* === TYPOGRAPHY === */
@import url('https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600;700&display=swap');

body {
    font-family: 'Source Sans Pro', 'Helvetica Neue', Helvetica, Arial, sans-serif !important;
    font-size: 14px !important;
    line-height: 1.42857143 !important;
    background-color: #ecf0f5 !important;
}

/* === ROUNDED CORNERS REMOVAL === */
.fi-btn,
.fi-icon-btn,
.fi-input-wrp,
.fi-select,
.fi-section,
.fi-section-content-ctn,
.fi-ta,
.fi-ta-ctn,
.fi-wi,
.fi-modal-window,
.fi-dropdown-panel,
.fi-avatar,
.fi-badge,
.fi-notification,
.fi-pagination button,
.fi-tabs-item,
button,
input,
select,
textarea {
    border-radius: 0 !important;
}

/* === BODY & MAIN BACKGROUND === */
.fi-body,
.fi-main,
.fi-main-ctn {
    background-color: #ecf0f5 !important;
}

/* === TOP HEADER === */
.fi-topbar {
    background-color: #22c1c3 !important;
    border-bottom: 1px solid #1ba8aa !important;
    box-shadow: none !important;
}

.fi-topbar-item,
.fi-topbar > nav {
    background-color: transparent !important;
}

/* Topbar text white */
.fi-topbar .fi-dropdown-trigger,
.fi-topbar button,
.fi-topbar a,
.fi-topbar span,
.fi-topbar svg {
    color: #ffffff !important;
}

/* Breadcrumbs */
.fi-breadcrumbs a,
.fi-breadcrumbs span,
.fi-breadcrumbs svg {
    color: rgba(255, 255, 255, 0.85) !important;
    font-size: 13px !important;
}

.fi-breadcrumbs a:hover {
    color: #ffffff !important;
}

/* === SIDEBAR === */
.fi-sidebar {
    background-color: #22c1c3 !important;
    box-shadow: none !important;
}

.fi-sidebar-header {
    background-color: #22c1c3 !important;
    border-bottom: 1px solid #1ba8aa !important;
}

.fi-sidebar-nav {
    background-color: #22c1c3 !important;
}

/* Sidebar logo white */
.fi-sidebar-header img,
.fi-logo img {
    filter: brightness(0) invert(1) !important;
}

/* Sidebar nav items */
.fi-sidebar-item-button {
    color: #ffffff !important;
    padding: 10px 15px !important;
    font-size: 14px !important;
    border-left: 3px solid transparent !important;
}

.fi-sidebar-item-button span {
    color: #ffffff !important;
}

.fi-sidebar-item-button svg {
    color: #ffffff !important;
    width: 20px !important;
    height: 20px !important;
}

/* Sidebar hover */
.fi-sidebar-item-button:hover {
    background-color: rgba(0, 0, 0, 0.15) !important;
    border-left-color: #ffffff !important;
}

.fi-sidebar-item-button:hover span,
.fi-sidebar-item-button:hover svg {
    color: #ffffff !important;
}

/* Sidebar active - AdminLTE signature */
.fi-sidebar-item-active .fi-sidebar-item-button {
    background-color: rgba(0, 0, 0, 0.2) !important;
    border-left: 3px solid #ffffff !important;
}

.fi-sidebar-item-active .fi-sidebar-item-button span,
.fi-sidebar-item-active .fi-sidebar-item-button svg {
    color: #ffffff !important;
}

/* Sidebar group labels */
.fi-sidebar-group-button span {
    color: rgba(255, 255, 255, 0.85) !important;
    font-size: 12px !important;
    font-weight: 600 !important;
    text-transform: uppercase !important;
    letter-spacing: 0.5px !important;
}

.fi-sidebar-group-button svg {
    color: rgba(255, 255, 255, 0.85) !important;
}

/* === CONTENT CARDS / SECTIONS === */
.fi-section {
    background-color: #ffffff !important;
    border: 1px solid #d2d6de !important;
    border-top: 3px solid #22c1c3 !important;
    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1) !important;
}

.fi-section-header {
    background-color: #f4f4f4 !important;
    border-bottom: 1px solid #d2d6de !important;
    padding: 10px 15px !important;
}

.fi-section-header-heading {
    color: #444444 !important;
    font-size: 16px !important;
    font-weight: 600 !important;
}

.fi-section-content-ctn {
    padding: 15px !important;
    background-color: #ffffff !important;
}

/* === TABLES === */
.fi-ta {
    background-color: #ffffff !important;
    border: 1px solid #d2d6de !important;
    border-top: 3px solid #22c1c3 !important;
    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1) !important;
}

.fi-ta-header {
    background-color: #f4f4f4 !important;
    border-bottom: 1px solid #d2d6de !important;
    padding: 10px 15px !important;
}

.fi-ta-header-heading {
    color: #444444 !important;
    font-size: 16px !important;
    font-weight: 600 !important;
}

.fi-ta-table th {
    background-color: #f9f9f9 !important;
    border-bottom: 2px solid #d2d6de !important;
    font-weight: 600 !important;
    color: #333333 !important;
    font-size: 13px !important;
    padding: 8px 10px !important;
}

.fi-ta-table td {
    padding: 8px 10px !important;
    font-size: 13px !important;
    border-top: 1px solid #f4f4f4 !important;
    color: #333333 !important;
}

.fi-ta-table tbody tr:nth-child(odd) {
    background-color: #f9f9f9 !important;
}

.fi-ta-table tbody tr:nth-child(even) {
    background-color: #ffffff !important;
}

.fi-ta-table tbody tr:hover {
    background-color: #f5f5f5 !important;
}

/* Table pagination */
.fi-ta-pagination {
    background-color: #f9f9f9 !important;
    border-top: 1px solid #d2d6de !important;
    padding: 10px 15px !important;
}

/* === BUTTONS === */
.fi-btn {
    border-radius: 0 !important;
    text-transform: uppercase !important;
    font-size: 12px !important;
    font-weight: 700 !important;
    padding: 6px 12px !important;
    box-shadow: none !important;
    letter-spacing: 0.5px !important;
}

/* Primary buttons */
.fi-btn.fi-color-primary,
.fi-btn[wire\:loading\.attr="disabled"] {
    background-color: #22c1c3 !important;
    border: 1px solid #1ba8aa !important;
    color: #ffffff !important;
}

.fi-btn.fi-color-primary:hover {
    background-color: #1ba8aa !important;
}

/* Danger buttons */
.fi-btn.fi-color-danger {
    background-color: #dd4b39 !important;
    border: 1px solid #d73925 !important;
    color: #ffffff !important;
}

/* Success buttons */
.fi-btn.fi-color-success {
    background-color: #00a65a !important;
    border: 1px solid #008d4c !important;
    color: #ffffff !important;
}

/* Gray/Secondary buttons */
.fi-btn.fi-color-gray {
    background-color: #f4f4f4 !important;
    border: 1px solid #ddd !important;
    color: #444444 !important;
}

.fi-btn.fi-color-gray:hover {
    background-color: #e7e7e7 !important;
}

/* Icon buttons */
.fi-icon-btn {
    border-radius: 0 !important;
}

/* === INPUTS === */
.fi-input-wrp {
    border: 1px solid #d2d6de !important;
    border-radius: 0 !important;
    background-color: #ffffff !important;
    box-shadow: none !important;
}

.fi-input-wrp:focus-within {
    border-color: #22c1c3 !important;
    box-shadow: none !important;
    --tw-ring-color: transparent !important;
}

.fi-input {
    font-size: 14px !important;
    color: #333333 !important;
}

/* Select inputs */
.fi-select-input {
    font-size: 14px !important;
    color: #333333 !important;
}

/* Labels */
.fi-fo-field-wrp-label label {
    font-weight: 600 !important;
    color: #333333 !important;
    font-size: 14px !important;
}

/* === DROPDOWNS === */
.fi-dropdown-panel {
    background-color: #ffffff !important;
    border: 1px solid #d2d6de !important;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175) !important;
}

.fi-dropdown-list-item {
    font-size: 14px !important;
    color: #333333 !important;
}

.fi-dropdown-list-item:hover {
    background-color: #f5f5f5 !important;
}

/* === MODALS === */
.fi-modal-window {
    background-color: #ffffff !important;
    border: 1px solid #d2d6de !important;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175) !important;
}

.fi-modal-header {
    background-color: #f4f4f4 !important;
    border-bottom: 1px solid #d2d6de !important;
}

.fi-modal-footer {
    background-color: #f4f4f4 !important;
    border-top: 1px solid #d2d6de !important;
}

/* === AVATAR === */
.fi-avatar {
    border-radius: 0 !important;
    background-color: #22c1c3 !important;
}

/* === BADGES === */
.fi-badge {
    border-radius: 0 !important;
    font-size: 11px !important;
    font-weight: 700 !important;
    text-transform: uppercase !important;
}

/* === WIDGETS === */
.fi-wi {
    background-color: #ffffff !important;
    border: 1px solid #d2d6de !important;
    border-top: 3px solid #22c1c3 !important;
    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1) !important;
}

.fi-wi-stats-overview-stat {
    border-radius: 0 !important;
    border: 1px solid #d2d6de !important;
    border-left: 3px solid #22c1c3 !important;
    background-color: #ffffff !important;
}

/* === NOTIFICATIONS === */
.fi-notification {
    border-radius: 0 !important;
    border-left: 4px solid #22c1c3 !important;
    background-color: #ffffff !important;
    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1) !important;
}

/* === TABS === */
.fi-tabs {
    border-bottom: 1px solid #d2d6de !important;
    background-color: #f9f9f9 !important;
}

.fi-tabs-item {
    border-radius: 0 !important;
    font-size: 14px !important;
    color: #333333 !important;
}

.fi-tabs-item:hover {
    border-bottom: 2px solid #22c1c3 !important;
}

.fi-tabs-item[aria-selected="true"] {
    border-bottom: 2px solid #22c1c3 !important;
    color: #22c1c3 !important;
    font-weight: 600 !important;
}

/* === PAGINATION === */
.fi-pagination-item {
    border-radius: 0 !important;
    border: 1px solid #d2d6de !important;
    background-color: #ffffff !important;
    color: #333333 !important;
}

.fi-pagination-item:hover {
    background-color: #f5f5f5 !important;
}

.fi-pagination-item[aria-current="page"] {
    background-color: #22c1c3 !important;
    border-color: #1ba8aa !important;
    color: #ffffff !important;
}

/* === FORM ACTIONS === */
.fi-fo-actions {
    background-color: #f4f4f4 !important;
    border-top: 1px solid #d2d6de !important;
    padding: 15px !important;
}

/* === CHECKBOX & RADIO === */
.fi-checkbox-input,
.fi-radio-input {
    border-radius: 0 !important;
    border: 1px solid #d2d6de !important;
}

.fi-checkbox-input:checked,
.fi-radio-input:checked {
    background-color: #22c1c3 !important;
    border-color: #22c1c3 !important;
}

/* === LINKS === */
.fi-link {
    color: #22c1c3 !important;
}

.fi-link:hover {
    color: #5dd3d5 !important;
}

/* === EMPTY STATE === */
.fi-ta-empty-state {
    padding: 40px !important;
    color: #666666 !important;
}

/* === PAGE HEADER === */
.fi-header {
    margin-bottom: 15px !important;
}

.fi-header-heading {
    color: #333333 !important;
    font-weight: 600 !important;
}

/* === DARK MODE OVERRIDE === */
.dark .fi-body,
.dark .fi-main,
.dark .fi-main-ctn {
    background-color: #ecf0f5 !important;
}

.dark .fi-sidebar {
    background-color: #22c1c3 !important;
}

.dark .fi-topbar {
    background-color: #22c1c3 !important;
}

.dark .fi-section,
.dark .fi-ta,
.dark .fi-wi {
    background-color: #ffffff !important;
}

.dark .fi-section-content-ctn,
.dark .fi-ta-table td,
.dark .fi-ta-table th {
    color: #333333 !important;
}

/* === SIDEBAR SCROLLBAR === */
.fi-sidebar::-webkit-scrollbar {
    width: 5px;
    background-color: #22c1c3;
}

.fi-sidebar::-webkit-scrollbar-thumb {
    background-color: rgba(0, 0, 0, 0.2);
}

/* === INFOLIST === */
.fi-in-entry {
    border-bottom: 1px solid #f4f4f4 !important;
    padding: 8px 0 !important;
}

/* === FILTERS === */
.fi-ta-filters-form {
    background-color: #f9f9f9 !important;
    border: 1px solid #d2d6de !important;
    padding: 15px !important;
}
</style>
