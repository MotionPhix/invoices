<div class="min-h-screen bg-gray-100 dark:bg-neutral-800">

  @include('layouts.navigation')

  <!-- Page Heading -->
  <header class="sticky top-0 z-20 bg-white shadow">

    <div class="flex items-center px-4 py-2 mx-auto max-w-7xl sm:px-6 lg:px-8">
      {{ $header }}
    </div>

  </header>

  <!-- Page Content -->
  <main>
    {{ $slot }}
  </main>

</div>
