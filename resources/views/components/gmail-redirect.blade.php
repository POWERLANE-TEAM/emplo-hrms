@props(['email'])

<a href="https://mail.google.com/mail/?view=cm&fs=1&to={{ $email }}" target="_blank">
    {{ $email }}
</a>