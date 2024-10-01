@props([
  'invoices' => []
])

<div class="flex flex-col">
  <div class="-m-1.5 overflow-x-auto">
    <div class="p-1.5 min-w-full inline-block align-middle">
      <div class="overflow-hidden border rounded-lg dark:border-neutral-700">
        <table
          class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
          <thead>
            <tr>
              <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start dark:text-neutral-500">Company</th>
              <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start dark:text-neutral-500">Total amount</th>
              <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start dark:text-neutral-500">Paid on</th>
              <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-end dark:text-neutral-500">Status</th>
            </tr>
          </thead>

          <tbody
            class="divide-y divide-gray-200 dark:divide-neutral-700">

            @foreach($invoices as $invoice)

              <tr>
                <td
                  class="px-6 py-4 text-sm font-medium text-gray-800 whitespace-nowrap dark:text-neutral-200">
                  {{ $invoice->contact->company->name }}
                </td>

                <td
                  class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap dark:text-neutral-200">
                  {{ number_format($invoice->total_amount, 2) }}
                </td>

                <td
                  class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap dark:text-neutral-200">
                  {{ $invoice->updated_at->format('d M, Y') }}
                </td>

                <td class="px-6 py-4 text-sm font-medium capitalize whitespace-nowrap text-end">
                  {{ $invoice->status }}
                  {{-- <button type="button" class="inline-flex items-center text-sm font-semibold text-blue-600 border border-transparent rounded-lg gap-x-2 hover:text-blue-800 focus:outline-none focus:text-blue-800 disabled:opacity-50 disabled:pointer-events-none dark:text-blue-500 dark:hover:text-blue-400 dark:focus:text-blue-400">Delete</button> --}}
                </td>
              </tr>

            @endforeach

          </tbody>

        </table>

      </div>
    </div>
  </div>
</div>
