<html>
  <head>
    @vite(['resources/css/app.css'])
    
    @livewireStyles
    
    @vite(['resources/js/app.js'])
  </head>
  <body>
    livewireテスト <span class="text-blue-300">register</span> 
    @livewire('register')
    @livewireScripts
  </body>
</html>