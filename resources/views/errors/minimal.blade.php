<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
    <style>
        body { margin: 0; min-height: 100vh; display: grid; place-items: center; font-family: ui-sans-serif, system-ui, sans-serif; background: #09090b; color: #fafafa; }
        main { max-width: 32rem; padding: 2rem; }
        .code { font-family: ui-monospace, Menlo, monospace; color: #5eead4; font-size: .875rem; }
        h1 { font-size: 1.75rem; margin: .5rem 0 1rem; letter-spacing: -.02em; }
        p { color: #a1a1aa; line-height: 1.6; }
        a { color: #5eead4; font-weight: 500; }
    </style>
</head>
<body>
    <main>
        <p class="code">@yield('code')</p>
        <h1>@yield('message')</h1>
        <p>@yield('detail')</p>
        <p><a href="/">Return to the home page</a></p>
    </main>
</body>
</html>
