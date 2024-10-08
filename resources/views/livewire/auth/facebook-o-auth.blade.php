<style>
    .fb-material-button {
        -moz-user-select: none;
        -webkit-user-select: none;
        -ms-user-select: none;
        -webkit-appearance: none;
        background-color: WHITE;
        border: 1px solid #747775;
        border-radius: 4px;
        box-sizing: border-box;
        color: #1f1f1f;
        cursor: pointer;
        font-size: 14px;
        height: 40px;
        padding: 0 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 500px;
        transition: background-color 0.218s, border-color 0.218s, box-shadow 0.218s;
        max-width: 500px;
        min-width: min-content;
    }

    .fb-material-button .fb-material-button-icon {
        height: 26px;
        margin-right: 12px;
        min-width: 26px;
        width: 26px;
    }

    .fb-material-button .fb-material-button-content-wrapper {
        display: flex;
        align-items: center;
        height: 100%;
        justify-content: center;
        width: 100%;
    }

    .fb-material-button .fb-material-button-contents {
        font-weight: 500;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .fb-material-button .fb-material-button-state {
        -webkit-transition: opacity .218s;
        transition: opacity .218s;
        bottom: 0;
        left: 0;
        opacity: 0;
        position: absolute;
        right: 0;
        top: 0;
    }

    .fb-material-button:disabled {
        cursor: default;
        background-color: #ffffff61;
        border-color: #1f1f1f1f;
    }

    .fb-material-button:disabled .fb-material-button-contents {
        opacity: 38%;
    }

    .fb-material-button:disabled .fb-material-button-icon {
        opacity: 38%;
    }

    .fb-material-button:hover {
        background-color: #f1f1f1;
        border-color: #d1d1d1;
        -webkit-box-shadow: 0 1px 3px 0 rgba(60, 64, 67, .15), 0 1px 2px 0 rgba(60, 64, 67, .30);
        box-shadow: 0 1px 3px 0 rgba(60, 64, 67, .15), 0 1px 2px 0 rgba(60, 64, 67, .30);
    }

    .fb-material-button:not(:disabled):active .fb-material-button-state,
    .fb-material-button:not(:disabled):focus .fb-material-button-state {
        background-color: #e0e0e0;
        opacity: 12%;
    }

    .fb-material-button:hover .fb-material-button-state {
        background-color: #f1f1f1;
        opacity: 8%;
    }
</style>

<div class="row justify-content-center align-items-center">
    <a href="auth/facebook/redirect"
        class="fb-material-button btn btn-lg w-100 link-offset-2 link-underline link-underline-opacity-0" role="button">
        <div class="fb-material-button-icon">
            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" style="display: block;">
                <path fill="#1877F2"
                    d="M24,4C12.9,4,4,12.9,4,24c0,10,7.5,18.3,17.3,19.8V30.8h-5.2v-6.8h5.2v-4.9c0-5.1,3.1-7.9,7.7-7.9
                    c2.2,0,4.4,0.4,4.4,0.4v4.8h-2.5c-2.5,0-3.3,1.5-3.3,3.1v3.5h5.6l-0.9,6.8h-4.7v13C36.5,42.3,44,34,44,24C44,12.9,35.1,4,24,4z" />
            </svg>
        </div>
        <span class="fb-material-button-contents">Continue with Facebook</span>
    </a>
</div>