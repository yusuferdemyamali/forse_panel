<div class="text-center">
    <a href="{{ filament()->getRequestPasswordResetUrl() }}" 
       class="text-sm text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300 font-medium underline">
        {{ __('Şifremi Unuttum?') }}
    </a>
</div>

<style>
body {
    background: white !important;
    min-height: 100dvh !important;
    height: 100dvh !important;
    overflow: hidden;
}

html,
body,
.fi-simple-layout,
.fi-simple-main-ctn {
    min-height: 100dvh !important;
    height: 100dvh !important;
}

.fi-simple-layout {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    box-sizing: border-box;
    padding: 1rem;
    padding-top: calc(1rem + env(safe-area-inset-top, 0px));
    padding-bottom: calc(1rem + env(safe-area-inset-bottom, 0px));
    overflow: hidden;
}

.fi-simple-main-ctn {
    display: flex;
    width: 100%;
    box-sizing: border-box;
    align-items: center !important;
    justify-content: center !important;
    padding: 0 !important;
}

.fi-simple-main {
    margin-top: 0 !important;
    margin-bottom: 0 !important;
}

@media screen and (min-width: 1280px) {
    body::before {
        content: "";
        position: fixed;
        top: 0;
        left: 100px;
        width: 600px;
        height: 100dvh;
        background: url('/images/login-image.jpg?v=3') center center no-repeat;
        background-size: contain;
        z-index: 0;
    }

    main {
        position: absolute; 
        right: 100px;
        z-index: 1;
    }

    main:before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: -9;
        border-radius: 0px !important;
        box-shadow: 0px 0px 0px 0px #555;
    }

    .fi-simple-header-heading {
        display: none;
    }

    .fi-logo {
        position: fixed;
        right: 300px;
        top: 125px;
        color: cornsilk;
    }

}

@media screen and (min-width: 1536px) {
    body::before {
        width: 550px;
    }
}

@media screen and (min-width: 1920px) {
    body::before {
        width: 650px;
                left: 50px;

    }
}

</style>